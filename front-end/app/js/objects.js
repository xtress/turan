'use strict';

/* Objects */
var mainJs = {
  	
  	toggleMenu: function (){
  		if ($('.mobile-search').hasClass('visible')){
  			this.toggleSearch();
  		}
		$('.mobile-menu').stop();
  		$('.mobile-menu').html($('.main-menu').html());
  		if (! $('.mobile-menu').hasClass('visible')){
  			$('.mobile-menu').show();
			$('.mobile-menu').addClass('rounded');
			$('.mobile-menu').addClass('visible');
	  		$('.mobile-menu').animate({
	  			left: "0px",
	  		},500);

  			$('.btn-menu').animate({
	  			left: "273px",
	  		},500);

  		}else{
	  		$('.mobile-menu').animate({
	  			left: "-255px",
	  		},
	  		{
    			duration: 500,
				complete: function() {
				    $('.mobile-menu').removeClass('rounded');
  					$('.mobile-menu').removeClass('visible');
  					$('.mobile-menu').hide();
				}
    		});
    		$('.btn-menu').animate({
	  			left: "0px",
	  		},500);
  			
  		}
  	},

  	toggleSearch: function (){
  		if ($('.mobile-menu').hasClass('visible')){
  			this.toggleMenu();
  		}
		$('.mobile-search').stop();
  		$('.mobile-search').html($('article.right').html());
  		
  		if (! $('.mobile-search').hasClass('visible')){
  			$('.mobile-search').show();
			$('.mobile-search').addClass('rounded');
			$('.mobile-search').addClass('visible');
	  		$('.mobile-search').animate({
	  			right: "0px",
	  		},500);

  			$('.btn-login').animate({
	  			right: "273px",
	  		},500);

  		}else{
	  		$('.mobile-search').animate({
	  			right: "-255px",
	  		},
	  		{
    			duration: 500,
				complete: function() {
				    $('.mobile-search').removeClass('rounded');
  					$('.mobile-search').removeClass('visible');
  					$('.mobile-search').hide();
				}
    		});
    		$('.btn-login').animate({
	  			right: "0px",
	  		},500);
  			
  		}
  	},
  	togglePositionDesc: function(element){
      var descriptonElement = $(element).parent().parent().children('.position-description');
      $(descriptonElement).toggle("slow");

    }
}