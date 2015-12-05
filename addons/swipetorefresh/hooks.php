<?php
/**
 * Swipe to refresh
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2015 interactive32.com
 */

$this->attach('view_body', 10, function($view) {


	$base_url = $view->baseUrl();
	$base_addon_url = $view->baseUrl().'/addons/'.basename(__DIR__);

	echo '<script type="text/javascript" src="'. $base_addon_url. '/js/xpull.js"></script>';

	
	echo '
	<script type="text/javascript">

		$(document).ready(function(){
			$(".container.main .main-content").xpull({
				"callback":function(){$(".container.main").fadeTo("slow", 0.33);location.reload();}
			});
			$(".container.main .cover").xpull({
				"callback":function(){$(".container.main").fadeTo("slow", 0.33);location.reload();}
			});
		});

	</script>
	';
	
});
