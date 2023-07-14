//alert('i am here');
	//var objHeight = $("#radial-menu-containing-box").outerHeight();
    //var objWidth = $("#radial-menu-containing-box").outerWidth();
	var objHeight = $("#radial-menu-containing-box").innerHeight();
    var objWidth = $("#radial-menu-containing-box").innerWidth();
	var offset = $("#radial-menu-containing-box").offset();
	var leftPos  = offset.left;
	var rightPos = offset.left + objWidth;
	var topPos   = offset.top;
	var bottomPos= offset.top + objHeight;
	var overFlowWidth = $(".service-radial-menu-container a").outerWidth();
		
	var centerX = (leftPos+rightPos)/2-leftPos;
	var centerY = (topPos+bottomPos)/2;
	
		
	//var maxDiameter = ((rightPos-leftPos)<(bottomPos-topPos))?(rightPos-leftPos):(bottomPos-topPos);
	//var maxRadius = maxDiameter/2-overFlowWidth;
	//var maxDiameter = (rightPos-leftPos);
	//var maxRadius = maxDiameter/2-overFlowWidth/2;

	
	$("#radial-menu-containing-box").css('height', $("#radial-menu-containing-box").css('width'));
	//var container_side = $("#radial-menu-containing-box").css('width');
	
	
	//alert($("#radial-menu-containing-box").width());
	var radial_menu_containing_box_width = $("#radial-menu-containing-box").width();
	var new_radius = radial_menu_containing_box_width/2-overFlowWidth/2;
	//alert(new_radius);
	
	//var leftPos  = 0;
	//var topPos   = centerY;
		
	//alert('leftPos: '+leftPos+', rightPos: '+rightPos+', topPos: '+topPos+', bottomPos:'+bottomPos+', centerX: '+centerX+', centerY: '+centerY+', maxDiameter: '+maxDiameter + ', overFlowWidth:' +overFlowWidth);
	
	//$('.service-radial-menu-container').incircle({top: topPos+'px', left: centerX+'px', radius: maxRadius+'px' });
	$('.service-radial-menu-container').incircle({radius: new_radius+'px' });

$( document ).ready(function() 
{
	
});		


$( window ).on("load", function() 
{
		//CIRCULAR MENU SECTION
		//END OF CIRCULAR MENU SECTION
		var partnerJsonArray = document.getElementById('partner-container').getAttribute('data-partner-json-array');
		var partnerArrayParsed = jQuery.parseJSON( partnerJsonArray ); 
		var htmlPartnerIndex = 0;
		const partnerContainer = $("#partner-container");
		const card_post_container = $("#partner-container .card-poster-container");
		const partnerCardImage = $("#partner-container .card-poster-container img");
		
		var maxHeight = 0;
		
		card_post_container.css( "height",  card_post_container.css("width"));
		
		setInterval(function()
		{
			
			partnerCardImage.attr("src", partnerArrayParsed[htmlPartnerIndex].logo);
			partnerCardImage.attr("width", card_post_container.css("width"));
			partnerCardImage.attr("height", card_post_container.css("width"));
			htmlPartnerIndex++;
			htmlPartnerIndex %= partnerArrayParsed.length;
			//alert('hi');
		}, 2000);

	});
	