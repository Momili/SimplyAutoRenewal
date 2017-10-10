app.controller('successController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){   
    
	// Load user details from server
	$http.get('models/get_settings.php').success(function (response) {
		$scope.user_details = response.user;
	});
	
   	// Load payment details from server
   	$http.get('models/success.php').success(function (response) {
   		$scope.start_date=new Date(response.payment.start_date.replace(' ',"T"));
        $scope.end_date=new Date(response.payment.end_date.replace(' ',"T"));
        $scope.payment = response.payment;
   	});    
   	
   	$scope.pay=function(){  
        $http.get('models/confirm_payment.php').success(function (response) {
            $scope.message = response;
            if($scope.message==='SUCCESS'){
            	$scope.makePayment();
            }else{
            	alert('We\'re sorry, something went wrong.\r\n Please try again.');
                //local
                window.location.href = "http://localhost:8080/SimplyAutoRenewal/main.html#/";
                //
                /*bluehost
                window.location.href ="http://www.simplyautorenewal.com/main.html#/";
                */
            }
        });  
    };   
   	
    $scope.cancel=function(){ 
        //local
    	window.location.href = "http://localhost:8080/SimplyAutoRenewal/main.html#/";
        //
        /* bluehost
         window.location.href ="http://www.simplyautorenewal.com/main.html#/"; 
        */
    };   
    
    $scope.makePayment=function(){        
        var months = parseInt($scope.payment.months);
        var dscr=$scope.payment.description;
        var amount=months*4.00; 
        var startDate=$scope.start_date.getFullYear()+'-'+($scope.start_date.getMonth()+1)+'-'+$scope.start_date.getDate()+' 00:00:00.000';
        var endDate=$scope.end_date.getFullYear()+'-'+($scope.end_date.getMonth()+1)+'-'+$scope.end_date.getDate()+' 23:59:00.000';
        var uri = 'models/add_payment.php?dscr='+dscr+'&months='+months+'&amount='+amount+ '&start_date='+startDate+'&end_date='+ endDate;
        //alert(uri);
        $http.get(uri).success(function (response) {
            $scope.message = response;
            $http.get('models/update_expiry_date.php?end_date='+endDate).success(function () { 
            	alert('THANK YOU!\r\n Your payment has been accepted.');
                //local
                window.location.href = "http://localhost:8080/SimplyAutoRenewal/main.html#/";
                //
                /*
                window.location.href ="http://www.simplyautorenewal.com/main.html#/"; 
                */
            }); 
        });  
    };
   
}]);