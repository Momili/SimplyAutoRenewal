app.controller('adminUsersController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
        
    $http.get('models/get_admin_users.php').success(function (response) {
            $scope.admin_users = response.users;
	});
		
}]);