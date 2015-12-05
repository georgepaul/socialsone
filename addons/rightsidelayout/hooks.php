<?php
/**
 * Right Side Layout add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2013 interactive32.com
 */

$this->attach('hook_app_init', 10, function ($app)
{
	
	$view = $app['view'];
	
	$view->addScriptPath(realpath(dirname(__FILE__) . '/rightlayout'));
});

