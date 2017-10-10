app.controller('completedController', ['$scope','$http', function($scope, $http ){
		
    $scope.loadCompleted= function(mm){
        $scope.loading = true;
        $scope.myCompleted = undefined;
        $scope.etsyFee = "$0.00";
        $http.get('models/get_completed.php?month='+mm)
            .success(function (response) {
                $scope.myCompleted = response.renewals;
                $scope.etsyFee = ($scope.myCompleted.length*2/10).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });//new Intl.NumberFormat("en-US",{ style: "currency", currency: "USD" }).format($scope.myCompleted.length*.2);
            })
	    .error(function() {
	        $scope.myCompleted = undefined;
                $scope.etsyFee = "$0.00";
	    });
	    $scope.loading = false;
    };
	
    var dt = new Date();
    $scope.loadCompleted(parseInt(dt.getMonth())+1);	
	
    var dt = new Date();
    var jsonObj = [];
    var i=1;
    var item = {}
    var period = new Date(Date.parse(dt,"yy/mm/dd")).toString();
    
    item ["name"] = period.substring(4, 7)+' ' +period.substring(11,15);
    item ["date"] = (JSON.parse(JSON.stringify(dt.toDateString())));
    jsonObj.push(item);
    do{
        item = {}
        period = new Date(dt.setMonth(dt.getMonth()-1)).toString('yyyy-mm');
        item ["name"] = period.substring(4, 7)+' ' +period.substring(11,15);
        item ["date"] = (JSON.parse(JSON.stringify(dt.toDateString())));
        jsonObj.push(item);
        i++;
    }while(i<6);        
        
    $scope.periods=jsonObj;   
    $scope.myPeriod = $scope.periods[0];
    
    $scope.update = function(){
    	var dt = new Date($scope.myPeriod.date);
    	$scope.loadCompleted(parseInt(dt.getMonth())+1);
    };
    
}]);