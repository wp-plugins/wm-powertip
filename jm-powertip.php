<?php
/**
 * Plugin Name: WM PowerTip
 * Plugin URI: http://chalktalk.wisemago.com/wm-power-tip
 * Description: For adding jquery power tips to your content. Use [wmpowertip][/wmpowertip]. The attributes available are 'text'(for the text to be displayed),'placement' (position of the tip options: n, e, s, w, nw, ne, sw, or se), 'followmouse'(tooltip will follow the users mouse cursor options: true/false) ,'smartplacement' (will try to keep the tip on screen options:true/false),'fadeintime'(Tooltip fade-in time in milliseconds.default : 200) ,'fadeouttime'(Tooltip fade-out time in milliseconds.default : 200) ,	'closedelay' (in milliseconds. default:100). For further details see <a href='http://chalktalk.wisemago.com/wm-power-tip'>here</a>
 * Author: WiseMago
 * Author URI: wisemago.com
 * Version: 1.1.1
 */

class WMPowerTip {
	
    //initialize the plugin
	function __construct(){
		
		add_filter('the_posts', array(&$this,'conditinalStyleJsWMPT')); // the_posts gets triggered before wp_head
		add_shortcode('wmpowertip', array($this, 'shortcodeWMPT'));  
	}

	function shortcodeWMPT( $atts, $content = null ){
		extract(shortcode_atts(array(
		'text' => '',
		'placement' => 'ne',
		'followmouse' => '',
		'smartplacement' => '',
		'fadeintime' => '',
		'fadeouttime' => '',
		'closedelay' => '',
		), $atts));
				
		if($placement=='')
			$args[] = 'placement: "ne"';
		else
			$args[] = 'placement: "'.$placement.'"';
		
	
		if($followmouse!='') $args[] ='followMouse: "'.$followmouse.'"';
		if($smartplacement!='') $args[] ='smartPlacement: "'.$smartplacement.'"';
		if($fadeintime!='') $args[] = 'fadeInTime: "'.$fadeintime.'"';
		if($fadeouttime!='') $args[] = 'fadeOutTime: "'.$fadeouttime.'"';
		if($closedelay!='') $args[] ='closeDelay: "'.$closedelay.'"';
		
		$args = implode(",",$args);
		$id= rand(0,time());
		
		$str .= "<span data-powertip='$text' id='$id' class='powertip_wrap'>".$content."</span>";
		
		$str .= "<script type='text/javascript'>
		jQuery(function() {
			// placement examples
			jQuery('#".$id."').powerTip({".$args."});
			
			});
	</script>";
		
		return $str;
	}
	
	function conditinalStyleJsWMPT($posts){
		if (empty($posts)) return $posts;
	 
		$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
		foreach ($posts as $post) {
			if (stripos($post->post_content, '[wmpowertip') !== false) {
				$shortcode_found = true; // bingo!
				break;
			}
		}
	 
		if ($shortcode_found && !is_admin()) {
			// enqueue here
			wp_enqueue_style('wmptmaincss',plugins_url('css/jquery.powertip.css',__FILE__));
			wp_enqueue_script("jquery");
			wp_enqueue_script('wmptmainjs',plugins_url('js/jquery.powertip-1.1.0.min.js',__FILE__)); 
		}
	 
		return $posts;
	}
}
new WMPowerTip();
             ?>