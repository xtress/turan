'use strict';

/* Controllers */

angular.module('restApp.controllers', ['restApp.services']).

  controller('MainCtrl', ['$scope','apiConfig', function($scope, apiConfig) {
    $scope.apiConfig = apiConfig;
  }]).
  controller('NewsCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http,$location) {
    var newsCacheFile = $routeParams.id+'.json';
	$http({method: 'GET', url: 'content/news/'+newsCacheFile}).
		success(function(data, status, headers, config) {
		     $scope.news = data
		}).
		error(function(data, status, headers, config) {
		    $location.path( "#/" );
		});
  }])
