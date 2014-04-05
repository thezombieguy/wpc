<div class="textfield">
  <label for="<?php print $id; ?>">
    <?php _e($label, WPC_TEXTDOMAIN ); ?>
  </label>
  <input type="text" id="<?php print $id; ?>" name="<?php print $field; ?>" value="<?php print esc_attr( $value ); ?>" size="25" />
  <div class="widget-desc">
  <i><?php print $description; ?></i>
  </div>
</div>
