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

        var sliderCacheFile = 'mainSlider.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/slider/'+settingsJs.getLocale()+'/';
        $http({method: 'GET', url: contentFolder+sliderCacheFile}).
            success(function(data, status, headers, config) {
                $scope.mainSlider = data;
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!: "+contentFolder+sliderCacheFile);
            });

        var sliderCacheFile = 'actions.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/actions/'+settingsJs.getLocale()+'/';
        $http({method: 'GET', url: contentFolder+sliderCacheFile}).
            success(function(data, status, headers, config) {
                $scope.actions = data;
            }).
            error(function(data, status, headers, config) {
                console.log("Not found!: "+contentFolder+sliderCacheFile);
            });

                    
      $scope.$on('onRepeatLast', function(scope, element, attrs){
          $(".menu li a.active").removeClass('active');
          $('.slider').bxSlider({ 
              slideWidth: 720,
              minSlides: 1,
              maxSlides: 1,
              slideMargin: 10,
              pager: false,
              nextText: '',
              prevText: '',
              auto: true,
              pause: 6000
          });
      });    

        

  }]).

  controller('ContentCtrl', ['$scope','$routeParams','$http','$location', function($scope, $routeParams, $http, $location){
        var alias = $routeParams.alias;
        var contentCacheFile = alias+'.json?'+settingsJs.getUniqueValue();
        var contentFolder = 'content/static/'+settingsJs.getLocale()+'/';
        $scope.alias = alias;
        $http({method: 'GET', url: contentFolder+contentCacheFile}).
            success(function(data, status, headers, config) {
                $scope.page = data;
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
        $scope.recoverFormErrors = {};
        $scope.recoverAction = function (){
            var formData = $scope.recoverForm;

            console.log(formData);
        };
  }]).
  controller('RegistrationCtrl', ['$scope','$routeParams','$http','$location','$translate', function($scope, $routeParams, $http, $location, $translate){
     $scope.registrationFormErrors = {};
     $scope.registrationAction = function (){
        var formData = $scope.registrationForm;
      
        if(typeof formData != 'undefined'){
          var errors = 6;

          if(!formData.hasOwnProperty('FIO')||formData.FIO ==""){
            $scope.registrationFormErrors.FIO = true;
          }else{
             $scope.registrationFormErrors.FIO = false; errors--;
          }

          if(!formData.hasOwnProperty('phone')||formData.phone ==""){
            $scope.registrationFormErrors.phone = true;
          }else{
             $scope.registrationFormErrors.phone = false; errors--;
          }

          if(!formData.hasOwnProperty('email')||formData.email ==""){
            $scope.registrationFormErrors.email = true;
          }else{
             $scope.registrationFormErrors.email = false; errors--;
          }

          if(!formData.hasOwnProperty('password')||formData.password ==""){
            $scope.registrationFormErrors.password = true;
          }else{
             $scope.registrationFormErrors.password = false; errors--;
          }

          if(!formData.hasOwnProperty('password_confirm')||formData.password_confirm ==""){
            $scope.registrationFormErrors.password_confirm = true;
          }else{
             $scope.registrationFormErrors.password_confirm = false; errors--;
          }
         
          if(formData.hasOwnProperty('password')&&formData.hasOwnProperty('password_confirm')){
            if(formData.password!==formData.password_confirm ){
              $scope.registrationFormErrors.passwords_not_equal = true;
            }else{
              $scope.registrationFormErrors.passwords_not_equal = false; errors--;
            }
          }else{
            $scope.registrationFormErrors.passwords_not_equal = false; errors--;
          }

          if(errors == 0){
            delete formData.password_confirm;
            $.ajax({
                type    : 'POST',
                url     : 'http://'+location.host+'/admin/web/app_dev.php/api/user/register',
                data    : formData,
                dataType  : 'json',
                success   : function(data) {
                    if(data.status == true){
                        var n = noty({layout:'center', type: 'success',text: $translate(data.message)});
                    }else{
                        var n = noty({layout:'center', type: 'error',text: $translate(data.message)});
                    }
                }

            });
          


          }

        }     
        
     }


      
  }]).

  controller('LoginCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'Session', 'AUTH_EVENTS', '$rootScope', '$cookieStore', function($scope, $routeParams, $http, $location, $translate, AuthService, Session, AUTH_EVENTS, $rootScope, $cookieStore){
        if( typeof Session.getUserData() != 'undefined'){
            $location.path('/account');
        }

        $scope.loginFormErrors = {};

        $scope.loginAction = function (){
            var formData = $scope.loginForm;
            AuthService.login(formData);
        };

        $scope.$on(AUTH_EVENTS.loginSuccess, function(event, args) {
            console.log('Login success! Set new user data:');
            console.log(Session.getUserData());
            console.log("LoginCtrl->AUTH_EVENTS.loginSuccess:");
            console.log("Redirerct to account page");
            window.location.replace(window.location.origin+window.location.pathname+"#/account");
        });

        $scope.$on(AUTH_EVENTS.loginFailed, function(event, args) {
            console.log('Login failed! User data removed.' );
            $scope.loginFormErrors = JSON.stringify(args);
        });


    }]).
  controller('LogoutCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'Session', 'AUTH_EVENTS', '$rootScope', '$cookieStore', function($scope, $routeParams, $http, $location, $translate, AuthService, Session, AUTH_EVENTS, $rootScope, $cookieStore){
        if( typeof Session.getUserData() != 'undefined'){
            AuthService.logout();
        }
        $location.path('/login').replace();
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
            var apiUrl = 'turan.by';
            var locationPartials = location.host.split('.');

            if (locationPartials[0] == 'www'){
                apiUrl = 'www.turan.by';
            }

            $.ajax({
                type 		: 'POST',
                url 		: 'http://'+apiUrl+'/admin/web/app_dev.php/api/add-Order',
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
  }]).
  controller('AccountCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'Session', 'AUTH_EVENTS', '$rootScope', '$cookieStore', function($scope, $routeParams, $http, $location, $translate, AuthService, Session, AUTH_EVENTS, $rootScope, $cookieStore){
        if(typeof Session.getUserData() == 'undefined'){
            console.log("AccountCtrl:");
            console.log("Redirerct to Login page");
            $location.path('/login');
        }


        $scope.page = {'title': 'Test',
                       'content': JSON.stringify(Session.getUserData())};
}])



