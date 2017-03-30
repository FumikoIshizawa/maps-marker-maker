<?php
/*
Plugin Name: Maps Marker Maker
Plugin URI:
Description: Make makers on Google maps according to locations described in the article.
Version: 1.0.0
Author: fumikoi
Author URI: http://tabippo.net
License: GPL2
*/

/*  Copyright 2017/03/10 fumikoi(fumikoi@tabippo.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require 'src/marker-maker.php';

function mmm_make_markers($content) {
  global $post;
  $is_shown = get_post_meta($post->ID, 'mmm_is_shown', true);
  if ($is_shown != 'on') {
    $markers = new MarkerMaker($content);
    return $markers->has_or_create_locations();
  }
}

function mmm_add_plugin_menu() {
  add_options_page(
    'Maps Marker Maker',
    'Maps Marker Maker',
    'administrator',
    'maps-marker-maker',
    'mmm_show_plugin_page'
  );
  register_setting(
    'mmm-group',
    'mmm_api_key',
    'mmm_register_api_key'
  );
  register_setting(
    'mmm-group',
    'mmm_map_color',
    'mmm_register_map_color'
  );
  register_setting(
    'mmm-group',
    'mmm_nodetail_text',
    'mmm_register_nodetail_text'
  );
}
add_action('admin_menu', 'mmm_add_plugin_menu');

function mmm_show_plugin_page() {
  include_once('views/settings.php');
}

function mmm_register_api_key($input) {
  return $input;
}

function mmm_add_meta_box($content) {
  add_meta_box('mmm_meta_box', 'Maps Maker Maker', 'mmm_meta_box', 'post', 'advanced', 'high');
}

function mmm_meta_box() {
  include_once('views/post-settings.php');
}
add_action('admin_init', 'mmm_add_meta_box');

function mmm_save_post($post_id) {
  if (array_key_exists('post_type', $_POST) && 'page' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id)) {
      return $post_id;
    }
  } elseif (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }

  $value = (isset($_POST['mmm_is_shown'])) ? $_POST['mmm_is_shown'] : null;
  if (!add_post_meta($post_id, 'mmm_is_shown', $value, true)) {
    update_post_meta($post_id, 'mmm_is_shown', $value);
  }
}
add_action('save_post', 'mmm_save_post');
?>
