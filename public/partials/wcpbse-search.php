<?php

defined( 'ABSPATH' ) || exit;
?>
<div class="wcpbsc-search">
	<div class="wcpbsc-search-bar">
		<div class="wcpbsc-search-input-box">
			<input type="search" class="wcpbsc-search-input" placeholder="<?php echo esc_attr__( 'Search products…', 'woocommerce-better-search' ); ?>" autocomplete="off">
			<span class="wcpbsc-search-cross">&#10005;</span>
		</div>
		<span class="wcpbsc-search-loader"></span>
		<span class="wcpbsc-search-magnifier">&#128269;</span>
	</div>
	<div class="wcpbsc-search-result"></div>
</div>
