<?php
class MarkerMaker {
  private $content = '';

  function __construct($content) {
    $this->content = $content;
  }

  public function has_or_create_locations() {
    if ($locations = $this->has_locations($this->content)) {
      $API_KEY = get_site_option('mmm_api_key');
      $center_lat = $locations[0]['lat'];
      $center_lng = $locations[1]['lng'];
      $markers_html = $this->create_markers_html($locations);

      return '<div id="locations_map" style="height:400px;"></div>
        <script>
          var map;
          function initMap() {
            var styles = [
              {
                stylers: [
                  { hue: "#00ffe6" },
                  { saturation: -20 }
                ]
              },{
                featureType: "road",
                elementType: "geometry",
                stylers: [
                  { lightness: 100 },
                  { visibility: "simplified" }
                ]
              },{
                featureType: "road",
                elementType: "labels",
                stylers: [
                  { visibility: "off" }
                ]
              }
            ];
            var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});

            var mapData  = { position: {lat: '. $center_lat .', lng: '. $center_lng .'}, zoom: 14, fitBounds: true };
            map = new google.maps.Map(document.getElementById("locations_map"), {
              center: mapData.position,
              zoom: mapData.zoom,
              mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, "map_style"]
              }
            });
            map.mapTypes.set("map_style", styledMap);
            map.setMapTypeId("map_style");
            var fitBounds  = new google.maps.LatLngBounds();
            ' . $markers_html . '
            if(mapData.fitBounds) {
              map.fitBounds(fitBounds);
            }
          }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=' . $API_KEY . '&callback=initMap" async defer></script>';
    } else {
      return false;
    }
  }

  function has_locations() {
    $this->content = mb_convert_encoding($this->content, "HTML-ENTITIES", "auto");
    $dom = new DOMDocument;
    @$dom->loadHTML($this->content);
    $xpath = new DOMXPath($dom);

    if ($xpath->evaluate('string(//div[@class="info_tmp"]/iframe/@src)') == '') {
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
          'detail' => $xpath->evaluate('string(.)', $node),
        ];
      }
    }
    return $locations;
  }

  function create_markers_html($locations) {
    $this->content = '';
    $count = 0;
    foreach ($locations as $location) {
      $detail = $location['detail'];
      $this->content .= 'var marker' . $count . ' = new google.maps.Marker({
        position: {lat: ' . $location['lat'] . ', lng: ' . $location['lng'] . '},
        map: map,
        title: "Hello World!"
      });
      var infowindow = new google.maps.InfoWindow({
        content: "詳細は記事をご覧ください"
      });
      marker' . $count . '.addListener("click", function() {
        infowindow.open(map, marker' . $count . ');
      });
      fitBounds.extend(new google.maps.LatLng({
        lat: ' . $location['lat'] . ',
        lng: ' . $location['lng'] . '
      }));
      ';
      $count++;
    }

    return $this->content;
  }
}
?>
