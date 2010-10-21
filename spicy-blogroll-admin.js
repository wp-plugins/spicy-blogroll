/*global jQuery: false, document: false  
For JSLint JS online checker*/
function textCounter(field, countfield, maxlimit) {
	if(field.value.length > maxlimit){
		field.value = field.value.substring(0, maxlimit);
	}else{
		countfield.value = maxlimit - field.value.length;
	}
}
jQuery(document).ready(function(){
//		jQuery('.sbr_options').slideDown();
		jQuery('.sbr_section h3').click(function(){
			if(jQuery(this).parent().next('.sbr_options').css('display')==='none'){
			  jQuery(this).children('img').removeClass('inactive').addClass('active');
			}else{
			  jQuery(this).children('img').removeClass('active').addClass('inactive');
			}
			jQuery(this).parent().next('.sbr_options').slideToggle('slow');
		});
		jQuery('#message').fadeOut(5000);
});
