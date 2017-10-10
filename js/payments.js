app.controller('paymentsController', ['$scope','$http', '$rootScope','$sce', function($scope, $http, $rootScope, $sce){
    
    $scope.months=[
        { "value":"1" , "text":"one month" },
        { "value":"2" , "text":"two months" },
        { "value":"3" , "text":"three months" },
        { "value":"4" , "text":"four months" },
        { "value":"5" , "text":"five months" },
        { "value":"6" , "text":"six months" }];
    
    $scope.myMonth = $scope.months[0]; //ome month
    
    $scope.calcAmount=function(){
        var months = parseInt($scope.myMonth.value);
        angular.element('#extention-amount').html("$"+(months*4).toLocaleString('en-US', { minimumFractionDigits: 2 })+" USD"); 
        $scope.toDate=$scope.calcFullMonth($scope.fromDate, months);
        $scope.expressCheckout();
    };
    
    // Load payment history from server
    $http.get('models/get_payments.php').success(function (response) {
    	$scope.payments = response.payments;
    }); 
    
    // Load user details from server
    $http.get('models/get_settings.php').success(function (response) {
    	$scope.user_details = response.user;
    	$scope.expiryDate=new Date($scope.user_details[0].expiry_date.replace(' ','T'));
        $scope.fromDate=$scope.expiryDate>new Date()?new Date($scope.expiryDate.getTime()+86400000):new Date();
        $scope.toDate=$scope.calcFullMonth($scope.fromDate, 1);
    	$scope.calcAmount();
    });
    
    $scope.makePayment=function(){        
        var months = parseInt($scope.myMonth.value);
        var dscr=$scope.myMonth.text+" service fee";
        var amount=months*4.00; 
        var startDate=$scope.fromDate.getFullYear()+'-'+($scope.fromDate.getMonth()+1)+'-'+$scope.fromDate.getDate()+' 00:00:00.000';
        var endDate=$scope.toDate.getFullYear()+'-'+($scope.toDate.getMonth()+1)+'-'+$scope.toDate.getDate()+' 23:59:00.000';
        var uri = 'models/add_payment.php?dscr='+dscr+'&months='+months+'&amount='+amount+ '&start_date='+startDate+'&end_date='+ endDate;
        $http.get(uri).success(function (response) {
            $scope.message = response;
            $http.get('models/update_expiry_date.php?end_date='+endDate).success(function () {                
            }); 
        });  
    };
    
    $scope.calcFullMonth=function(startDate, numOfMonths) {        
        //copy the date        
        var dt = new Date(startDate);
        dt.setMonth(dt.getMonth() + numOfMonths);
        return dt;
    };  
    
    $scope.expressCheckout=function(){
        var months = parseInt($scope.myMonth.value);
        var dscr=$scope.myMonth.text+" service fee";
        var amount=months*4.00; 
        var startDate=$scope.fromDate.getFullYear()+'-'+(($scope.fromDate.getMonth()+1)<10?'0':'') +($scope.fromDate.getMonth()+1)+'-'+($scope.fromDate.getDate()<10?'0':'')+$scope.fromDate.getDate()+' 00:00:00.000';
        var endDate=$scope.toDate.getFullYear()+'-'+(($scope.toDate.getMonth()+1)<10?'0':'')+($scope.toDate.getMonth()+1)+'-'+($scope.toDate.getDate()<10?'0':'')+$scope.toDate.getDate()+' 23:59:00.000';
        var uri = 'models/expresscheckout.php?dscr='+dscr+'&months='+months+'&amount='+amount+ '&start_date='+startDate+'&end_date='+ endDate;
        $scope.actionUrl = uri;
        $scope.formAction = $sce.trustAsUrl(uri);
    };
    
}]);
