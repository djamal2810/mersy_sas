$( document ).ready(function() 
{
	var navigation_bar_height = $('.navigation-bar').height();
	var header_astros_navy_banner_height = $('#header_astros_navy_banner').height();
	$('.navigation-bar-wrapper').height(navigation_bar_height);
	var navigation_bar = $('.navigation-bar')[0];
  	let resizeObserver = new ResizeObserver(() => {
        //alert("The element was resized");
		navigation_bar_height = $('.navigation-bar').height();
		$('.navigation-bar-wrapper').height(navigation_bar_height);
    });
  
    resizeObserver.observe(navigation_bar);
    //alert(header_astros_navy_banner_height + '  ' + navigation_bar_height);
});

