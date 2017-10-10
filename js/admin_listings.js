app.controller('adminListingsController', ['$scope','$http', '$routeParams', function($scope, $http, $routeParams){

    var sortBy = function(field, reverse, primer){
        
        var key = function (x) {return primer ? primer(x[field]) : new Date(x[field].replace(' ','T')).getTime()};
                    
        reverse = !reverse ? 1 : -1;

        return function (a, b) {
            return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
        } 
    }
	
    $scope.sortListings= function(field){
    	
        if($scope.orderBy===field){        	
            $scope.ascending=!$scope.ascending;
        }
        else{
            $scope.orderBy=field;
            $scope.ascending=false;
        }                      
        $scope.myListings.sort(sortBy(field, $scope.ascending, field==='ExpiryDate'?undefined:parseFloat));		
    };
    
    $scope.getCssClass=function(dt){
    	var dt1=new Date(dt).getTime();
        var dt2= new Date().getTime();
        //console.log((dt1-dt2)/(60*60*24*1000));
        return Math.round((dt1-dt2)/(60*60*24*1000))<8?'exipred-listing':'';
    };
    
    
    // Load products from server
    $scope.shopName=$routeParams.shopname;    
    $scope.loading = true;
    var url='models/get_admin_listings.php?shopname='+$scope.shopName;
    //alert(url);
    $http.get(url).then(function (response) {
        //alert(response);
        $scope.myListings = response.data.listings;  
        $scope.loading = false;
        $scope.orderBy="ExpiryDate";
        $scope.ascending=false;            
    });	
    
}]);
