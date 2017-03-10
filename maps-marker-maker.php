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
  $markers = new MarkerMaker($content);
  return $markers->has_or_create_locations();
}

add_action( 'admin_menu', 'mmm_add_plugin_menu' );
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
}

function mmm_show_plugin_page() {
  include_once('views/settings.php');
}

function mmm_register_api_key($input) {
  return $input;
}
?>
