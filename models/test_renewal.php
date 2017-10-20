<?php 
        
    
    class test_renewal{ 

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
}