'use strict';

/* Directives */


angular.module('restApp.directives', []).
    directive('appVersion', ['version', function(version) {
        return function(scope, elm, attrs) {
            elm.text(version);
        };
    }])
    .directive('mainMenu', function(){
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
    })
    .directive('languagesLinks', function(){
        return {
            transclude: true,
            restrict: 'M',
            replace: true,
            templateUrl: 'partials/languagesLinksTemplate.html',
            compile: function compile(tElement, tAttrs) {
                return function postLink(scope, elm, attrs) {
                    var location = document.location;
                    var locationPartials = location.hostname.split('.');
                    if (locationPartials[0] == 'www'){
                        if (locationPartials[1] == 'en'){
                           
                            scope.ruLink = location.protocol+'//'+locationPartials[0]+'.'+locationPartials[2]+'.'+locationPartials[3]+location.pathname;
                            scope.enLink = location.protocol+'//'+locationPartials[0]+'.'+locationPartials[1]+'.'+locationPartials[2]+'.'+locationPartials[3]+location.pathname;
                        }else{
                            scope.ruLink = location.protocol+'//'+locationPartials[0]+'.'+locationPartials[1]+'.'+locationPartials[2]+location.pathname;
                            scope.enLink = location.protocol+'//'+locationPartials[0]+'.en.'+locationPartials[1]+'.'+locationPartials[2]+location.pathname;
                        }
                    }else{
                        if (locationPartials[0] == 'en'){
                            
                            scope.ruLink = location.protocol+'//'+locationPartials[1]+'.'+locationPartials[2]+location.pathname;
                            scope.enLink = location.protocol+'//'+locationPartials[0]+'.'+locationPartials[1]+'.'+locationPartials[2]+location.pathname;
                        }else{
                            scope.ruLink = location.protocol+'//'+locationPartials[0]+'.'+locationPartials[1]+location.pathname;
                            scope.enLink = location.protocol+'//'+'en.'+locationPartials[0]+'.'+locationPartials[1]+location.pathname;
                        }
                    }

                    $('a.lang.'+settingsJs.getLocale()).addClass('active');

                }
            }


        };
    })
;