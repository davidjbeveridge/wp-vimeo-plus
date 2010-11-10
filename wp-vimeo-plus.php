<?php
/*
Plugin Name: Vimeo Plus
Plugin URI: #
Description: Adds a shortag for Vimeo Plus videos (Plus accounts only). Requires the NerdyIsBack Plugin Framework.
Version: 0.1
Author: David Beveridge
Author URI: http://www.nerdyisback.com
License: MIT
*/
/*	Copyright (c) 2010 David Beveridge, Studio DBC

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

// Create Options pages:


$fields = array();
$fields[] = array('type'=>'heading','name'=>'Player Dimensions:');

$fields[] = array('id' => 'width','name'=>'Width','desc' => 'Number in px. Defaults to 640.','type'=>'text');
$fields[] = array('id' => 'height','name'=>'Height','desc' => 'Number in px. Defaults to 480','type'=>'text');

$fields[] = array('type'=>'heading','name'=>'Customize Player:');

$fields[] = array('id' => 'disableByline','name'=>'Disable Byline','desc' => 'Whether or not to display video byline', 'type'=>'checkbox', 'value' => 1);
$fields[] = array('id' => 'disablePortrait','name'=>'Disable Portrait','desc' => 'Whether or not to display user\'s portrait', 'type'=>'checkbox', 'value' => 1);
$fields[] = array('id' => 'disableTitle','name'=>'Disable Title','desc' => 'Whether or not to display video title', 'type'=>'checkbox', 'value' => 1);
$fields[] = array('id' => 'autoPlay','name'=>'Enable Autoplay','desc' => 'Whether or not to begin playing on video load', 'type'=>'checkbox', 'value' => 1);
$fields[] = array('id' => 'playerColor','name'=>'Player Color','desc' => 'Hexidecimal color code.  Defaults to blue.', 'type'=>'text');


$vimeoPlusOpts = new CustomOptionspage('options','vimeoplusst','Vimeo Plus','manage_options',$fields);

function vimeo_plus_shorttag($atts,$content=null,$code=null)	{
	global $vimeoPlusOpts;
	if(is_null($content))	{
		return "$code: No video url specified.";
	}
	else	{
		$id = intval(array_pop(explode('/',$content)));
	}
	$w = $vimeoPlusOpts->getOption('width');
	$h = $vimeoPlusOpts->getOption('height');
	$defaults = array(
		'width' => (!empty($w) ? $w : 640),
		'height' => (!empty($h) ? $h : 480)
	);
	extract(shortcode_atts($defaults,$atts));
	$args = array();

	if(($dbyline = $vimeoPlusOpts->getOption('disableByline')) && !empty($dbyline))	{
		$args['byline'] = 0;
	}
	else	{
		$args['byline'] = 1;
	}
	
	if(($dportrait = $vimeoPlusOpts->getOption('disablePortrait')) && !empty($dportrait))	{
		$args['portrait'] = 0;
	}
	else	{
		$args['portrait'] = 1;
	}
	
	if(($dtitle = $vimeoPlusOpts->getOption('disableTitle')) && !empty($dtitle))	{
		$args['title'] = 0;
	}
	else	{
		$args['title'] = 1;
	}
	
	if(($autoplay = $vimeoPlusOpts->getOption('autoPlay')) && !empty($autoplay))	{
		$args['autoplay'] = 1;
	}
	else	{
		$args['autoplay'] = 0;
	}
	
	if(($color = $vimeoPlusOpts->getOption('playerColor')) && $color !== '')	{
		$args['color'] = str_replace('#','',$color);
	}

	return "<iframe src=\"http://player.vimeo.com/video/$id?".htmlspecialchars(http_build_query($args))."\" width=\"$width\" height=\"$height\" frameborder=\"0\"></iframe>";
}

add_shortcode('vimeoplus','vimeo_plus_shorttag');