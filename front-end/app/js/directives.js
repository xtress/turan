'use strict';

/* Directives */


angular.module('restApp.directives', []).
  directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]).
   directive('mainMenu', function(){
        return {
            transclude: true,
            restrict: 'M',
            replace: true,
            templateUrl: 'partials/mainMenuTemplate.html',
            compile: function compile(tElement, tAttrs) {
                return function postLink(scope, elm, attrs) {
                    var location = document.location.hash;
                    $(".main-menu li a[href='"+location+"']").parent('li').addClass('active');
                }
            }


        };
 }).
    directive('languagesLinks', function(){
        return {
            transclude: true,
            restrict: 'M',
            replace: true,
            templateUrl: 'partials/languagesLinksTemplate.html',
            compile: function compile(tElement, tAttrs) {
                return function postLink(scope, elm, attrs) {
                    var location = document.location;
                    var locale = 'ru';
                    var locationPartials = location.hostname.split('.');
                    if (locationPartials[0] == 'en'){
                        locale = 'en';
                        scope.ruLink = location.protocol+'//'+locationPartials[1]+location.pathname+'#/';
                        scope.enLink = location.protocol+'//'+location.hostname+location.pathname+'#/';
                    }else{
                        scope.ruLink = location.protocol+'//'+location.hostname+location.pathname+'#/';
                        scope.enLink = location.protocol+'//'+'en.'+location.hostname+location.pathname+'#/';
                    }

                    $('a.lang.'+locale).addClass('active');

                }
            }


        };
    })
;

