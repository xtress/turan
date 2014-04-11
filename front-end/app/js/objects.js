'use strict';

/* Objects */

var settingsJs = {
    getLocale: function(){

        var locale = 'ru';
        var locationPartials = location.host.split('.');
        if (locationPartials[0] == 'www'){
            if (locationPartials[1] == 'en'){
                locale = 'en';
            }
        }else{
            if (locationPartials[0] == 'en'){
                locale = 'en';
            }
        }
        return locale;
    },
    getUniqueValue: function(){
        var value = Math.round(new Date().getTime() / 1000);
        return value;
    }
};


var mainJs = {
    initDateTimePickers: function (){
        $('#request_date').datetimepicker({
            pickTime: false,
            language: settingsJs.getLocale(),
            setDate: 'today'
        });
    },
    getFormattedDate: function(input){
        var data = input.toJSON().slice(0,10);
        var pattern=/(.*?)\-(.*?)\-(.*?)$/;
        var result = data.replace(pattern,function(match,p1,p2,p3){
            return (p3<10?"0"+p3:p3)+"."+p2+"."+p1;
        })

        return result;
    },
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
	  		$('.mobile-menu').animate({left: "0px"},500);

  			$('.btn-menu').animate({left: "273px"},500);

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
	  			right: "0px"
	  		},500);
  			
  		}
  	},
  	togglePositionDesc: function(element){
      var descriptonElement = $(element).parent().parent().children('.position-description');
      $(descriptonElement).toggle("slow");

    },
    changePanoranView: function(className, url){
      var urlsIMG = {
          1: 'asset/images/1.jpg',
          3: 'asset/images/3.jpg',
          4: 'asset/images/4.jpg'
      };
      var urlsIframe = {
          1: 'asset/pizza.html',
          3: 'asset/restorant.html',
          4: 'asset/banket.html'
      };
      var $iframe = $('.' + className);
      console.log( urlsIMG[url]);
      if ( $('iframe.main-panoram-view').css('display') !== 'none'){
          $iframe.attr('src', urlsIframe[url]);
      }else{
          $iframe.attr('src', urlsIMG[url]);
      }

    }
}
