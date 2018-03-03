<?php class test_renewal{ 

 // database connection and table name 
    private $conn; 
    private $table_name = "test_renewals"; 
    
    // object properties 
    public $ID; 
    public $UserID; 
    public $ShopID; 
    public $ItemID; 
    public $CategoryID; 
    public $State; 
    public $Title; 
    public $ImageUrl; 
    public $Quantity;
    public $Views;
    public $Likes;
    public $LastUpdatedDate; 
    public $ExpiryDate; 
    
    public $ScheduledDateTime; 
    public $ScheduledDate; 
    public $ScheduledTime;     
    public $RenewalStatus; 
    public $UpdatedTimeStamp;
    public $TargetDateTime;
    public $LocalDateTime;
    
    public $Unit;
    public $Frequency;
    public $RenewType;    
    public $EndDate;
    public $EndTime;
    public $NumberOfItems;
    public $Sun;
    public $Mon;
    public $Tue;
    public $Wed;
    public $Thu;
    public $Fri;
    public $Sat;
    public $TargetTZ;
    public $LocalTZ;    
    
    // constructor with $db as database connection 
    public function __construct($db){ 
        $this->conn = $db;
    }
    
     // read products
    function readAll($userId, $shopId, $status){ 
        // select all query
        $query = "SELECT 
                    ID,'false' as selected,UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime
                  FROM " . $this->table_name . "
                  WHERE  RenewalStatus='".$status."'
                  AND UserID=". $userId ."
                  AND ShopID=". $shopId ."
                  ORDER BY 
                  ScheduledDate,ScheduledTime";
 
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
    
    function createNew(){        
       $query = "INSERT INTO " . $this->table_name . "
                (UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency,RenewType,EndDate,EndTime,NumberOfItems,Sun,Mon,Tue,Wed,Thu,Fri,Sat,TargetTZ,LocalTZ)
                 VALUES
                (:UserID,:ShopID,:ItemID,:Title,:ImageUrl,:Quantity,:Views,:Likes,:LastUpdatedDate,:ExpiryDate,:ScheduledDateTime,:ScheduledDate,:ScheduledTime,:RenewalStatus,:UpdatedTimeStamp,:TargetDateTime,:LocalDateTime,:Unit,:Frequency,:RenewType,:EndDate,:EndTime,:NumberOfItems,:Sun,:Mon,:Tue,:Wed,:Thu,:Fri,:Sat,:TargetTZ,:LocalTZ)";
         try{
            // prepare query            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':UserID', $this->UserID);
            $stmt->bindParam(':ShopID', $this->ShopID);
            $stmt->bindParam(':ItemID', $this->ItemID);
            $stmt->bindParam(':Title', $this->Title);
            $stmt->bindParam(':ImageUrl', $this->ImageUrl);
            $stmt->bindParam(':Quantity', $this->Quantity);
            $stmt->bindParam(':Views', $this->Views);
            $stmt->bindParam(':Likes', $this->Likes);
            $stmt->bindParam(':LastUpdatedDate', $this->LastUpdatedDate);
            $stmt->bindParam(':ExpiryDate', $this->ExpiryDate);            
            $stmt->bindParam(':ScheduledDateTime', $this->ScheduledDateTime);
            $stmt->bindParam(':ScheduledDate', $this->ScheduledDate);
            $stmt->bindParam(':ScheduledTime', $this->ScheduledTime);
            $stmt->bindParam(':RenewalStatus', $this->RenewalStatus);
            $stmt->bindParam(':UpdatedTimeStamp', $this->UpdatedTimeStamp);
            $stmt->bindParam(':TargetDateTime', $this->TargetDateTime);            
            $stmt->bindParam(':LocalDateTime', $this->LocalDateTime);            
            $stmt->bindParam(':Unit', $this->Unit);            
            $stmt->bindParam(':Frequency', $this->Frequency);            
            $stmt->bindParam(':RenewType', $this->RenewType); 
            $stmt->bindParam(':EndDate', $this->EndDate);
            $stmt->bindParam(':EndTime', $this->EndTime);
            $stmt->bindParam(':NumberOfItems', $this->NumberOfItems);
            $stmt->bindParam(':Sun', $this->Sun);
            $stmt->bindParam(':Mon', $this->Mon);
            $stmt->bindParam(':Tue', $this->Tue);
            $stmt->bindParam(':Wed', $this->Wed);
            $stmt->bindParam(':Thu', $this->Thu);
            $stmt->bindParam(':Fri', $this->Fri);
            $stmt->bindParam(':Sat', $this->Sat);
            $stmt->bindParam(':TargetTZ', $this->TargetTZ);
            $stmt->bindParam(':LocalTZ', $this->LocalTZ);
    
            // execute query
            $stmt->execute();
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    function update_random(){
    	try{
    		$query ="UPDATE " .$this->table_name ."
                    SET RenewalStatus='". $this->RenewalStatus ."',
                    ItemID='". $this->ItemID ."',
                    Title='". $this->Title ."',
                    ImageUrl='". $this->ImageUrl ."',
                    Quantity='". $this->Quantity ."',
                    Views='". $this->Views ."',
                    Likes='". $this->Likes ."',
                    LastUpdatedDate='". $this->LastUpdatedDate ."',
                    ExpiryDate='". $this->ExpiryDate ."',                    				
                    UpdatedTimeStamp='". $this->UpdatedTimeStamp ."',
                    RenewType='". $this->RenewType ."'
                    WHERE ID='". $this->ID ."'" ;
    		// prepare query
    		$stmt = $this->conn->prepare($query);
    		
    		//echo $query."</br>";
    		
    		// execute query
    		$stmt->execute();
    	}catch(PDOException $e){
    		echo $query . "<br>" . $e->getMessage();
    	}
    }
    
    function update_forever(){
    	try{
            $query ="UPDATE " .$this->table_name ."
                SET ScheduledDateTime='". $this->ScheduledDateTime ."',
                LocalDateTime='". $this->LocalDateTime ."',
                TargetDateTime='". $this->TargetDateTime ."',
                ScheduledDate='". $this->ScheduledDate ."',
                ScheduledTime='". $this->ScheduledTime ."',
                UpdatedTimeStamp='". $this->UpdatedTimeStamp ."'
                WHERE ID='". $this->ID ."'" ;
    		// prepare query
    		$stmt = $this->conn->prepare($query);
    		
    		//echo $query."</br>";
    		
    		// execute query
    		$stmt->execute();
    	}catch(PDOException $e){
    		echo $query . "<br>" . $e->getMessage();
    	}
    }
    
    function add_forever_completed($id){
        try{
        $query="INSERT INTO " .$this->table_name ."(UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency,RenewType
                ,EndDate,EndTIme,NumberOfItems,Sun,Mon,Tue,Wed,Thu,Fri,Sat,TargetTz,LocalTz)
                SELECT UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,'R','". date('Y-m-d H:i:s') ."',TargetDateTime,LocalDateTime,Unit,Frequency,RenewType
                ,EndDate,EndTIme,NumberOfItems,Sun,Mon,Tue,Wed,Thu,Fri,Sat,TargetTz,LocalTz
                FROM " .$this->table_name ."
                WHERE ID='". $id ."'" ;
    		$stmt = $this->conn->prepare($query);    		
    		$stmt->execute();
    	}catch(PDOException $e){
    		echo $query . "<br>" . $e->getMessage();
    	}
    }
    
     function add_scheduled_item($id, $Item_id, $title, $image_url, $quantity, $views, $likes, $last_updated_date, $expiry_date ){
        try{
        $query="INSERT INTO " .$this->table_name ."(UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency,RenewType
                ,EndDate,EndTIme,NumberOfItems,Sun,Mon,Tue,Wed,Thu,Fri,Sat,TargetTz,LocalTz)
                SELECT UserID,ShopID,'". $Item_id."','". $title."','". $image_url."','". $quantity."','". $views."','". $likes."','". $last_updated_date->format('Y-m-d H:i:s')."','". $expiry_date->format('Y-m-d H:i:s')."'
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,'R','". date('Y-m-d H:i:s') ."',TargetDateTime,LocalDateTime,Unit,Frequency,RenewType
                ,EndDate,EndTIme,NumberOfItems,Sun,Mon,Tue,Wed,Thu,Fri,Sat,TargetTz,LocalTz
                FROM " .$this->table_name ."
                WHERE ID='". $id ."'" ;
    		$stmt = $this->conn->prepare($query);
    		$stmt->execute();
    	}catch(PDOException $e){
    		echo $query . "<br>" . $e->getMessage();
    	}
    }
    
    function update($status, $id){//should be update_status
        try{            
            $query ="UPDATE " .$this->table_name ."
                    SET RenewalStatus='". $status ."',
                    UpdatedTimeStamp='". date('Y-m-d H:i:s') ."' 
                    WHERE ID='". $id ."'" ;
            // prepare query
            $stmt = $this->conn->prepare($query); 
            // execute query
            $stmt->execute(); 
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    function get_scheduled_items(){
        // select all query
        $query = "SELECT 
                    r.ID, r.UserID, r.ItemID, r.Title, e.access_token, e.access_token_secret, e.shop_id, r.ScheduledDateTime, r.ScheduledDate, r.ScheduledTime,
                    r.RenewalStatus, u.expiry_date, r.Unit, r.Frequency, r.RenewType, r.TargetDateTime, r.LocalDateTime,
                    r.EndDate, r.EndTime, r.NumberOfItems, r.Sun, r.Mon, r.Tue, r.Wed, r.Thu, r.Fri, r.Sat, r.TargetTZ, r.LocalTZ
                  FROM " . $this->table_name . " r 
                      inner join etsy_users  e on r.ShopID = e.shop_id and r.UserID = e.google_id
                      inner join users u on r.UserID = u.google_id
                  WHERE  (RenewalStatus='S' OR RenewalStatus='F')
                  AND r.ScheduledDateTime<=Now() 
                  AND r.ScheduledDateTime>=DATE_SUB(Now(),INTERVAL 2 hour)
                  AND u.expiry_date>=Now()
                  AND (r.Unit='d' OR
                       (DAYOFWEEK(CURRENT_DATE())=1 AND r.Sun='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=2 AND r.Mon='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=3 AND r.Tue='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=4 AND r.Wed='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=5 AND r.Thu='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=6 AND r.Fri='Y') OR
                       (DAYOFWEEK(CURRENT_DATE())=7 AND r.Sat='Y'))
                  ORDER BY ScheduledDateTime";
                          
        //echo($query);
        
        //AND (date(ScheduledDate)<'".date('Y-m-d')."'
        //OR (date(ScheduledDate)='".date('Y-m-d')."'
        //AND time(ScheduledTime)<='".date('H:i:s')."'))
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
    
    function read_all_renewals($month){
        // select all query
        $query = "SELECT r.ID, r.ImageUrl, r.Title, r.ScheduledDateTime, r.UpdatedTimeStamp, r.TargetDateTime, r.LocalDateTime, r.RenewalStatus, e.shop_name, r.RenewType, e.login_name
                  FROM " . $this->table_name . " r 
                  inner join etsy_users  e on r.ShopID = e.shop_id and r.UserID = e.google_id
                  WHERE  Month(ScheduledDateTime)=".$month."
                  ORDER BY r.RenewalStatus DESC, r.ScheduledDateTime";
        
        //echo($query);
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
    
    function delete_all_scheduled($user_id, $shop_id){
    	       
        try{            
            $query ="UPDATE " .$this->table_name ."
                    SET RenewalStatus='C', 
                    UpdatedTimeStamp='". date('Y-m-d H:i:s') ."' 
                    WHERE UserID=". $user_id ." 
                    AND ShopID=". $shop_id ."
                    AND (RenewalStatus='S' OR RenewalStatus='F') ";
            
            echo $query;
            // prepare query
            $stmt = $this->conn->prepare($query); 
            // execute query
            $stmt->execute(); 
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }    
	
    function read_completed($userId, $shopId, $month){
    	// select all query
    	$query = "SELECT
                    ID,'false' as selected,UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,RenewType
                  FROM " . $this->table_name . "
                  WHERE  RenewalStatus='R'
                  AND UserID=". $userId ."
                  AND ShopID=". $shopId ."
                  AND Month(ScheduledDateTime)=".$month."
                  ORDER BY ScheduledDateTime DESC";
    
    	// prepare query statement
    	$stmt = $this->conn->prepare( $query );
    	// execute query
    	$stmt->execute();
    	return $stmt;
    }
    
    function read_scheduled($userId, $shopId){ 
        // select all query
        $query = "SELECT 
                    ID,'false' as selected,UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency
                  FROM " . $this->table_name . "
                  WHERE  (RenewalStatus='S' OR RenewalStatus='F')
                  AND UserID=". $userId ."
                  AND ShopID=". $shopId ."
                  ORDER BY ScheduledDateTime";                  
 
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
    
    function delete_all_renewals(){
        
        $query ="Delete from " .$this->table_name;
            
        echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();        
    }
}