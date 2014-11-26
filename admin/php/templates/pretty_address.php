<?php ob_start(); ?>
<?php echo $cart->address; ?><br>
<?php echo $cart->city; ?>, <?php echo $cart->province; ?><br>
<?php echo $cart->country; ?>, <?php echo $cart->postal_code; ?>
<?php $return = ob_get_contents(); ob_end_clean(); return $return; ?>