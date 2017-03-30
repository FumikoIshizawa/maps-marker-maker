# Maps Marker Maker for Wordpress
A summarized map of locations described in the article.

Maps Marker Maker allows you to embed a new Google Maps according to the article, which has already embedded some iframe for Google Maps. Therefore, it allows a user to see all locations in the article at the same time.

Especially, it is useful to you, who introduce some places, such as sightseeing spots, restaurants or etc, in your site.

## Features
- Parse article contents and search iframe code for Google Maps.
- Summarize locations and show another Google Maps.
- Allows you to customize map design.

## Configuration
From settings page, API Key, map design and default markers' text.

- API Key: get from [Google Developer Console](https://console.developers.google.com/)
- Map design: a map design's code
- Default markers' text: this text will show on marker when there is no description of location caused by parse error or uncorresponded languages(ex: Japanese)

## Embedded Google Maps
Following [Embed a map or share a location](https://support.google.com/maps/answer/144361?co=GENIE.Platform%3DDesktop&hl=en).

```
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10650.329016424288!2d11.5755539!3d48.137579!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x51008b61fc157967!2sNeues+Rathaus!5e0!3m2!1sja!2sth!4v1472279471038" width="400" height="300" frameborder="0"></iframe>
```

Longitude, latitude and location's name are extracted.

## Use in your theme's source code
If there is any maps's description, `mmm_make_markers` will return html codes for a new maps. Otherwise, it will return `false`.

```
<?php
  $content = get_the_content();
  if (function_exists('mmm_make_markers')) {
    if ($markers = mmm_make_markers($content)) {
      echo $markers;
    }
  }
?>
```

## Deactivate maps
Maps Marker Maker allows you to decide whether show a maps or not for each articles.

In the edit page, under the edit parts, you will see Maps Marker Maker's setting. If the maps should not be shown, please check the checkbox.
