/*
======================================================================================
	||
    * arkalys-functions.js
    * Code by Nicow 2016
	||
======================================================================================
*/

/* resize elts 

	window.resizeResponsive = function(elts, size){
		var wElts = $(elts).width();
		var hElts = $(elts).height();
		if(hElts > wElts && hElts > size){
			$(elts).css({'width': '50%'});
			var hEltsA = $(elts).height();
			if(hEltsA < size){
				$(elts).css({'width': 'auto', 'height': '100%'});
			}
		}else{
			if(hElts < size){
				$(elts).css({'width': 'auto', 'height': '100%'});
			}
		}
	}*/
	window.resizeResponsive = function(elts, size){
		var wElts = $(elts).width();
		var hElts = $(elts).height();
		if(hElts < size){
			$(elts).css({'width': 'auto', 'height': '100%'});
			var hEltsA = $(elts).height();
			if(hEltsA < size){
				$(elts).css({'width': 'auto', 'height': '100%'});
			}
		}
	}