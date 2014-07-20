'use strict';

/* Directives */


angular.module('restApp.directives', [])
    .directive('onLastRepeat', function() {
        return function(scope, element, attrs) {
            if (scope.$last) setTimeout(function(){
                scope.$emit('onRepeatLast', element, attrs);
            }, 1);
        };
    })
    .directive('mainMenu', ['$rootScope', 'Session', 'AUTH_EVENTS', function($rootScope, Session, AUTH_EVENTS){
        return {
            transclude: true,
            restrict: 'M',
            replace: true,
            templateUrl: 'partials/mainMenuTemplate.html',
//            controller: function($scope, $attrs, Session, AUTH_EVENTS) {
//                $scope.$on(AUTH_EVENTS.loginSuccess, function() {
//
//                    $scope.logined = false;
//                    if (typeof Session.getUserData() != 'undefined'){
//                        $scope.logined = true;
//                    }
//
//                });
//                $scope.$on(AUTH_EVENTS.logoutSuccess, function() {
//
//                    $scope.logined = false;
//                    if (typeof Session.getUserData() != 'undefined'){
//                        $scope.logined = true;
//                    }
//
//                });
//            },
            compile: function compile(tElement, tAttrs) {
                return function postLink(scope, elm, attrs) {

                    scope.logined = false;
                    if (typeof Session.getUserData() != 'undefined'){
                        scope.logined = true;
                    }
                    var location = document.location.hash;
                    $(".menu li a[href='"+location+"']").addClass('active');
                    $(".menu li a").click(function(){
                        $(".menu li a.active").removeClass('active');
                        $(this).addClass('active');
                    });

                }
            }


        };
    }])
    .directive('accountMenu', ['$rootScope', 'Session', 'AUTH_EVENTS', function($rootScope, Session, AUTH_EVENTS){
        return {
            transclude: true,
            restrict: 'M',
            replace: true,
            templateUrl: 'partials/accountMenuTemplate.html',
            compile: function compile(tElement, tAttrs) {
                return function postLink(scope, elm, attrs) {

                    scope.logined = false;
                    if (typeof Session.getUserData() != 'undefined'){
                        scope.logined = true;
                    }
                    var location = document.location.hash;
                    $(".accoutn-menu li a[href='"+location+"']").addClass('active');
                    $(".accoutn-menu li a").click(function(){
                        $(".accoutn-menu li a.active").removeClass('active');
                        $(this).addClass('active');
                    });

                }
            }


        };
    }])
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

                    $('.languages.a.'+settingsJs.getLocale()).addClass('active');

                }
            }


        };
    })
;