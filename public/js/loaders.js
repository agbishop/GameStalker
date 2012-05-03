function loadToolbar(){
	$('#banner').append(toolEle);
	 $('#psn').live("click",function(){
 	$('#isoParent').isotope({filter:'.psn'});
 });
 $('#xbox').live("click",function(){
 	$('#isoParent').isotope({filter:'.xbox'});
 });
  $('#xbox').live({
  	mouseenter:
  	function(){
 		qT($(this), 'top center', 'bottom center', 'Xbox', 'ui-tooltip-dark');
 	},
 	mouseleave:
 	function(){
 		qTD($(this));
 	}
 	}
 );
 
// QTIP Header Hover - PSN 
 $('#psn').live({
 	mouseenter:
 	function(){
 		qT($(this), 'top center', 'bottom center', 'PSN', 'ui-tooltip-dark');
 	},
 	mouseleave:
 	function(){
 		qTD($(this));
 	}
 }
 ); 

// QTIP Header Hover - Steam 
 $('#steam').live({
 	mouseenter:
 	function(){
 		qT($(this), 'top center', 'bottom center', 'Steam', 'ui-tooltip-dark');
 	},
 	mouseleave:
 	function(){
 		qTD($(this));
 	}
 }
 );
			}
function destroyToolbar(){
		$.each($('.filter'), function(){
 		$(this).remove();
 	});
 }
function loadHome(){
	$('#banner').prepend(house);
	$('.house').live("click",function() { //home to get back to menu 
        	$('.filter').show();
        	$('#Settings').fadeOut(100,function(){
            $('#isoParent').fadeIn(100);
        	});
        	$('#isoParent').isotope({filter:'.home'});
    });
    
}
function destroyHome(){
	$('.house').remove();
}
