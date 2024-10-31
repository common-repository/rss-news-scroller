<?php

/*
Plugin Name: RSS News Scroller by Vimal Ghorecha
Description: RSS News Schroller is a simple WordPress plugin to create a list of news with images from RSS feed and display it in marquee.
Author: 	 Vimal Ghorecha
Version: 	 2.0.0
Plugin URI:  http://www.vhghorecha.in/rss-news-scroller
Author URI:  http://www.vhghorecha.in/
Donate link: http://www.vhghorecha.in/
*/

/**
 *     RSS News Scroller by Vimal Ghorecha
 *     Copyright (C) 2013  Vimal Ghorecha
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function vhg_rss_show()
{
	global $wpdb;
	
	$htmldata = "";
	$cnt = 0;
	$vhg_news_items = get_option('vhg_news_items');
	$vhg_rss_url = get_option('vhg_rss_url');
	
	$htmlres = file_get_contents($vhg_rss_url);
	$htmlDom = simplexml_load_string($htmlres);
	
	foreach($htmlDom->channel->item as $item)
	{
		$htmldata .= "<div><strong>".$item->title."</strong><br/><br/>". $item->description ."</div>";
		$cnt++;
		if($cnt == $vhg_news_items)
			break;
	}
	
	$htmldata .= "";
	
	echo "<div id='vhg_banner'style='width: 250px; height: 250px;'>$htmldata</div>";
	add_action('wp_footer', 'vhg_start_imagecube');
}

function vhg_start_imagecube()
{
	global $wpdb;
	$vhg_speed = get_option('vhg_speed');
	$vhg_pause = get_option('vhg_pause');
	$vhg_direction = get_option('vhg_direction');
	$vhg_shading = filter_var(get_option('vhg_shading'), FILTER_VALIDATE_BOOLEAN);
	$vhg_opacity = get_option('vhg_opacity');
	$vhg_full3D = filter_var(get_option('vhg_full3D'), FILTER_VALIDATE_BOOLEAN);
    
	$cubeoption = "direction:'".$vhg_direction."', speed:'".$vhg_speed."',pause:'".$vhg_pause."',shading:".($vhg_shading?"true":"false").",opacity:'".$vhg_opacity."',full3D:".($vhg_full3D?"true":"false");
	
	echo "<script type='text/javascript'>jQuery('#vhg_banner').imagecube({".$cubeoption."});</script>";
}

/*add_filter('the_content','vhg_show_filter');

function vhg_show_filter($content)
{
	return 	preg_replace_callback('/\[RSS-MARQUEE=(.*?)\]/sim','vhg_show_filter_callback',$content);
}


function vhg_show_filter_callback($matches) 
{
		
}*/

function vhg_install() 
{
	add_option('vhg_title', "RSS News Scroller by Vimal Ghorecha");
	add_option('vhg_direction', "random");
	add_option('vhg_news_items', 5);
	add_option('vhg_speed', "5000");
	add_option('vhg_pause', "5000");
	add_option('vhg_shading', "1");
	add_option('vhg_opacity', 0.8);
	add_option('vhg_rss_title', "Vimal Ghorecha's Personal Utopia");
	add_option('vhg_rss_url', "http://feeds.feedburner.com/vhghorecha");
	add_option('vhg_full3D', "1");
}

function vhg_widget($args) 
{
	extract($args);
	if(get_option('vhg_title') <> "")
	{
		echo $before_widget;
		echo $before_title;
		echo get_option('vhg_title');
		echo $after_title;
	}
	vhg_rss_show();
	if(get_option('vhg_title') <> "")
	{
		echo $after_widget;
	}
}
	
function vhg_control() 
{
	echo "RSS News Scroller by Vimal Ghorecha";
	echo "<br>";
	echo "<a href='www.vhghorecha.in/rss-news-scroller-by-vimal-ghorecha' target='_blank'>Check official website</a>";
	echo "<br>";
}

function vhg_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('rss-news-scroller-by-vimal-ghorecha', 'RSS News Scroller by Vimal Ghorecha', 'vhg_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('rss-news-scroller-by-vimal-ghorecha', array('RSS News Scroller by Vimal Ghorecha', 'widgets'), 'vhg_control');
	} 
	
}

function vhg_load_scripts()
{
	wp_register_script('vhg_rss_script', plugins_url('jquery.imagecube.js', __FILE__));
	wp_enqueue_script('vhg_rss_script',array('jquery'));
	
	/*$vhg_script_settings = array( lastrssbridgeurl => plugins_url( 'bridge.php' , __FILE__ ));
	wp_localize_script( 'vhg_rss_script', 'vhg_script_settings', $vhg_script_settings );*/
}

function vhg_deactivation() 
{
	delete_option('vhg_title');
	delete_option('vhg_direction');
	delete_option('vhg_news_items');
	delete_option('vhg_speed');
	delete_option('vhg_pause');
	delete_option('vhg_shading');
	delete_option('vhg_opacity');
	delete_option('vhg_rss_title');
	delete_option('vhg_rss_url');
	delete_option('vhg_full3D');
}

