app.controller('settingsController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
    
    if(!$rootScope.user_details && !$rootScope.etsy_shops){
		
    	// Load user details from server
    	$http.get('models/get_settings.php').success(function (response) {
    		$rootScope.user_details = response.user;
    	});
        
        $http.get('models/get_etsy_shops.php').success(function (response) {
            $rootScope.etsy_shops = response.shops;
            //set default shop
            for(var i=0;i<response.shops.length;i++){
                if(response.shops[i]['is_default']==='1'){
                    $scope.select(response.shops[i]);
                }
            }
        });
    }
    
    $scope.select = function (shop) {
        var uri = 'models/set_current_shop.php?google_id=' + shop.google_id + '&user_id=' + shop.user_id + '&shop_id=' + shop.shop_id;
        $http.get(uri).success(function (response) {
            $rootScope.myListings = null;
            $rootScope.shopImgUrl = response.user.shop_icon_url;
        });
        for(var i=0;i<$rootScope.etsy_shops.length;i++){
            $rootScope.etsy_shops[i]['is_default']=$rootScope.etsy_shops[i]['shop_id']===shop.shop_id?1:0;
        }
    };
    
    $scope.remove = function (shop) {
        if(window.confirm('Click OK to remove shop: ' + shop.title)){  
            var uri = 'models/delete_shop.php?user_id=' + shop.user_id+ '&shop_id=' + shop.shop_id;
            $http.get(uri).success(function (response) {
                $scope.message = response;
            });
            $scope.etsy_shops.splice( $scope.etsy_shops.indexOf(shop), 1 );	
        }
    };
}]);
