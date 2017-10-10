
app.controller('adminController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
        
    // Load products from server
    //if(!$rootScope.myRenewals){
        $http.get('models/get_admin_renewals.php').success(function (response) {
            $scope.admin_renewals = response.renewals;
	});
    //}		
}]);