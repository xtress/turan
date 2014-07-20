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
  controller('RegistrationCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'AUTH_EVENTS', 'Session',  function($scope, $routeParams, $http, $location, $translate, AuthService, AUTH_EVENTS, Session){
     $scope.registrationFormErrors = {};
     $scope.registrationAction = function (){

        var formData = $scope.registrationForm;
      
        if(typeof formData != 'undefined'){

          if(!formData.hasOwnProperty('FIO')||formData.FIO ==""){
            $scope.registrationFormErrors.FIO = true;
          }else{
             delete $scope.registrationFormErrors.FIO;
          }
          var  phoneValidator=  new RegExp(/^[\+ ]{0,1}[(]{0,1}[375]{0,3}[)]{0,1}[\-\ ?]{0,1}([29]{2}|[33]{2}|[44]{2})[\-\ ?]{0,1}[0-9]{3}[\-\ ?]{0,1}[0-9]{2}[\-\ ?]{0,1}[0-9]{2}$/);
          if(!formData.hasOwnProperty('phone')||formData.phone ==""){
            $scope.registrationFormErrors.phone = 'REQUIRED_FIELD';
          }else{
              if(phoneValidator.test(formData.phone) == true){
                  delete $scope.registrationFormErrors.phone;
              }else{
                  $scope.registrationFormErrors.phone = 'INVALID_PHONE';
              }
          }

          var  mailValidator= /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if(!formData.hasOwnProperty('email')||formData.email ==""){
             $scope.registrationFormErrors.email = 'REQUIRED_FIELD';
          }else{
              if(mailValidator.test(formData.email) == true){
                  delete $scope.registrationFormErrors.email;
              }else{
                  $scope.registrationFormErrors.email = 'INVALID_EMAIL';
              }
          }

          if(!formData.hasOwnProperty('password')||formData.password ==""){
            $scope.registrationFormErrors.password = true;
          }else{
              delete $scope.registrationFormErrors.password;
          }

          if(!formData.hasOwnProperty('password_confirm')||formData.password_confirm ==""){
            $scope.registrationFormErrors.password_confirm = true;
          }else{
            delete $scope.registrationFormErrors.password_confirm;
          }
         
          if(formData.hasOwnProperty('password')&&formData.hasOwnProperty('password_confirm')){
            if(formData.password!==formData.password_confirm ){
              $scope.registrationFormErrors.passwords_not_equal = true;
            }else{
              delete $scope.registrationFormErrors.passwords_not_equal;
            }
          }else{
              delete $scope.registrationFormErrors.passwords_not_equal;
          }

          console.log($scope.registrationFormErrors);
          if(Object.getOwnPropertyNames($scope.registrationFormErrors).length == 0){
              delete formData.password_confirm;
              AuthService.register(formData);
          }else{
              $scope.registrationFormErrors.message = 'INVALID_FORM';
          }
        }
        
     }

        $scope.$on(AUTH_EVENTS.loginSuccess, function(event, args) {
            console.log('Login success! Set new user data:');
            console.log(Session.getUserData());
            console.log("RegisterCtrl->AUTH_EVENTS.loginSuccess:");
            console.log("Redirerct to account page");
            window.location.replace(settingsJs.getBaseUrl()+"#/account");
        });

        $scope.$on(AUTH_EVENTS.registrationFailed, function(event, args) {
            console.log('Registration failed!' );
            $scope.$apply(function () {
                delete $scope.formData.password;
                delete $scope.formData.password_confirm;
                $scope.registrationFormErrors.message = args.message;
            });
        });
      
  }]).
  controller('LoginCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'Session', 'AUTH_EVENTS', '$rootScope', '$cookieStore', function($scope, $routeParams, $http, $location, $translate, AuthService, Session, AUTH_EVENTS, $rootScope, $cookieStore){
        if( typeof Session.getUserData() != 'undefined'){
            $location.path('/account');
        }

        $scope.loginAction = function (){
            var formData = $scope.loginForm;
            $scope.loginFormErrors = {};

            var  mailValidator= /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!formData.hasOwnProperty('username')||formData.username ==""){
                $scope.loginFormErrors.username = 'REQUIRED_FIELD';
            }else{
                if(mailValidator.test(formData.username) == true){
                    delete $scope.loginFormErrors.username;
                }else{
                    $scope.loginFormErrors.username = 'INVALID_EMAIL';
                }
            }

            if(!formData.hasOwnProperty('password')||formData.password ==""){
                $scope.loginFormErrors.password = 'REQUIRED_FIELD';
            }else{
                delete $scope.loginFormErrors.password;
            }

            if(Object.getOwnPropertyNames($scope.loginFormErrors).length == 0){
               AuthService.login(formData);
            }else{
                delete formData.password;
                $scope.loginFormErrors.message = 'INVALID_FORM';
            }

        };

        $scope.$on(AUTH_EVENTS.loginSuccess, function(event, args) {
            console.log('Login success! Set new user data:');
            console.log(Session.getUserData());
            console.log("LoginCtrl->AUTH_EVENTS.loginSuccess:");
            console.log("Redirerct to account page");
            window.location.replace(settingsJs.getBaseUrl()+"#/account");
        });

        $scope.$on(AUTH_EVENTS.loginFailed, function(event, args) {
            console.log('Login failed! User data removed.' );
            $scope.$apply(function () {
                delete $scope.loginForm.password;
                $scope.loginFormErrors.message = args.message;
              });
        });



    }]).
  controller('LogoutCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'Session', 'AUTH_EVENTS', '$rootScope', '$cookieStore', function($scope, $routeParams, $http, $location, $translate, AuthService, Session, AUTH_EVENTS, $rootScope, $cookieStore){
        if( typeof Session.getUserData() != 'undefined'){
            AuthService.logout();
        }
        $location.path('/login').replace();
  }]).
  controller('AccountSettingsCtrl', ['$scope','$routeParams','$http','$location','$translate', 'AuthService', 'AUTH_EVENTS', 'Session',  function($scope, $routeParams, $http, $location, $translate, AuthService, AUTH_EVENTS, Session){
      console.log(Session.getUserData());

      $scope.changeAction = function(){

      }
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



