/*
======================================================================================
	||
    * arkalys-interface.js
    * Code by Nicow 2016
	||
======================================================================================
*/

$(document).ready(function(){

	/* resize img responsive */

		$('img[cover]').each(function(){
			resizeResponsive(this, 190);
		});
		$(window).resize(function(){
			$('img[cover]').each(function(){
				resizeResponsive(this, 190);
			});
		});

	/* nav */

		$('body').on('click', 'li[data-nav]', function(){
			var hNav = $('nav[role=navigation]').height();
			if(hNav < 275){
				$('nav[role=navigation]').animate({
					'height': 275,
				}, 100);
			}else{
				$('nav[role=navigation]').animate({
					'height': 0,
				}, 100);
			}
		});

});