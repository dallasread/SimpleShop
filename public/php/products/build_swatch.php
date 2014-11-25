<?php $swatches = explode("|", $colours); ?>
<div class="simpleshop_swatch simpleshop_swatch_count_<?php echo count($swatches); ?> <?php if ($selected == $colours) { echo "selected"; } ?>" data-colour="<?php echo $colours; ?>" data-id="<?php echo $id; ?>">
	<div class="outer_swatch" style="<?php
		if (count($swatches) != 1) { echo "width: " . $size . "px; height: " . $size . "px;"; }
	?>">
		<?php $n = 0; foreach ($swatches as $swatch) { ?>
			<div class="inner_swatch" style="<?php
				if (count($swatches) == 1) {
					echo "background: $swatch; width: " . $size . "px; height: " . $size . "px;";
				} else {
					if ($n == 0) {
						$n += 1;
						echo "position: absolute; border-top: " . $size . "px solid $swatch; border-right: " . $size . "px solid transparent; ";
					} else {
						echo "position: absolute; border-bottom: " . $size . "px solid $swatch; border-left: " . $size . "px solid transparent; ";
					}
				} ?> "></div>
		<?php } ?>
	</div>
</div>