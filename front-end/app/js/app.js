'use strict';


// Declare app level module which depends on filters, and services
angular.module('restApp', ['restApp.filters', 'restApp.services', 'restApp.directives', 'restApp.controllers']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {templateUrl: 'partials/main.html', controller: 'MainCtrl'});
    $routeProvider.when('/news/:id', {templateUrl: 'partials/news.html', controller: 'MainCtrl'});
    $routeProvider.when('/gallery', {templateUrl: 'partials/gallery.html', controller: 'MainCtrl'});
    $routeProvider.when('/gallery/:alias', {templateUrl: 'partials/gallery.item.html', controller: 'MainCtrl'});
    $routeProvider.when('/contacts', {templateUrl: 'partials/contacts.html', controller: 'MainCtrl'});
    $routeProvider.when('/history', {templateUrl: 'partials/history.html', controller: 'MainCtrl'});
    $routeProvider.when('/vacancies', {templateUrl: 'partials/vacancies.html', controller: 'MainCtrl'});
    $routeProvider.when('/ballroom', {templateUrl: 'partials/ballroom.html', controller: 'MainCtrl'});
    $routeProvider.when('/restaurant', {templateUrl: 'partials/restaurant.html', controller: 'MainCtrl'});
    $routeProvider.when('/pizzeria', {templateUrl: 'partials/pizzeria.html', controller: 'MainCtrl'});
    $routeProvider.when('/recover', {templateUrl: 'partials/recover.html', controller: 'MainCtrl'});
    $routeProvider.when('/registration', {templateUrl: 'partials/registration.html', controller: 'MainCtrl'});

    $routeProvider.otherwise({redirectTo: '/'});
  }]);