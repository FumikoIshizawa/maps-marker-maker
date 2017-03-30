<?php
  global $post;
  $default_value = get_post_meta($post->ID, 'mmm_is_shown', true);
  $default = $default_value ? 'checked="checked"' : '';
?>
<table class="form-table">
  <tbody>
    <tr>
      <th scope="row">
        Check this box to hide maps.
      </th>
      <td>
        <input type="hidden" name="mmm_is_shown" value="0">
        <input type="checkbox" id="mmm_is_shown" name="mmm_is_shown" <?php echo $default; ?>>
      </td>
    </tr>
  </tbody>
</table>
