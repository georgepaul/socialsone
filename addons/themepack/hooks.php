<?php
/**
 * Theme pack add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2013 interactive32.com
 */

// attach directly to settings form
$this->attach('form_SettingsStyle', 10, function(&$form) {

	$themes_array = array();
	foreach (glob(dirname(__FILE__) . '/css/*.css') as $file) {
		$theme_file = pathinfo($file);
		$themes_array['/addons/'.basename(__DIR__).'/css/' . $theme_file['basename']] = ucfirst($theme_file['filename']);
	}

	$current_values = $form->getElement('css_theme')->getMultiOptions();
	
	$values = array_merge($current_values, $themes_array);
	
	// update form
	$form->getElement('css_theme')->setMultiOptions($values);

});