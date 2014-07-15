'use strict';

/* Services */



angular.module('restApp.services', ['ngCookies']).
    value('version', '0.1').
    factory('Session', function ($cookieStore, $rootScope, AUTH_EVENTS) {
        this.create = function (userData) {
           $cookieStore.put('userData', userData);
           $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
        };
        this.destroy = function (data) {
           $cookieStore.remove('userData');
           $rootScope.$broadcast(AUTH_EVENTS.loginFailed, data);
        };
        this.getUserData = function () {
            return $cookieStore.get('userData');
        };

        return this;
    }).
    constant('AUTH_EVENTS', {
        loginSuccess: 'auth-login-success',
        loginFailed: 'auth-login-failed',
        logoutSuccess: 'auth-logout-success',
        sessionTimeout: 'auth-session-timeout',
        notAuthenticated: 'auth-not-authenticated',
        notAuthorized: 'auth-not-authorized'
    }).
    factory('locale', ['$location', function($location) {
        var locale = 'ru';
        var locationPartials = $location.host().split('.');
        if (locationPartials[0] == 'en'){
            locale = 'en';
        }
        return locale;

    }])
    .factory('AuthService', function ($http, Session) {
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
                   Session.destroy(data);
                 }
            });
        };

//        authService.isAuthenticated = function () {
//            return !!Session.token;
//        };
//
//        authService.isAuthorized = function (authorizedRoles) {
//            if (!angular.isArray(authorizedRoles)) {
//                authorizedRoles = [authorizedRoles];
//            }
//            return (authService.isAuthenticated() &&
//                authorizedRoles.indexOf(Session.userRole) !== -1);
//        };

        return authService;
    })

;
