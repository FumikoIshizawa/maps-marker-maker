<?php
  $API_KEY = get_site_option('mmm_api_key');
  $MAP_COLOR = get_site_option('mmm_map_color');
?>
<div class="wrap">
  <h2>Maps Marker Maker Settings</h2>
<form method="post" action="options.php">
<?php
  settings_fields( 'mmm-group' );
  do_settings_sections( 'default' );
?>
<table class="form-table">
  <tbody>
    <tr>
      <th scope="row"><label for="mmm_api_key">Your API Key</label></th>
      <td>
        <input type="hidden" name="mmm_api_key" value="0">
        <label for="mmm_api_key"><input type="text" id="mmm_api_key" name="mmm_api_key" size="30" value="<?php echo $API_KEY; ?>"/></input></label>
      </td>
    <tr>
    </tr>
      <th scope="row"><label for="mmm_api_key">Map Color(default: #00ffe6)</label></th>
      <td>
        <input type="hidden" name="mmm_map_color" value="0">
        <label for="mmm_map_color"><input type="text" id="mmm_map_color" name="mmm_map_color" size="10" value="<?php echo $MAP_COLOR; ?>"/></input></label>
      </td>
    </tr>
  </tbody>
</table>
<?php submit_button(); ?>
</form>
