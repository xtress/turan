'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('restApp.services', []).
  value('version', '0.1').
   factory('apiConfig', ['$http', function($http) {
      return $http.get('../config/app.conf.json').then(function(response) {
              return response.data;
             });

    }]);
