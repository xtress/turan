'use strict';
// Declare app level module which depends on filters, and services
angular.module('restApp', ['restApp.filters', 'restApp.services', 'restApp.directives', 'restApp.controllers', 'pascalprecht.translate']).
  config(['$routeProvider','$translateProvider','$translatePartialLoaderProvider', function($routeProvider, $translateProvider, $translatePartialLoaderProvider) {

    $routeProvider.when('/', {templateUrl: 'partials/main.html', controller: 'MainCtrl'});

    $routeProvider.when('/:alias\.html', {templateUrl: 'partials/staticContentTemplate.html', controller: 'ContentCtrl'});

//    $routeProvider.when('/contacts', {templateUrl: 'partials/contacts.html', controller: 'MainCtrl'});
//    $routeProvider.when('/history', {templateUrl: 'partials/history.html', controller: 'MainCtrl'});
//    $routeProvider.when('/ballroom', {templateUrl: 'partials/ballroom.html', controller: 'MainCtrl'});
//    $routeProvider.when('/restaurant', {templateUrl: 'partials/restaurant.html', controller: 'MainCtrl'});
//    $routeProvider.when('/pizzeria', {templateUrl: 'partials/pizzeria.html', controller: 'MainCtrl'});

    $routeProvider.when('/vacancies', {templateUrl: 'partials/vacancies.html', controller: 'VacanciesCtrl'});
    $routeProvider.when('/news/:id', {templateUrl: 'partials/news.html', controller: 'NewsCtrl'});
    $routeProvider.when('/gallery', {templateUrl: 'partials/gallery.html', controller: 'GalleryCtrl'});
    $routeProvider.when('/gallery/:type/:alias', {
        templateUrl: 'partials/gallery.item.html',
        controller: 'GalleryItemCtrl',
        resolve: {
            type: function ($q, $route) {
                var deferred = $q.defer(),
                    allowedTypes = new Array('photo', 'video');

                if (allowedTypes.indexOf($route.current.params.type) != -1) {
                    deferred.resolve();
                } else {
                    //deferred.reject('NOT_ALLOWED_TYPE');
                    console.log('NOT ALLOWED GALLERY TYPE');
                    return false;
                }

                return deferred.promise;
            }
    }});

    $routeProvider.when('/recover', {templateUrl: 'partials/recover.html', controller: 'RecoverCtrl'});
    $routeProvider.when('/registration', {templateUrl: 'partials/registration.html', controller: 'RegistrationCtrl'});
    $routeProvider.when('/reserve', {templateUrl: 'partials/reserve.html', controller: 'ReserveCtrl'});

    $routeProvider.otherwise({redirectTo: '/'});


    $translatePartialLoaderProvider.addPart('main');
    $translateProvider.useLoader('$translatePartialLoader', {
       urlTemplate: 'translations/{part}/{lang}.json'
    });

    $translateProvider.preferredLanguage(settingsJs.getLocale());
  }]);

