'use strict';


// Declare app level module which depends on filters, and services
angular.module('restApp', ['restApp.filters', 'restApp.services', 'restApp.directives', 'restApp.controllers']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {templateUrl: 'partials/main.html', controller: 'MainCtrl'});
    $routeProvider.when('/news/:id', {templateUrl: 'partials/news.html', controller: 'MainCtrl'});
    $routeProvider.when('/gallery', {templateUrl: 'partials/gallery.html', controller: 'MainCtrl'});
    $routeProvider.when('/contacts', {templateUrl: 'partials/contacts.html', controller: 'MainCtrl'});
    $routeProvider.when('/history', {templateUrl: 'partials/history.html', controller: 'MainCtrl'});

    $routeProvider.otherwise({redirectTo: '/'});
  }]);