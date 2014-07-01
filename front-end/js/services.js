'use strict';

/* Services */



angular.module('restApp.services', []).
  value('version', '0.1').
    factory('locale', ['$location', function($location) {
        var locale = 'ru';
        var locationPartials = $location.host().split('.');
        if (locationPartials[0] == 'en'){
            locale = 'en';
        }
        return locale;

    }])
;
