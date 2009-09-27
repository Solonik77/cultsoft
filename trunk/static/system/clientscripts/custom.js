$(document).ready(function() { 

	// Navigation menu

	$('ul.sf-navbar').superfish({ 
		delay:       1000,
		animation:   {opacity:'show',height:'show'},
		speed:       'fast',
		autoArrows:  true,
		dropShadows: false
	});
	
	$('ul.sf-navbar li').hover(function(){
		$(this).addClass('sfHover2');
	},
	function(){
		$(this).removeClass('sfHover2');
	});

});