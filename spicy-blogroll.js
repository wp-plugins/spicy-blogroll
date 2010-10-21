/*global jQuery: false, document: false, window: false, SpicyBlogrollSettings: false 
For JSLint JS online checker*/
function stripHTML(oldString) {
  var stripped = oldString.replace(/(<([^>]+)>)/ig,"");
  return stripped;
}
jQuery(document).ready(function($){
  // connect to hover event of <a> in .widget_links
  $('.widget_links a').hover(
  function(e){
    if (!e) {e = window.event;}
	// create a new div and append it to the link
	$(this).append('<div id="sb_popup"></div>');
	// get coords of hovered link
	var mouseX = 0;
	var mouseY = 0;
	if (e.pageX || e.pageY){
		mouseX = e.pageX;
		mouseY = e.pageY;
	}
	else if (e.clientX || e.clientY){
		mouseX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		mouseY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	// move the top left corner as per adjustment settings
    mouseX += parseInt(SpicyBlogrollSettings.var6,10);
    mouseY += parseInt(SpicyBlogrollSettings.var7,10);
	var opac = parseInt(SpicyBlogrollSettings.var10,10);
	// position the inserted div
	$('#sb_popup').css({
	  'left': mouseX + 'px',
	  'top': mouseY + 'px',
	  'width': parseInt(SpicyBlogrollSettings.var8,10) + 'px',
	  'min-height': parseInt(SpicyBlogrollSettings.var9,10) + 'px',
	  'filter': 'alpha(opacity=' + opac + ')',
	  'opacity': opac/100	  
	});
	var timer = parseInt(SpicyBlogrollSettings.var12,10);
	var time_out = SpicyBlogrollSettings.var13;
	var rss_wait = SpicyBlogrollSettings.var14;
	if (timer < 1000) {timer = 1000;}
	var link_txt = stripHTML(this.innerHTML);
	$('#sb_popup').html(rss_wait);
    $('#sb_popup').fadeIn(300);
	$.ajax({
	  type:	"GET",
	  url: SpicyBlogrollSettings.var1 + '/spicy-blogroll-ajax.php',
	  timeout:	timer,
	  data: {
	    link_url: this.href,
		link_text: link_txt,
		var2: SpicyBlogrollSettings.var2,
		var3: SpicyBlogrollSettings.var3,
		var4: SpicyBlogrollSettings.var4,
		var5: SpicyBlogrollSettings.var5,
		var11: SpicyBlogrollSettings.var11
	  },
	  success:	function(msg){
        $('#sb_popup').html(msg);
	  },
	  error: function(msg){
	    $('#sb_popup').html(time_out);
	  }
	});
  },
  // remove tip when the mouse hovers away
  function(){
	$(this).find('div').remove();
  }); // hover
}); // doc.ready