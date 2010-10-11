<?php
/*
Spicy Blogroll Ajax Script - part of the Spicy Blogroll plugin
*/

// fetch information from GET method
$link_url = $_GET['link_url'];
$link_text = $_GET['link_text'];
$var2 = unscramble($_GET['var2']);
$var3 = unscramble($_GET['var3']);
$var4 = unscramble($_GET['var4']);
$var5 = unscramble($_GET['var5']);
$nonce = unscramble($_GET['var11']);
require_once($var2.$var4);
require_once($var2.$var3.$var5);
// return the result
SpicyBlogroll_HandleAjax($link_url, $link_text);
// truncate post excerpt to set length or less
function SpicyBlogroll_GetExerpt($text){
  $length = get_option(SHORT_NAME.'_num_words',20);
  $text = strip_tags($text);
  $words = explode (' ', $text, $length + 1);
  if (count($words) > length){
    array_pop($words);
	array_push($words, '[...]');
	$text = implode(' ', $words);
  }
  return $text;
}
// as the name suggests
function unscramble($text1){
  $len=strlen($text1);
  $rng=ord(substr($text1,1,1))-70;
  $rn=$rng%2;
  $seed=ord(substr($text1,0,1))-$rng-64;
  $count=7;
  $text2='';
  for($i=2; $i<=$len-1; $i++) {
    $seed*=-1;
	$count+=1;
	$ch=ord(substr($text1,$i,1))-$seed;
	if ($ch==42){$ch.=92;}
    $text2.=chr($ch);
	if($count%5==$rn){$i++;}
  }
  return $text2;
}
// parse the RSS feed and return Ajax request
function SpicyBlogroll_HandleAjax($link_url, $link_text){
  global $nonce;
  $dev_link = "http://www.michaelpedzotti.com/wordpress-plugins/spicy-blogroll";
  if (!wp_verify_nonce($nonce, 'spicy-blogroll')){die('Sorry, this feature is temporarily unavailable. If the problem persists, contact the blog owner and report the following error code. If you are the blog owner, raise a support ticket via the link in the plugin.<br /><br />Error code: <strong>42</strong>');} 
  $linkclick = get_option(SHORT_NAME.'_linkclick');
  if ($linkclick == 'Parent window'){
    $js_1 = 'onClick="top.location.href=\'';
    $js_2 = '\';return false"';
  }else{
    $js_1 = 'onClick="window.open(\'';
    $js_2 = '\');return false"';
  }  
  // formatted HTML will be placed into this variable
  $result = '';
  // number of posts to retrieve
  $number = get_option(SHORT_NAME.'_num_posts',4);
  $link_url = trailingslashit($link_url);
  // have a guess at the feed type
  if (strstr($link_url,".xml")){
    // link url is a feed
	$feed_url = untrailingslashit($link_url);
  }
  else if (strstr($link_url,"blogspot")){
    // blogspot blog
	$feed_url = $link_url."feeds/posts/default/";
  }
  else if (strstr($link_url,"typepad")){
    // Typepad blog
	$feed_url = $link_url."atom.xml";
  }
  else {
    // own domain or WordPress blog
	$feed_url = $link_url."feed/";
  }
  // use WP to fetch the RSS feed
  $feedfile = fetch_feed($feed_url);
  // check for a valid response from the feed url
  if (!is_wp_error($feedfile)){
    // set the number of recent items from the feed
    $maxitems = $feedfile->get_item_quantity($number);
	$feed_items = $feedfile->get_items(0, $maxitems);
  }	
  // create HTML out of these recent posts
  if ($maxitems > 0){
	$result = '<p><strong>'.get_option(SHORT_NAME.'_intro_rss','Recent posts from ').' '.$link_text.'</strong></p><ul>';
	foreach($feed_items as $item){
	  // fetch the post info
	  $item_title = $item->get_title();
	  $item_link = $item->get_permalink();
	  $item_description = SpicyBlogroll_GetExerpt($item->get_description());
	  // format result
	  $result .= '<li><a href="'.$item_link.'" class="sb_link"'
	   .$js_1.$item_link.$js_2.'">'.$item_title.'</a>
	   <p class="sb_desc">'.$item_description.'</p></li>';
	   //.' target="'.$link_target
	}
	$result .= '</ul>';
	if (get_option(SHORT_NAME.'_dev_link',false)){
	  $result .= '<p class="sb_devlink"><a href="'.$dev_link.'" class="sb_link" title="Spicy Blogroll home page" '.$js_1.$dev_link.$js_2.'>Spicy Blogroll</a> by Michael Pedzotti</p>';
	}
  }else{
    // in case no feed is found to parse
	$result = '<p><strong>'.get_option(SHORT_NAME.'_intro_rss','Recent posts from ').' '.$link_text.'</strong></p><p>'.get_option(SHORT_NAME.'_no_rss','No posts available.').'</p>';
	if (get_option(SHORT_NAME.'_dev_link',false)){
	  $result .= '<p class="sb_devlink"><a href="'.$dev_link.'" class="sb_link" title="Spicy Blogroll home page" '.$js_1.$dev_link.$js_2.'>Spicy Blogroll</a> by Michael Pedzotti</p>';
	}
  }		
  // return the HTML code for the popup
  die ($result);
}
?>