function vhg_option() 
{
	global $wpdb;
	echo '<h2>RSS News Scroller by Vimal Ghorecha</h2>';
	$vhg_title = get_option('vhg_title');
	
	$vhg_speed = get_option('vhg_speed');
	$vhg_pause = get_option('vhg_pause');
	$vhg_direction = get_option('vhg_direction');
	$vhg_news_items = get_option('vhg_news_items');
	$vhg_shading = get_option('vhg_shading');
	$vhg_opacity = get_option('vhg_opacity');
	$vhg_rss_title = get_option('vhg_rss_title');
	$vhg_rss_url = get_option('vhg_rss_url');
	$vhg_full3D = get_option('vhg_full3D');
	
	if (@$_POST['vhg_submit']) 
	{
		$vhg_title = stripslashes($_POST['vhg_title']);
		
		$vhg_speed = stripslashes($_POST['vhg_speed']);
		$vhg_pause = stripslashes($_POST['vhg_pause']);
		$vhg_direction = stripslashes($_POST['vhg_direction']);
		$vhg_news_items = stripslashes($_POST['vhg_news_items']);
		$vhg_shading = stripslashes($_POST['vhg_shading']);
		$vhg_opacity = stripslashes($_POST['vhg_opacity']);
		$vhg_rss_title = stripslashes($_POST['vhg_rss_title']);
		$vhg_rss_url = stripslashes($_POST['vhg_rss_url']);
		$vhg_full3D = stripslashes($_POST['vhg_full3D']);
		
		update_option('vhg_title', $vhg_title );
		update_option('vhg_speed', $vhg_speed );
		update_option('vhg_pause', $vhg_pause );
		update_option('vhg_direction', $vhg_direction );
		update_option('vhg_news_items', $vhg_news_items );
		update_option('vhg_shading', $vhg_shading );
		update_option('vhg_opacity', $vhg_opacity );
		update_option('vhg_rss_title', $vhg_rss_title );
		update_option('vhg_rss_url', $vhg_rss_url );
		update_option('vhg_full3D', $vhg_full3D );
	}
	
	echo '<form name="vhg_form" method="post" action="">';
	
	echo '<p>Title :<br><input  style="width: 250px;" type="text" value="';
	echo $vhg_title . '" name="vhg_title" id="vhg_title" /></p>';
	
	echo '<p>Speed :<br><input  style="width: 100px;" type="text" value="';
	echo $vhg_speed . '" name="vhg_speed" id="vhg_speed" />(Default 5000)</p>';
	
	echo '<p>Pause :<br><input  style="width: 100px;" type="text" value="';
	echo $vhg_pause . '" name="vhg_pause" id="vhg_pause" />(Default 5000)</p>';
	
	echo '<p>Direction :<br><input  style="width: 100px;" type="text" value="';
	echo $vhg_direction . '" name="vhg_direction" id="vhg_direction" /> (Left/Right/Up/Down)</p>';

	echo '<p>News Items, articles or posts to display from feed :<br><input  style="width: 100px;" type="text" value="';
	echo $vhg_news_items . '" name="vhg_news_items" id="vhg_news_items" /> (Default 5: 1,2, any integer)</p>';
	
	echo '<p>Opacity :<br><input  style="width: 250px;" type="text" value="';
	echo $vhg_opacity . '" name="vhg_opacity" id="vhg_opacity" /></p>';
		
	echo '<p>RSS Feed Title:<br><input  style="width: 350px;" type="text" value="';
	echo $vhg_rss_title . '" name="vhg_rss_title" id="vhg_rss_title" /></p>';
	
	echo '<p>RSS Feed URL: <br><input  style="width: 350px;" type="text" value="';
	echo $vhg_rss_url . '" name="vhg_rss_url" id="vhg_rss_url" />';
	
	?>
	<p>Shading: <input type="radio" id="vhg_shading" name="vhg_shading"  value="1" <?php
	if ($vhg_shading == 1) echo 'checked' ;?> /> Yes
	<input type="radio" id="vhg_shading" name="vhg_shading"  value="0" <?php
	if ($vhg_shading == 0) echo 'checked' ; ?> /> No
	<p>Full3D: <input type="radio" id="vhg_full3D" name="vhg_full3D"  value="1" <?php
	if ($vhg_full3D == 1) echo 'checked' ;?> /> Yes
	<input type="radio" id="vhg_full3D" name="vhg_full3D"  value="0" <?php
	if ($vhg_full3D == 0) echo 'checked' ; ?> /> No
	

<?php
	echo '<br>';
	echo '<br>';

	echo '<input name="vhg_submit" id="vhg_submit" lang="publish" class="button-primary" value="Update" type="Submit" />';
	echo '</form>';
?>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="item_name" value="RSS News Scroller Contribution">
		<input type="hidden" name="business" value="vimal.ghorecha@gmail.com">
		<input type="image" src="http://www.vhghorecha.in/wp-content/uploads/2013/12/contribute-button.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
<?php
}

function vhg_add_to_menu() 
{
	add_options_page('RSS News Scroller by Vimal Ghorecha', 'RSS News Scroller by Vimal Ghorecha', 'manage_options', __FILE__, 'vhg_option' );
}

add_action('admin_menu', 'vhg_add_to_menu');
add_action("plugins_loaded", "vhg_widget_init");
register_activation_hook(__FILE__, 'vhg_install');
register_deactivation_hook(__FILE__, 'vhg_deactivation');
add_action('init', 'vhg_widget_init');
add_action('wp_enqueue_scripts','vhg_load_scripts');
?>