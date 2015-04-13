/*
    Author  - Anjana Wijesundara
    Contact - wsanjana951@gmail.com
*/

var app = angular.module('Application', ['ngRoute']);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when('/', {templateUrl: 'pages/lists.html', controller: 'CustomerListControler'}).
      when('/NewCustomer', {templateUrl: 'pages/new.html', controller: 'CustomerAddControler'}).
      when('/UpdateCustomer/:id', {templateUrl: 'pages/edit.html', controller: 'CustomerEditControler'}).
      otherwise({redirectTo: '/'});
}]);

app.controller ('CustomerListControler',[
  '$scope','$http',
  function ($scope, $http) {
      $http.get('api/Customers').success(function(data) {
        $scope.customers = data;  
      });
  }    
]),

app.controller ('CustomerAddControler',[
  '$scope','$http','$location',
  function ($scope, $http, $location) {
  
      $scope.master = {};
      $scope.activePath = null;

      $scope.New_Customer = function(customer, AddNewForm) {
          console.log(customer);

          $http.post('api/New_Customer', customer).success(function(){
              $scope.reset();
              $scope.activePath = $location.path('/');
          });

          $scope.reset = function() {
              $scope.customer = angular.copy($scope.master);
          };

          $scope.reset();
      };

  }
]),

app.controller('CustomerEditControler',[

  '$scope','$http','$location','$routeParams',
  function ($scope, $http, $location, $routeParams) {

      var id = $routeParams.id;
      $scope.activePath = null;

      $http.get('api/Customers/'+id).success(function(data) {
        $scope.CustomerDetail = data;
      });

      $scope.Update_Customer = function(customer) {
          $http.put('api/Customers/'+id, customer).success(function(data) {
          $scope.CustomerDetail = data;
          $scope.activePath = $location.path('/');
        });

      };

      $scope.Delete_Customer = function(customer) {
          console.log(customer);
          var deleteCustomer = confirm('Are you absolutely sure you want to delete?');
          if (deleteCustomer) {
              $http.delete('api/Customers/'+customer.id);
              $scope.activePath = $location.path('/');
          }        
      };

  }
]);