<?php
	$pricing = SimpleShop::price_for_product($_REQUEST);
	die(json_encode($pricing));
?>