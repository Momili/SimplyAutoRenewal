
app.controller('tileListingsController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
                
	// Load products from server
	if(!$rootScope.myListings){
            $http.get('data/listings.json').success(function (response) {
            $rootScope.myListings = response.listings;
            $scope.myDate=$scope.currentDate();
            $scope.myTime=$scope.currentTime();
	});
}
        $scope.reset=function(){
            angular.forEach($scope.myListings, function(listing) {
            listing.selected=false;
            $scope.myDate=$scope.currentDate();
            $scope.myTime=$scope.currentTime();
        });};
		
	$scope.removeSelected=function(myImg){
            angular.forEach($scope.myListings, function(listing) {
            if(listing.ImageUrl===myImg.listing.ImageUrl){
               listing.selected=false; 
            }
        });};
        
    $scope.schedule=function(){
            var jsonStr = '{"renewals":[]}';
            //var jsonStr = '{[]}';
            var obj = JSON.parse(jsonStr);
            //alert(jsonStr);
            angular.forEach($scope.myListings, function(listing) {
                if(listing.selected){
                    //alert(listing.ItemID);
                    var jobj = (JSON.parse(JSON.stringify(listing)));
                    jobj.Title=encodeURIComponent(jobj.Title);
                    jobj.ScheduledDate=$scope.myDate;
                    jobj.ScheduledTime=$scope.myTime;  
                    jobj.RenewalStatus="S";               
                    obj['renewals'].push(jobj);
                }
            });
            jsonStr = JSON.stringify(obj);
            
            var uri='models/add_renewals.php?listing='+jsonStr;
            //alert(uri);        
            $http.get(uri).success(function (response) {
                //alert(response);
                $rootScope.message = response;
            });     
            $scope.reset();
        };
    
    $scope.currentDate=function(){
        var d = new Date();
        return d.getFullYear()+'/'+(d.getMonth()+1)+'/'+d.getDate();
    };
    
    $scope.currentTime=function(){
        var d = new Date();
        return d.getHours()+':'+(d.getMinutes()+1)+":"+d.getSeconds();
    };
    
}]);