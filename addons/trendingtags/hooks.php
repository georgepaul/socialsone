<?php
/**
 * Trending Tags add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2014 interactive32.com
 */

$controller = $request->getControllerName();
$action = $request->getActionName();

// only show on certain pages
$show = false;
if ($controller == 'index' && $action = 'index') $show = true;
if ($controller == 'search') $show = true;
if (!$show) return;

$this->attach('hook_view_sidebar', 20, function($view) { 

	// Get cache from registry
	$cache = Zend_Registry::get('cache');
	
	
	if (($out = $cache->load('addon_trendingtags')) === false) {
		
		// cache missed, we need to build this again
		
		$sql = "
		SELECT content 
		FROM posts
		WHERE content like '%#%'
		ORDER BY created_on DESC 
		LIMIT 300
		";
		
		$Model = new Application_Model_Addons();
		$rows = $Model->getAdapter()->fetchCol($sql);
		
		if (empty($rows)) return;
		
		// merge all content to a single string
		$full_content = '';
		$raw_tags = null;
		$tags = array();
		
		foreach ($rows as $key => $val) {
			// remove dupes from a single post to avoid abuse
			$val = implode(' ',array_unique(explode(' ', $val)));
			$val = implode("\n",array_unique(explode("\n", $val)));
			
			// concate to one big string
			$full_content .= $val.' ';
		}
		
		// match tags
		//preg_match_all("/(^|[\t\r\n\s])#(\w+)/u", $full_content, $raw_tags); // not uft8 safe
		preg_match_all("/#([\p{L}\p{N}\-_]+)(?=\s|\Z)/u", $full_content, $raw_tags);

		if (!$raw_tags || empty($raw_tags[1])) return;
		
		// take 2nd match
		$raw_tags = $raw_tags[1];
		
		// transform and count
		foreach ($raw_tags as $key => $value) {
			$tags[$value] = isset($tags[$value]) ? ++$tags[$value] : 1;
		}
		
		// sort by count 
		arsort($tags);
		// cut to top 10 tags
		$tags = array_slice($tags, 0, 5);
		
		
		// display sidebar box content
		
		$base_url = Application_Plugin_Common::getFullBaseUrl();
		$search_url = $base_url . '/search/posts/?term=%23';
		

		foreach ($tags as $key => $value) {
			$out .= '<li><a href="'.$search_url.$key.'">#'.$key.'</a></li>';
		}
		
		

		// save to cache
		$cache->save($out);
	}

	echo '<div class="well">
				<div class="sb-title">'.$view->translate('Trending').'</div><ul class="nav nav-pills nav-stacked">';
	
	// write out
	echo $out;
	
	echo '</ul></div>';

});


