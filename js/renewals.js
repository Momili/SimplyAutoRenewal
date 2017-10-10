
app.controller('renewalsController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
        
	// Load products from server
	//if(!$rootScope.myRenewals){
		$http.get('models/get_renewals.php').success(function (response) {
			$scope.myRenewals = response.renewals;
		});
	//}
	
	$scope.delete = function (removal) {
            var uri = 'models/cancel_renewal.php?id=' + removal.ID;
            $http.get(uri).success(function (response) {
		$scope.message = response;
	    });
            $scope.myRenewals.splice( $scope.myRenewals.indexOf(removal), 1 );		
	};
        
        $scope.delete_all = function () { 
        	if($scope.myRenewals.length>0){
	            if(window.confirm('Click OK to cancel all scheduled renewals.')){                
	                var uri = 'models/delete_all_scheduled.php';
	                $http.get(uri).success(function (response) {
	                    $scope.message = response;
	                });
	                var len=$scope.myRenewals.length;
	                $scope.myRenewals.splice( 0, len);		
	            }
        	}
	};	
        
        $scope.getForeverDesc = function (renewal) {
            var unit='';
            if(renewal.Unit==='1'){
               unit= ' minute(s)';               
            }
            else if(renewal.Unit==='2'){
                unit= ' hour(s)';  
            }
            else if(renewal.Unit==='3'){
                unit= ' day(s)';  
            }
            else if(renewal.Unit==='4'){
                unit= ' week(s)';  
            }
            return 'every '+String(renewal.Frequency)+unit;
	};
}]);