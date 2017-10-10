app.controller('contactController', ['$scope','$http', function($scope, $http){
        
        // Load user details from server
    	$http.get('models/get_settings.php').success(function (response) {
    		$scope.user_details = response.user[0];
    	});
    	
    	$scope.send= function(){
            //alert($scope.user_details.google_email);
            //alert($scope.subject);
            //alert($scope.comments);
            
            var jsonMail={"from":$scope.user_details.google_email, "subject":$scope.subject,"comments":$scope.comments};
			jsonMail=JSON.stringify(jsonMail);
            var uri='models/send_form_email.php?msg='+jsonMail;
            //alert(uri);
            $http.post(uri).then(function (response) {

            });
            
            $scope.reset();
            alert('Thank you for contacting us.\r\nWe will be in touch with you very soon.')
        };
        
        $scope.reset= function(){
            $scope.subject='';
            $scope.comments='';
        };        
		
}]);