<?php
/*
Plugin Name: Spicy Blogroll
Version: 0.1
Description: Spices up your regular Blogroll by showing recent post excerpts for each of your links in the Blogroll widget. Once you've set up the plugin write a post about it and let everyone on your blogroll know about it. Change options on the settings page. (Code inspired by the Live Blogroll plugin by Vladimir Prelovac).
Author: Michael Pedzotti
Author URI: http://www.michaelpedzotti.com
Plugin URI: http://www.michaelpedzotti.com/wordpress-plugins/spicy-blogroll
*/
/*
Copyright 2010  Michael Pedzotti  (email : michael@nine95.com)

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program; if not, access it at this web address:
http://www.gnu.org/licenses/ or write to the Free Software Foundation, Inc.,
51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Version check */
global $wp_version;
$exit_msg = 'Spicy Blogroll requires WordPress 2.6 or newer. Please update your version of WordPress
 <a href="http://codex.wordpress.org/Upgrading_WordPress" target="_blank">by clicking this link</a>
 or upgrade automatically from your blog dashboard.';
if (version_compare($wp_version,"2.6","<")){
  exit ($exit_msg);
}
// additional links on the plugin page added in WP2.8
if (version_compare($wp_version, "2.8", ">")) {
  add_filter('plugin_row_meta','spicy_blogroll_plugin_meta',10,2);
  add_filter('plugin_action_links','spicy_blogroll_plugin_action_links',10,2);
}
// set up variables and hooks
define('SBR_PLUGIN_NAME','spicy-blogroll');
define('PLUGIN_NAME','Spicy Blogroll');
define('SHORT_NAME','sbr');
define('SBR_URL',WP_PLUGIN_URL.'/'.SBR_PLUGIN_NAME);
define('SBR_PATH',WP_PLUGIN_DIR.'/'.SBR_PLUGIN_NAME);
define('SBR_ADMIN_PAGE','sbr_admin');
define('SBR_ADMIN_URL',admin_url().'options-general.php?page='.SBR_ADMIN_PAGE);
$spicy_blogroll_vers = 0.1;
update_option(SHORT_NAME."_version",$spicy_blogroll_vers);
register_activation_hook(__FILE__,'sbr_activate_action');
add_action('wp_print_scripts', 'SpicyBlogRoll_ScriptsAction');
add_action('wp_head', 'SpicyBlogRoll_HeadAction');
add_action('admin_menu', 'sbr_add_admin');
add_action('admin_init', 'sbr_admin_init');
add_action('admin_head', 'sbr_HeadAction');
// action and filter rountines
function sbr_activate_action(){
  global $options;
  foreach ($options as $value){
	if (array_key_exists('std',$value)){
	  update_option($value['id'], $value['std']);
    }
  }
}
function SpicyBlogRoll_HeadAction(){
  if (!is_admin()){
    echo '<link rel="stylesheet" href="'.SBR_URL.
      '/spicy-blogroll.css" type="text/css" />';
  }
}
function sbr_HeadAction(){
  echo '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />';  
}
function spicy_blogroll_plugin_meta($links, $file) {
  $plugin = plugin_basename(__FILE__);
  if ($file == $plugin) {
    $links[] = '<a href="http://www.nine95.com/support" target="_blank">Help Desk</a>';
    $links[] = '<a href="http://www.michaelpedzotti.com/" target="_blank">Blog</a>';
  }
  return $links;
}
function spicy_blogroll_plugin_action_links($links, $file) {
  $plugin = plugin_basename(__FILE__);
  if ($file == $plugin) {
	$settings_link = '<a href="'.SBR_ADMIN_URL.'">Settings</a>';
	array_unshift($links, $settings_link );
	array_pop($links);
  }
  return $links;
}
function sbr_admin_init(){
  if (is_admin()){
    if (get_option(SHORT_NAME.'_IE8_fix') != 1){
      wp_enqueue_script("sbr_script", SBR_URL."/spicy-blogroll-admin.js", false, "1.0");
      wp_enqueue_style("sbr_style", SBR_URL."/spicy-blogroll-admin.css", false, "1.0", "all");
	}
  }
}
// add action to load js for blogroll pop-up
function SpicyBlogRoll_ScriptsAction(){
  if (!is_admin()){
    $nonce = wp_create_nonce('spicy-blogroll');
    wp_enqueue_script('jquery');
	wp_enqueue_script('spicy_blogroll_script',SBR_URL.'/spicy-blogroll.js',array('jquery'),$spicy_blogroll_vers);
	$var1 = SBR_URL;
	$var2 = scramble(ABSPATH,2);
	$var3 = scramble(WPINC,3);
	$var4 = scramble('wp-config.php',4);
	$var5 = scramble('/feed.php',5);
	$var6 = get_option(SHORT_NAME.'_x_offset',-260);
	$var7 = get_option(SHORT_NAME.'_y_offset',-200);
	$var8 = get_option(SHORT_NAME.'_width',260);
	$var9 = get_option(SHORT_NAME.'_min_height',300);
	$var10 = get_option(SHORT_NAME.'_opacity',85);
	$var11 = scramble($nonce,6);
	$var12 = get_option(SHORT_NAME.'_timeout',3000);
	$var13 = get_option(SHORT_NAME.'_rss_error','RSS feed timed out');
	$var14 = get_option(SHORT_NAME.'_waiting_rss','Waiting for RSS ...');
    wp_localize_script('spicy_blogroll_script', 'SpicyBlogrollSettings',
	 array('var1'=>$var1,'var2'=>$var2,'var3'=>$var3,'var4'=>$var4,'var5'=>$var5,'var6'=>$var6,
	 'var7'=>$var7,'var8'=>$var8,'var9'=>$var9,'var10'=>$var10,'var11'=>$var11,'var12'=>$var12,
	 'var13'=>$var13,'var14'=>$var14));
  }
}
// if we're on the options settings page then set up some stuff
if (is_admin()){
  global $options;
  $options = array (
  array("name" => PLUGIN_NAME." Options",
		"type" => "title"),
  array("name" => "General Options",
		"type" => "section",
		"id" => SHORT_NAME."_show_gen_options",
		"std" => false),
  array("type" => "open"),
  array("name" => "Number of Posts",
		"desc" => "Number of posts to show in pop-up. Default is 4. You can select up to 10 but the RSS feed may return less as the other blogger controls that.",
		"id" => SHORT_NAME."_num_posts",
		"type" => "select",
		"options" => array("2", "3", "4", "5", "6", "7", "8", "9", "10"),
		"std" => "4"),
  array("name" => "Size of Excerpt",
		"desc" => "Length (number of words) of the excerpt for each recent post. Default is 20.",
		"id" => SHORT_NAME."_num_words",
		"type" => "text",
		"std" => "20"),
  array("name" => "RSS Wait Time",
		"desc" => "Time (in milliseconds) to wait for return of RSS information. If nothing returned after this time, a friendly message is displayed. Recommended range is more than 1000 and less than 5000.",
		"id" => SHORT_NAME."_timeout",
		"type" => "text",
		"std" => "3000"),
  array( "name" => "Developers Link",
		"desc" => "Tick the checkbox to add a small, unobstrusive link at the bottom of the popup back to my developer website.",
		"id" => SHORT_NAME."_dev_link",
		"type" => "checkbox",
		"std" => false),
  array("type" => "close"),
  array("name" => "Position & Size",
		"type" => "section",
		"id" => SHORT_NAME."_show_position",
		"std" => false),
  array("type" => "open"),
  array("name" => "X-Offset",
		"desc" => "Positive values move the pop-up to the right, negative to the left. Default -260px. This suits a blogroll on the right of your blog. See notes below.",
		"id" => SHORT_NAME."_x_offset",
		"type" => "text",
		"std" => "-260"),
  array("name" => "Y-Offset",
		"desc" => "Positive values move the pop-up down the page, negative up the page. Default -200px. This suits a blogroll near the bottom of your page. See notes below.",
		"id" => SHORT_NAME."_y_offset",
		"type" => "text",
		"std" => "-200"),
  array("name" => "Width",
		"desc" => "Measured in pixels. Default 260px. If you change this value and your blogroll is on the right side, you may need to tweak X-Offset.",
		"id" => SHORT_NAME."_width",
		"type" => "text",
		"std" => "260"),
  array("name" => "Minimum Height",
		"desc" => "Measured in pixels. Default 300px. This is to give the pop-up a minimum size for those occasions when the feed from one of your blogroll links cannot be located.",
		"id" => SHORT_NAME."_min_height",
		"type" => "text",
		"std" => "300"),
  array("type" => "close"),
  array("name" => "Messages",
		"type" => "section",
		"id" => SHORT_NAME."_show_messages",
		"std" => false),
  array("type" => "open"),
  array( "name" => "Intro Text in Pop-up",
		"desc" => "These are the first few words introducing the post excerpts. No need for a space at the end, the plugin adds one for you.",
		"id" => SHORT_NAME."_intro_rss",
		"type" => "textarea",
		"length" => "100",
		"std" => "Recent posts from "),
  array( "name" => "Waiting for RSS",
		"desc" => "This brief message appears while the RSS feed is being fetched. Keep it short as it only appears for a second or two.",
		"id" => SHORT_NAME."_waiting_rss",
		"type" => "textarea",
		"length" => "75",
		"std" => "Waiting for RSS feed to load ..."),
  array( "name" => "No RSS Feed Found",
		"desc" => "This message is shown if no RSS feed can be found at the given URL.",
		"id" => SHORT_NAME."_no_rss",
		"type" => "textarea",
		"length" => "150",
		"std" => "Sorry, it seems that no posts are available at the moment."),
  array( "name" => "RSS Feed Time-Out",
		"desc" => "This message is shown if the RSS feed does not respond within the RSS Wait Time (General Options).",
		"id" => SHORT_NAME."_rss_error",
		"type" => "textarea",
		"length" => "150",
		"std" => "The RSS feed timed out. Please try again in a few moments. Move your mouse away then hover over the link again."),
  array("type" => "close"),
  array("name" => "Pop-up Styling",
		"type" => "section",
		"id" => SHORT_NAME."_show_popup_styling",
		"std" => false),
  array("type" => "open"),
  array("name" => "Link Click Option",
		"desc" => "Two choices. When the users clicks on a link in the pop-up do you want it to open in the same (parent) window/tab or a new window/tab?",
		"id" => SHORT_NAME."_linkclick",
		"type" => "select",
		"options" => array("Parent window", "New window")),
  array("name" => "Pop-up Opacity",
		"desc" => "This is to give your visitor some sense of what has just been covered up by the pop-up. 0 = popup is invisible (seems pointless but I like giving people full control). 100 = solid (can't see anything through the popup). Default 85. ",
		"id" => SHORT_NAME."_opacity",
		"type" => "text",
		"std" => "85"),
  array( "name" => "Custom CSS",
		"desc" => "<strong>[For a future release]</strong> Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets.",
		"id" => SHORT_NAME."_custom_css",
		"type" => "textarea",
		"length" => "250",
		"std" => "Future feature ..."),
  array( "name" => "IE8 Temp Fix",
		"desc" => "Tick the checkbox to remove the CSS styling from this options page. It won't look all that pretty and you'll lose access to the nice sliding menu sections, but you will be able to access all options settings. <strong>Also see notes below.</strong>",
		"id" => SHORT_NAME."_IE8_fix",
		"type" => "checkbox",
		"std" => false),
  array("type" => "close")
  );
}
// main options menu page (in the dashboard)
function sbr_admin(){
  global $options;
  $i=0;
  // temp IE8 fix
  if (get_option(SHORT_NAME.'_IE8_fix',false)){
    update_option(SHORT_NAME."_show_gen_options",1);
    update_option(SHORT_NAME."_show_position",1);
    update_option(SHORT_NAME."_show_messages",1);	
    update_option(SHORT_NAME."_show_popup_styling",1);
  }
  echo '<div class="wrap sbr_wrap">
		<div class="icon32" id="icon-options-general"><br></div>
        <h2>'.PLUGIN_NAME.' - v'.get_option(SHORT_NAME."_version").' [Settings Page]</h2><div class="sbr_opts"><form method="post">';
  if (isset($_REQUEST['saved'])){
    echo '<div id="message" class="sb_updated"><p><strong>'.PLUGIN_NAME.' settings saved.</strong></p></div>';
  }
  if (isset($_REQUEST['reset'])){
    foreach ($options as $value){
	  if (array_key_exists('std',$value)){
	     update_option($value['id'], $value['std']);
      }
	}
    echo '<div id="message" class="sb_updated"><p><strong>'.PLUGIN_NAME.' settings reset.</strong></p></div>';
  }
  $textarea_ctr = 0;
  foreach ($options as $value) {
    switch ( $value['type'] ) {
      case "open":
        break;
      case "close":
        echo '</div></div><br />';
        break;
      case "title":
        echo '<p>To customise the '.PLUGIN_NAME.' plugin, adjust the options below. Click on the blue plus sign to expand each section. Click again on the minus sign to shrink. You can also visit the <a href="http://www.michaelpedzotti.com/" title="Software Tools for Bloggers">author page</a>, <a href="http://nine95.com/support" title="helpdesk">submit a support request</a>, follow <a href="http://twitter.com/michaelpedzotti/">Michael Pedzotti on Twitter</a> or visit the plugin page in the WordPress Plugin Directory (link to be added).</p>';
        break;
      case 'text':?>
		<div class="sbr_input sbr_text">
		<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
		<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>
		" value="<?php
		if (!get_option($value['id']) && $value['std']){
		  $chosen=$value['std'];
		  update_option($value['id'],$chosen);
		}else{
		  $chosen=get_option($value['id']);
		}
		if (get_option( $value['id'] ) != "") {echo stripslashes(get_option( $value['id'])); }
		else { echo $value['std']; } ?>" />
		<small><?php echo $value['desc']; ?></small><div class="clearfix"></div></div><?php
        break;
      case 'textarea':
	    $textarea_ctr+=1;
		$java_action="textCounter(this.form.".$value['id'].",this.form.remLen".$textarea_ctr.",".$value['length'].");";?>
		<div class="sbr_input sbr_textarea">
		<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="16" rows="8"
		onKeyDown="<?php echo $java_action; ?>"	onKeyUp="<?php echo $java_action; ?>" onFocus="<?php echo $java_action; ?>"><?php if (get_option( $value['id'] ) != "") {echo stripslashes(get_option($value['id']));} else {echo $value['std'];} ?></textarea>
		<small><?php echo $value['desc']; ?><br />
		<input readonly type="text" name="remLen<?php echo $textarea_ctr; ?>" size = "4" value="<?php echo $value['length'];?>">
		 characters left.</small><div class="clearfix"></div></div><?php
        break;
      case 'select':?>
        <div class="sbr_input sbr_select">
        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
		<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php
		if(!get_option($value['id']) && $value['std']){
		  $chosen=$value['std'];
		  update_option($value['id'],$chosen);
		}else{
		  $chosen=get_option($value['id']);
		}
        foreach ($value['options'] as $option) { ?><option <?php
		if ($chosen == $option) { echo 'selected="selected" '; } ?>>
		<?php echo $option; ?></option><?php } ?></select>
		<small><?php echo $value['desc']; ?></small><div class="clearfix"></div></div><?php
		break;
	  case "checkbox":
  		$chosen=false;
		if(get_option($value['id'])){
		  $chosen=get_option($value['id']);
		}else if($value['std']){
          $chosen=$value['std'];
		  update_option($value['id'],$chosen);
		}?>
		<div class="sbr_input sbr_checkbox">
		<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
		<?php if($chosen){$checked = "checked=\"checked\"";}else{$checked = "";} ?>
		<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="1" 
		<?php echo $checked; ?> />
		<small><?php echo $value['desc']; ?></small><div class="clearfix"></div></div><?php
		break;
	  case "section":
		$i++;
		$chosen=false;
		if(get_option($value['id'])){
		  $chosen=get_option($value['id']);
		}else if($value['std']){
          $chosen=$value['std'];
		  update_option($value['id'],$chosen);
		}
		?>
		<div class="sbr_section">
		<div class="sbr_title"><h3><img src="<?php echo SBR_URL?>/images/trans.png"
		<?php if($chosen){echo ' class="active" ';}else{echo ' class="inactive" ';}?> 
		alt="" "><?php echo $value['name']; ?></h3><span class="submit"><input class="button-primary" name="save<?php echo $i; ?>
		" type="submit" value="Save changes" /></span><span class="checkbox"><?php
		if($chosen){$checked = "checked=\"checked\""; }else{$checked = "";}?>
		<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" 
		<?php echo $checked; ?> /> Keep expanded</span>
		<div class="clearfix"></div></div><div class="sbr_options"<?php
		if($chosen){echo ' style="display: block;"';}else{echo ' style="display: none;"';} ?> > <?php
		break;
	}
  }
  ?>
    <input type="hidden" name="action" value="save" /> 
  </form>
  <form method="post">
    <p class="submit"><span class="warning">Warning, pressing the following button will restore defaults. Any changes you have made will be lost.<br /></span>
    <input class="button-secondary" name="reset" type="submit" value="Use Defaults" />
    <input type="hidden" name="action" value="reset" />
    </p>
  </form>
  <div class="sbr_notes">
  <h2><img src="<?php echo SBR_URL; ?>/images/info_button_32.png" height="32" width="32" alt="more info " /> Notes:</h2>
  <ol><li>You can change any number of options at once. Pressing any of the <em>Save changes</em> buttons will save all changes.</li><li>If your blogroll is on the left side of your blog try an <strong>X-Offset</strong> of between 0 and 10. If it is near the top of your page then try a <strong>Y-Offset</strong> value closer to 0 or slightly positive.</li><li>X and Y offsets are measured from the <strong>top-left</strong> corner of the pop-up window.</li><li>Deactivating and reactivating the plugin will restore default settings.</li></ol>
  <h2><img src="<?php echo SBR_URL; ?>/images/add_32.png" height="32" width="32" alt="like this " /> Like this plugin?</h2><p><strong>No donation required</strong>, would you please add <a href="http://www.michaelpedzotti.com/" title="Software Tools for Bloggers"><em>http://www.michaelpedzotti.com/</em></a> to your blogroll. Tweet about it @michaelpedzotti and I will visit your blog. Add this to your blogroll (links) widget.</p><ul><li><strong>Name:</strong> Software Tools for Bloggers</li><li><strong>Web address:</strong> http://www.michaelpedzotti.com/</li><li><strong>Description:</strong> Useful plugins to add to your blog</li></ul><p>Visit <a href="http://www.michaelpedzotti.com/wordpress-plugins/" title="Other WP plugins by Michael Pedzotti"><em>http://www.michaelpedzotti.com/wordpress-plugins/</em></a> to see the other plugins I've developed or I'm working on.</p>
  <h2><img src="<?php echo SBR_URL; ?>/images/page_text_warning_32.png" height="32" width="32" alt="warning " /> IE8 Users</h2><p><strong>If you are using IE8</strong> the CSS for this options window is buggy. Take a moment to view it in Firefox to see what it is really like. Something in the spicy-blogroll-admin.css file is breaking it with IE8. If you have worked it out, please submit helpful suggestions via the <a href="http://nine95.com/support" title="submit a suggestion to fix IE8 formatting">support desk</a>. Meanwhile, expand the bottom options panel, tick the checkbox beside 'IE8 Temp Fix' and press any of the 'Save changes' buttons. I am sure that it'll get fixed real soon.</p>
  
  <p>Icons:<a href="http://www.woothemes.com/2009/09/woofunction/"> WooFunction</a></p></div></div><?php
}
// it does what it says it does to keep some info away from prying eyes - unscrupulous visitors that is :-)
function scramble($text1,$rng){
  $len=strlen($text1);
  $rn=$rng%2;
  $count=7;
  $seed=($rn%=2)+1;
  $text2=chr($seed+64+$rng).chr($rng+70);
  for($i=0; $i<=$len-1; $i++) {
    $seed*=-1;
	$count+=1;
	$ch=ord(substr($text1,$i,1))+$seed;
	if($ch==92){$ch.=42;}
    $text2.=chr($ch);
	if($count%5==$rn){$text2.=chr(mt_rand(97,123));}
  }
  return $text2;
}
function sbr_add_admin() {
  global $options;
  if ($_GET['page'] == SBR_ADMIN_PAGE){
	if ('save' == $_REQUEST['action']){
      foreach ($options as $value){
	    if(isset($_REQUEST[$value['id']])){
		  update_option($value['id'], $_REQUEST[$value['id']]);
		}else{
		  delete_option($value['id']);
		}
	  }
	  header("Location: ".SBR_ADMIN_URL."&saved=true");
      die;
    }else if('reset' == $_REQUEST['action']){
	  foreach ($options as $value){
	    delete_option( $value['id'] );
      }
	  header("Location: ".SBR_ADMIN_URL."&reset=true");
      die;
    }
  }
  add_options_page('options-general.php','Spicy Blogroll','administrator',SBR_ADMIN_PAGE,'sbr_admin');
}
?>