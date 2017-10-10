var app = angular.module('myApp', ['ngRoute']);
        
    // configure our routes
    app.config(function($routeProvider) {
        $routeProvider
            // route for the access page
            .when('/', {
                templateUrl : 'home.html'
                //controller  : 'mainController'
            })
            // route for the about page
            .when('/features', {
                templateUrl : 'features.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/aboutus', {
                templateUrl : 'aboutus.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/signup', {
                templateUrl : 'signup.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/signin', {
                templateUrl : 'signin.html'
                //,controller  : 'initListings'
            });            
    });
    
    var signOut=function(){
          window.location.href='/SimplyAutoRenewal/login.php?logout=1#/';
    };
    
    app.controller('mainController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){        
	
    $scope.getUserDetails=function(){
    	
    	$http.get('models/get_user_details.php').success(function (response) {
            if(!response){
                signOut();
            }
            else{
                //angular.element('#user-name').html(response.user.name.toUpperCase());
                //angular.element('#shop-name').html(response.user.shop_name.toUpperCase());
                //angular.element('#shop-icon-url').html(response.user.shop_icon_url);
                $scope.shopImgUrl=response.user.shop_icon_url;
                //angular.element('#user-id').html(response.user.id);
                //angular.element('#user-pic').html(response.user.picture);
            }
    	});	
	};
	
	//$scope.getUserDetails();
	
			
}]);

$('.nav li a').on('click', function() {
    $(this).parent().parent().find('.active').removeClass('active');
    $(this).parent().addClass('active');    
});

$(document).ready(function(){  
    $("#login").load('login.php');
});
