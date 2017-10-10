app.controller('listingsController', ['$scope','$http', '$rootScope', function($scope, $http, $rootScope){
     
    var sortBy = function(field, reverse, primer){
        
        var key = function (x) {return primer ? primer(x[field]) : new Date(x[field].replace(' ','T')).getTime()};
                    
        reverse = !reverse ? 1 : -1;

        return function (a, b) {
            return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
        } 
    }
	
    $scope.sortListings= function(field){
    	
        if($rootScope.orderBy===field){        	
            $rootScope.ascending=!$rootScope.ascending;
        }
        else{
            $rootScope.orderBy=field;
            $rootScope.ascending=false;
        }                      
        $rootScope.myListings.sort(sortBy(field, $rootScope.ascending, field==='ExpiryDate'?undefined:parseFloat));		
    };
    
    $scope.sortListings2=function(){
        var opt=parseInt($scope.sortByItem);
        switch (opt) {
            case 1:
                $rootScope.orderBy="ExpiryDate";
                $rootScope.ascending=false;
                break;
            case 2:
                $rootScope.orderBy="ExpiryDate";
                $rootScope.ascending=true;
                break;
            case 3:
                $rootScope.orderBy="Price";
                $rootScope.ascending=false;    
                break;
            case 4:
                $rootScope.orderBy="Price";
                $rootScope.ascending=true;
                break;
            case 5:
                $rootScope.orderBy="Views";
                $rootScope.ascending=false;
                break;
            case 6:
                $rootScope.orderBy="Views";
                $rootScope.ascending=true;
                break;
            case 7:
                $rootScope.orderBy="Likes";
                $rootScope.ascending=false;
                break;  
            case 8:
                $rootScope.orderBy="Likes";
                $rootScope.ascending=true;
                break;
            default:
        }
        $rootScope.myListings.sort(sortBy($rootScope.orderBy, $rootScope.ascending, $rootScope.orderBy==='ExpiryDate'?undefined:parseFloat));
    };
    
    $scope.loadListing= function(){
        alert($rootScope.myListings.length);
    };
    
    $scope.clearAll=function(){    	
    	var i = $rootScope.myListings.length;
    	while (i--){
    	    if ($rootScope.myListings[i].Title==="Random Item"){
    	        $rootScope.myListings.splice(i, 1);
    	    }
    	    else{
    	    	$rootScope.myListings[i].selected=false;
    	    }
    	}
    	/*
    	angular.forEach($rootScope.myListings, function(listing) {
    		$scope.randoms = [];
            listing.selected=false; 
            if(listing.Title==="Random Item"){
            	$scope.randoms.push($rootScope.myListings.indexOf(listing));
        	}
        });
    	*/
    	$scope.renewMessage();
    };
		
    $scope.removeRandom=function(listing){
    	if(listing.Title==="Random Item"){
			var index=$rootScope.myListings.indexOf(listing);
			$rootScope.myListings.splice(index,1);
		}
    	$scope.renewMessage();
    };
    
    $scope.removeSelected=function(myImg){
    	//alert(myImg.listing.ItemID);
    	angular.forEach($rootScope.myListings, function(listing) {
    		if(listing.ItemID===myImg.listing.ItemID){ //if(listing.ImageUrl===myImg.listing.ImageUrl){
    			//alert(listing.Title);
    			if(listing.Title==="Random Item"){
    				var index=$rootScope.myListings.indexOf(listing);
    				$rootScope.myListings.splice(index,1);
    			}
    			else{
    				listing.selected=false
    			}; 
            }
        });
    	$scope.renewMessage();
    };

    $scope.pickRandom=function(){
    	/*
    	var rnd = Math.floor((Math.random() * $rootScope.myListings.length) + 1);
    	$rootScope.myListings[rnd-1].selected=true;
    	$scope.renewMessage();
    	*/
    	var rnd = Math.floor((Math.random() * 99999)*(-1));
    	$scope.randomItem={"num":$rootScope.myListings.length,"UserID":$rootScope.myListings[0].UserID,"ShopID":$rootScope.myListings[0].ShopID,"ItemID":rnd,"selected":true,"Title":"Random Item","Quantity":"0","Views":"0","Likes":"0","Price":"0.00","LastUpdatedDate":null,"ExpiryDate":null,"ImageUrl":"images/imagebot.png","ScheduledDateTime":"","ScheduledDate":"","ScheduledTime":"","TargetDateTime":"","LocalDateTime":"","RenewalStatus":""};
    	$rootScope.myListings.push($scope.randomItem);
    	$scope.renewMessage();
    	
    };
    
    $scope.quickPick=function(){    	
    	var rnd = Math.floor((Math.random() * $rootScope.myListings.length) + 1);
    	$rootScope.myListings[rnd-1].selected=true;
    	$scope.renewMessage();
    };
    
    $scope.getCssClass=function(dt){
        //e.g. - "2011-08-03 09:15:11"
        var a=dt.split(" ");
        var d=a[0].split("-");
        var t=a[1].split(":");
        var dt = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
    	var dt1=new Date(dt).getTime();
        var dt2= new Date().getTime();
        //console.log((dt1-dt2)/(60*60*24*1000));
        return Math.round((dt1-dt2)/(60*60*24*1000))<8?'exipred-listing':'';
    };
    
    $scope.selectItem=function(listing){
    	listing.selected=!listing.selected;
    	//alert(listing.Title);
    	if(listing.Title==="Random Item" && !listing.selected){
            $scope.removeRandom(listing);
    	}
    	$scope.renewMessage();
    };
    
    $scope.renewMessage=function(){
    	var i=0;
    	angular.forEach($rootScope.myListings, function(listing) {
            if(listing.selected===true){
                i++;
            }    	
    	});
    	var t=$scope.repeatOption==1?1:$scope.repeatedTimes;    
    	$rootScope.selectionCost=(i * t * 2 /10).toLocaleString('en-US', { minimumFractionDigits: 2 });    	//new Intl.NumberFormat("en-US",{ style: "currency", currency: "USD" }).format(i * .2 * t);//
    	$rootScope.selectionCount=i*t;
        $rootScope.itemsCount=i>0?i:"";
    	//$scope.showMessage=i>0;
        document.getElementById("renew-msg").style.visibility = (i > 0)?"visible":"hidden";
    };
    
    $scope.schedule=function(){

    		if (validateSchedule()===false) {
    			window.alert('Scheduled date/time must be later than selected time-zone current date/time.');
    		}
    		else{   
	            setTimeZone();
                    var rec = getRecurrence();
	            var jsonStr = '{"renewals":[],'+rec+'}';
	            var obj = JSON.parse(jsonStr);
	            //alert(jsonStr);
	            $rootScope.i=0;
	            angular.forEach($scope.myListings, function(listing) {
	                if(listing.selected){
	                    
	                    var jobj = (JSON.parse(JSON.stringify(listing)));
                            //alert(jobj.ItemID);//(angular.element('#target-dateTime').html());
	                    jobj.Title=encodeURIComponent(jobj.Title);
	                    jobj.ScheduledDateTime=angular.element('#server-date').html()+ ' '+angular.element('#server-time').html();
	                    jobj.ScheduledDate=angular.element('#server-date').html();
	                    jobj.ScheduledTime=angular.element('#server-time').html();  
	                    jobj.TargetDateTime=encodeURIComponent(angular.element('#target-dateTime').html());  
	                    jobj.LocalDateTime=angular.element('#local-dateTime').html(); 
	                    jobj.RenewalStatus=($scope.repeatOption==1?"S":$scope.repeatedTimes>1?"S":"F");   
                            //alert(jobj.RenewalStatus);
	                    obj['renewals'].push(jobj);
	                    $rootScope.i++;
	                }
	            });
                    if($rootScope.i===0){
                        alert("No listings were selected for renewal.");
                    }
	            else if($rootScope.i>0){
	                jsonStr = JSON.stringify(obj);	            
	                var uri='models/add_renewals.php?listing='+jsonStr;
	                //alert(uri);
                        var status="";
	                $http.get(uri).then(function (response) {
                                status=response.data.status;
	                	if (status==='expired'){
                                    alert('Your auto renewal service expires on "'+response.data.expiry_date+'"\r\nPlease go to SETTINGS to extend your service.\r\nThank you.');
                                    $scope.reset();
		                }
                                else{
                                    angular.element('#dialog').html("<p>You've scheduled " + ($rootScope.selectionCount===undefined?$rootScope.i:$rootScope.selectionCount)+ " item(s) for renewal.</p>");
                                    angular.element('#dialog').show();
                                    window.setTimeout(function(){angular.element('#dialog').hide();}, 6000); 
                                    $scope.reset();
                                }
	                });
	            }
	        };   
    	}
        
    angular.element(document).ready(function () {
        // Load products from server
        if(!$rootScope.myListings || $rootScope.myListings===null || $rootScope.myListings===undefined ){
            $scope.loading = true;
            $http.get('models/get_listings.php').success(function (response) {
                $rootScope.myListings = response.listings;  
                $scope.loading = false;
                $rootScope.orderBy="ExpiryDate";
                $rootScope.ascending=false;
            });
            
            $http.get('models/get_sections.php').success(function (response) {
                $rootScope.mySections = response.sections;  
            });
        }
    });
}]);