'use strict';

/* Services */



angular.module('restApp.services', ['ngCookies']).
    constant('AUTH_EVENTS', {
        loginSuccess: 'auth-login-success',
        loginFailed: 'auth-login-failed',
        logoutSuccess: 'auth-logout-success',
        registrationFailed:'registration-failed'
    }).
    factory('Session', function ($cookieStore, $rootScope, AUTH_EVENTS) {
        this.create = function (userData) {
           $cookieStore.put('userData', userData);
           $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
        };
        this.logout = function(){
            $cookieStore.remove('userData');
            $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
        }
        this.destroy = function (data, callback) {
           $cookieStore.remove('userData');
           $rootScope.$broadcast(callback, data);
        };
        this.getUserData = function () {
            return $cookieStore.get('userData');
        };

        return this;
    }).
    factory('locale', ['$location', function($location) {
        var locale = 'ru';
        var locationPartials = $location.host().split('.');
        if (locationPartials[0] == 'en'){
            locale = 'en';
        }
        return locale;

    }])
    .factory('AuthService', function ($http, Session, AUTH_EVENTS) {
        var authService = {};
        authService.login = function (credentials) {
            return  $.ajax({
                url: 'http://'+location.host+'/admin/web/app_dev.php/api/user/login',
                method: "POST",
                data: credentials,
                dataType  : 'json'
            }).success(function (data) {
                if(data.status == true){
                  Session.create(data.content);
                }else{
                   Session.destroy(data, AUTH_EVENTS.loginFailed);
                 }
            });
        };
        authService.logout = function () {
            Session.logout();
        };
        authService.register = function (formData) {
            return  $.ajax({
                type    : 'POST',
                url     : 'http://'+location.host+'/admin/web/app_dev.php/api/user/register',
                data    : formData,
                dataType  : 'json',
                success   : function(data) {
                    if(data.status == true){
                        Session.create(data.content);
                    }else{
                        Session.destroy(data, AUTH_EVENTS.registrationFailed);
                    }
                }
            });
        };

        return authService;
    })

;
