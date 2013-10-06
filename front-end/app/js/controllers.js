'use strict';

/* Controllers */

angular.module('myApp.controllers', []).
  controller('MyCtrl1', [function() {
  	console.log('MyCtrl1');
  }])
  .controller('MyCtrl2', [function() {
  		console.log('MyCtrl2');
  }]);