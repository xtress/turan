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
  }]).
  controller('ReserveCtrl', ['$scope','$routeParams','$http','$location', '$translate', function($scope, $routeParams, $http, $location, $translate){
        mainJs.initDateTimePickers();

        $scope.request_date = mainJs.getFormattedDate(new Date());
        $scope.request_time = '10:00';
        $scope.saloon = 1;
        $scope.seats = 1;
        $scope.contact_name = '';
        $scope.contact_phone ='';
        $scope.contact_email ='';
        $scope.request_description = '';

        $scope.sendOrder = function(){
            console.log('scope.request_date='+$scope.request_date);
            console.log('(.request_date).val()='+$('.request_date').val());
            var formData = {
             'request_date':  mainJs.getFormattedDateRequest($('.request_date').val()),
             'request_time': $scope.request_time,
             'saloon': $scope.saloon,
             'seats': $scope.seats,
             'contact_name': $scope.contact_name,
             'contact_phone': $scope.contact_phone,
             'contact_email': $scope.contact_email,
             'request_description': $scope.request_description
             };
            $.ajax({
                type 		: 'POST',
                url 		: 'http://localhost/turan/admin/web/app_dev.php/api/add-Order',
                data 		: formData,
                dataType 	: 'json',
                success 	: function(data) {
                    if(data.status == true){
                        var n = noty({layout:'center', type: 'success',text: $translate(data.message)});
                    }else{
                        var n = noty({layout:'center', type: 'error',text: $translate(data.message)});
                    }
                    $('#request_date').datetimepicker('setDate', new Date());
                    $('#request_time').val('10:00');
                    $('#saloon').val(1);
                    $('#seats').val(1);
                    $('#contact_name').val('');
                    $('#contact_phone').val('');
                    $('#contact_email').val('');
                    $('#request_description').val('');

                }
            });





        };
  }])



