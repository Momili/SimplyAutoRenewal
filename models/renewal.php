<?php class renewal{ 
    
    // database connection and table name 
    private $conn; 
    private $table_name = "renewals"; 
    
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
    
    // create product
    function createNew(){        
       $query = "INSERT INTO " . $this->table_name . "
                (UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency,RenewType)
                 VALUES
                (:UserID,:ShopID,:ItemID,:Title,:ImageUrl,:Quantity,:Views,:Likes,:LastUpdatedDate,:ExpiryDate,:ScheduledDateTime,:ScheduledDate,:ScheduledTime,:RenewalStatus,:UpdatedTimeStamp,:TargetDateTime,:LocalDateTime,:Unit,:Frequency,:RenewType)";
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
            $this->UpdatedTimeStamp=date('Y-m-d H:i:s');
            $stmt->bindParam(':UpdatedTimeStamp', $this->UpdatedTimeStamp);
            $stmt->bindParam(':TargetDateTime', $this->TargetDateTime);            
            $stmt->bindParam(':LocalDateTime', $this->LocalDateTime);            
            $stmt->bindParam(':Unit', $this->Unit);            
            $stmt->bindParam(':Frequency', $this->Frequency);            
            $stmt->bindParam(':RenewType', $this->RenewType);                        
            
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
    		
    		echo $query."</br>";
    		
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
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,RenewalStatus,UpdatedTimeStamp,TargetDateTime,LocalDateTime,Unit,Frequency,RenewType)
                SELECT UserID,ShopID,ItemID,Title,ImageUrl,Quantity,Views,Likes,LastUpdatedDate,ExpiryDate
                ,ScheduledDateTime,ScheduledDate,ScheduledTime,'R','". date('Y-m-d H:i:s') ."',TargetDateTime,LocalDateTime,Unit,Frequency,RenewType
                FROM " .$this->table_name ."
                WHERE ID='". $id ."'" ;
                // prepare query
    		$stmt = $this->conn->prepare($query);
    		
                //echo "Add forever completed"."</br>";
    		//echo $query."</br>";
    		
    		// execute query
    		$stmt->execute();
    	}catch(PDOException $e){
    		echo $query . "<br>" . $e->getMessage();
    	}
    }
    
    function update($status, $id){
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
                    r.ID, r.UserID, r.ItemID, r.Title, e.access_token, e.access_token_secret, e.shop_id, r.ScheduledDateTime, r.ScheduledDate, r.ScheduledTime, r.RenewalStatus, u.expiry_date, r.Unit, r.Frequency, r.RenewType, r.TargetDateTime, r.LocalDateTime
                  FROM " . $this->table_name . " r 
                      inner join etsy_users  e on r.ShopID = e.shop_id and r.UserID = e.google_id
                      inner join users u on r.UserID = u.google_id
                  WHERE  (RenewalStatus='S' OR RenewalStatus='F')
                  AND r.ScheduledDateTime<=Now()
                  AND u.expiry_date>=Now()
                  ORDER BY ScheduledDateTime";
                          
        echo($query);
        
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

}