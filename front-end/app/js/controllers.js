'use strict';

/* Controllers */

angular.module('restApp.controllers', ['restApp.services']).

  controller('MainCtrl', ['$scope','apiConfig', function($scope, apiConfig) {
    $scope.apiConfig = apiConfig;
  }])
