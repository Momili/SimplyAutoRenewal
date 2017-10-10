var app = angular.module('myApp', ['ngRoute']);
        
    // configure our routes
    app.config(function($routeProvider) {
        $routeProvider
            // route for the access page
            .when('/', {
                templateUrl : 'settings.html'
                //controller  : 'mainController'
            })
            // route for the about page
            .when('/payment', {
                templateUrl : 'payment.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/success', {
                templateUrl : 'success.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/listings', {
                templateUrl : 'listings.html'
                //,controller  : 'initListings'
            })
            // route for the about page
            .when('/completed', {
                templateUrl : 'completed.html'
                //,controller  : 'initListings'
            })
            .when('/admin_renewals', {
                templateUrl : 'admin_renewals.html'
                //,controller  : 'initListings'
            })
            .when('/admin_users', {
                templateUrl : 'admin_users.html'
                //,controller  : 'initListings'
            })
            .when('/admin_listings', {
                templateUrl : 'admin_listings.html'
                //,controller  : 'initListings'
            })
            // route for the contact page
            .when('/contact', {
                templateUrl : 'contact.html'
                //,controller  : 'initListings'
            })
            .when('/renewals', {
                templateUrl : 'renewals.html'
                //,controller  : 'renewalsController'
            });             
    });
    
    var signOut=function(){
          //local  
          window.location.href='/SimplyAutoRenewal/login.php?logout=1#/';
          //
          /*
           window.location.href='http://www.simplyautorenewal.com/login.php?logout=1#/';* 
          */
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
   