'use strict';

/* Controllers */

angular.module('restApp.controllers', ['restApp.services']).

  controller('MainCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){

        var lastNewsCacheFile = 'lastNews.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/news/'+settingsJs.getLocale()+'/';
        $http({method: 'GET', url: contentFolder+lastNewsCacheFile}).
            success(function(data, status, headers, config) {
                $scope.lastNews = data
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!");
            });
  }]).

  controller('ContentCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){
        var alias = $routeParams.alias;
        var contentCacheFile = alias+'.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/static/'+settingsJs.getLocale()+'/';
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

  controller('NewsCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location) {

    var newsId = $routeParams.id;
    var newsCacheFile = newsId+'.json?'+settingsJs.getUniqueValue();

    var contentFolder = 'content/news/'+settingsJs.getLocale()+'/';
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

  controller('RecoverCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){

  }]).
  controller('RegistrationCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){

  }]).
  controller('VacanciesCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){

  }]).
  controller('GalleryCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){


        var contentFolder = 'content/gallery/'+settingsJs.getLocale()+'/';
        var photoGalleryFile = contentFolder+'galleryList.json?'+settingsJs.getUniqueValue();
        $http({method: 'GET', url: photoGalleryFile}).
            success(function(data, status, headers, config) {
                $scope.gallery = data;
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!");
                $location.path( "#/" );
            });
  }]).
  controller('GalleryItemCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){

        var galleryType = $routeParams.type;
        var galleryAlias = $routeParams.alias;
        var galleryCacheFile = '/gallery.json?'+settingsJs.getUniqueValue();

        var contentFile = 'content/gallery/'+settingsJs.getLocale()+'/'+galleryType+'/'+galleryAlias+'/'+galleryCacheFile;
        $http({method: 'GET', url: contentFile}).
            success(function(data, status, headers, config) {
                $scope.gallery = data;
                $scope.galleryType = galleryType;
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!");
                $location.path( "#/gallery" );
            });
  }])



