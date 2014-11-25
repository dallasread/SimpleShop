<?php $swatches = explode("|", $colours); ?>
<div class="simpleshop_swatch simpleshop_swatch_count_<?php echo count($swatches); ?> <?php if ($selected == $colours) { echo "selected"; } ?>" data-colour="<?php echo $colours; ?>" data-id="<?php echo $id; ?>">
	<div class="outer_swatch">
		<?php $n = 0; foreach ($swatches as $swatch) { ?>
			<div class="inner_swatch" style="<?php
				if (count($swatches) == 1) {
					echo "background: $swatch; ";
				} else {
					if ($n == 0) {
						$n += 1;
						echo "border-top: 32px solid $swatch; border-right: 32px solid transparent; ";
					} else {
						echo "border-bottom: 32px solid $swatch; border-left: 32px solid transparent; ";
					}
				} ?> "></div>
		<?php } ?>
	</div>
</div>