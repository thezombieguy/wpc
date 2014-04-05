<div class="link">
  <label for="<?php print $id; ?>">
    <?php _e($label . ' link', WPC_TEXTDOMAIN ); ?>
  </label>
  <input class="wpc-link" type="hidden" id="<?php print $id; ?>" name="<?php print $field; ?>" value="<?php print esc_attr( $value ); ?>" size="25" />
  <div class="widget-desc">
    <i><?php print $description; ?></i>
  </div>
  <input type="button" data-linkid="#<?php print $id; ?>" class="wpc-link-btn" value="Add/Edit">
  <?php $link = json_decode($value); ?>
  <a id="<?php print $id; ?>_link"  href="<?php print $link->href; ?>" target="<?php print $link->target; ?>"><?php print $link->title; ?></a>
  <?php if(!empty($link->href)): ?>
  <a href="#" class="wpc-remove-link link" data-target="#<?php print $id; ?>" >(remove)</a>
  <?php endif; ?>
</div>

