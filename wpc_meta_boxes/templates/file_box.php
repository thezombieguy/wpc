<div class="uploader">
  <input type="text" style="width: 100%;" name="<?php print $field; ?>" id="<?php print $field; ?>" value="<?php print $value; ?>"/>
  <input class="wpc-button button" data-filetype="file" name="<?php print $field; ?>_button" id="<?php print $field; ?>_button" value="Upload" />
  <div class="widget-desc">
    <i><?php print $description; ?></i>
  </div>
  <?php print WPC_Utils::l($value, WPC_Utils::filename_from_path($value), array('id' => "file-$field")); ?>
</div>

