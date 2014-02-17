'use strict';

/* Controllers */

angular.module('restApp.controllers', ['restApp.services']).

  controller('MainCtrl', ['$scope','$routeParams','$http','$location','apiConfig','locale', function($scope, $routeParams, $http, $location, apiConfig, locale){

        var lastNewsCacheFile = 'lastNews.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/news/'+locale+'/';
        $http({method: 'GET', url: contentFolder+lastNewsCacheFile}).
            success(function(data, status, headers, config) {
                $scope.lastNews = data
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!");
            });
  }]).

  controller('ContentCtrl', ['$scope','$routeParams','$http','$location','apiConfig','locale', function($scope, $routeParams, $http, $location, apiConfig, locale){
        var alias = $routeParams.alias;
        var contentCacheFile = alias+'.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/static/'+locale+'/';
        $scope.alias = alias;
        $http({method: 'GET', url: contentFolder+contentCacheFile}).
            success(function(data, status, headers, config) {
                $scope.page = data
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!");
                $location.path( "#/" );
            });
  }]).

  controller('NewsCtrl', ['$scope','$routeParams','$http','$location', 'locale', function($scope, $routeParams, $http, $location, locale) {

    var newsId = $routeParams.id;
    var newsCacheFile = newsId+'.json?'+settingsJs.getUniqueValue();

    var contentFolder = 'content/news/'+locale+'/';
    $http({method: 'GET', url: contentFolder+newsCacheFile}).
		success(function(data, status, headers, config) {
		     $scope.news = data
		}).
		error(function(data, status, headers, config) {
            console.log("Not found!");
            $location.path( "#/" );
		});

    var newsPaginationFile = contentFolder+'pagination.json?'+settingsJs.getUniqueValue();
    $http({method: 'GET', url: newsPaginationFile}).
            success(function(data, status, headers, config) {
                $scope.pagination = eval('data.'+'id'+$routeParams.id);
            }).
            error(function(data, status, headers, config) {
                console.log("Pagination not found!");
             });
  }]).

  controller('RecoverCtrl', ['$scope','$routeParams','$http','$location','apiConfig', function($scope, $routeParams, $http, $location, apiConfig){

  }]).
  controller('RegistrationCtrl', ['$scope','$routeParams','$http','$location','apiConfig', function($scope, $routeParams, $http, $location, apiConfig){

  }]).
  controller('VacanciesCtrl', ['$scope','$routeParams','$http','$location','apiConfig', function($scope, $routeParams, $http, $location, apiConfig){

  }]).
  controller('GalleryCtrl', ['$scope','$routeParams','$http','$location','apiConfig', function($scope, $routeParams, $http, $location, apiConfig){

//        var photoGalleryFile = contentFolder+'pagination.json?'+settingsJs.getUniqueValue();
//        $http({method: 'GET', url: contentFolder+newsCacheFile}).
//            success(function(data, status, headers, config) {
//                $scope.news = data
//            }).
//            error(function(data, status, headers, config) {
//                console.log("Not found!");
//                $location.path( "#/" );
//            });
  }]).
  controller('GalleryItemCtrl', ['$scope','$routeParams','$http','$location','apiConfig', function($scope, $routeParams, $http, $location, apiConfig){

  }])



