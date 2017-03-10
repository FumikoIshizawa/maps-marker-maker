<?php
  $API_KEY = get_site_option('mmm_api_key');
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
    </tr>
  </tbody>
</table>
<?php submit_button(); ?>
</form>
