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

function mmm_make_markers($content) {
  if ($locations = has_locations($content)) {
    $API_KEY = get_site_option('mmm_api_key');
    $center_lat = 48.137579;
    $center_lng = 11.5755539;
    $zoom = 14;
    $markers_html = create_markers_html($locations);

    return '<div id="locations_map" style="height:400px;"></div>
      <script>
        var map;
        function initMap() {
          map = new google.maps.Map(document.getElementById("locations_map"), {
            center: {lat: '. $center_lat .', lng: '. $center_lng .'},
            zoom: '. $zoom .'
          });
        }' . $markers_html . '
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=' . $API_KEY . '&callback=initMap" async defer></script>';
  } else {
    return false;
  }
}

function has_locations($content) {
  $content = mb_convert_encoding($content, "HTML-ENTITIES", "auto");
  $dom = new DOMDocument;
  @$dom->loadHTML($content);
  $xpath = new DOMXPath($dom);

  if ($xpath->evaluate('string(//div[@class="info_tmp"]/iframe/@src)') == '') {
    echo $xpath->evaluate('string(//div[@class="info_tmp"]/iframe/@src)');
    return false;
  }

  $locations = [];
  foreach ($xpath->query('//div[@class="info_tmp"]') as $node) {
    $maps_url = $xpath->evaluate('string(.//iframe/@src)', $node);
    if (preg_match('/https:\/\/www\.google\.com\/maps\//', $maps_url)) {
      $pattern = '/https:\/\/www\.google\.com\/maps\/embed\?pb=[a-zA-Z0-9!\.]+\!2d(\w+\.\w+)\!3d(\w+\.\w+)/';
      preg_match($pattern, $maps_url, $matches);
      $locations[] = [
        'lng' => $matches[1],
        'lat' => $matches[2],
      ];
      echo $matches[1] . '<br>';
      echo $matches[2];
    }
  }
  return $locations;
}

function create_markers_html($locations) {
  $content = '';
  foreach ($locations as $location) {
    $content += 'var marker = new google.maps.Marker({
      position: {lat: ' . $location['lat'] . ', lng: ' . $location['lng'] . '},
      map: map,
      title: "Hello World!"
    });';
  }

  return $content;
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
