<?php
/**
 * Description of user
 *
 * @author Sivan
 */
class myUser {
    private $conn; 
    private $table_name = "users"; 
    // object properties
    public $google_id;
    public $google_name;
    public $google_email;
    public $google_link;
    public $google_picture_link;
    public $expiry_date;
    public $created_date;   

    // constructor with $db as database connection 
    public function __construct($db){ 
        $this->conn = $db;
    }
    
    function createNew(){ 
        
        $query = "INSERT INTO " . $this->table_name . "
                        (google_id, google_name, google_email, google_link, google_picture_link,expiry_date,created_date) 
                        VALUES (:google_id, :google_name, :google_email, :google_link, :google_picture_link,:expiry_date,:created_date)";
         try{               
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':google_id', $this->google_id);
            $stmt->bindParam(':google_name', $this->google_name);
            $stmt->bindParam(':google_email', $this->google_email);
            $stmt->bindParam(':google_link', $this->google_link);
            $stmt->bindParam(':google_picture_link', $this->google_picture_link);
            
            $dt = new DateTime();
            $interval = new DateInterval('P1M'); 
            $dt->add($interval);
            $this->expiry_date=$dt->format('Y-m-d H:i:s');
            $this->created_date=date('Y-m-d H:i:s');
            
            $stmt->bindParam(':expiry_date', $this->expiry_date);            
            $stmt->bindParam(':created_date', $this->created_date); 
            /*
            echo 'id:'.$this->google_id;
            echo 'name:!'.$this->google_name;
            echo 'email:'.$this->google_email;
            echo 'link:'.$this->google_link;
            echo 'picture:'.$this->google_picture_link;
            echo 'expiry:'.$this->expiry_date;
            echo ':!'.$this->created_date;
             * 
             */
            // execute query
            $stmt->execute();
            echo 'created!'.$this->google_id;
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    function getCount(){
        $query ="SELECT google_id FROM " . $this->table_name . " WHERE google_id=".$this->google_id;
        // prepare query statement
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();     
        return $stmt->rowCount();        
    }
    
    function getUserDetails($userId){ 
        // select all query
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE google_id=". $userId;
 
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
    
    function update_expiry_date(){ 
        
        $query="UPDATE ". $this->table_name . "
            SET expiry_date='".$this->expiry_date . "'
            WHERE google_id=" .$this->google_id;
        try{
            // prepare query statement
            $stmt = $this->conn->prepare( $query ); 
            //$stmt->bindParam(':google_id', $this->google_id);
            //$stmt->bindParam(':expiry_date', $this->expiry_date);
            // execute query
            $stmt->execute();  
            echo $stmt->rowCount() . " records UPDATED successfully";
            echo 'updated!'.$this->expiry_date;
            
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }        
    }
    
    function read_all_users(){
    	// select all query
    	$query = "SELECT users.google_id,google_name,google_link,shop_name,login_name,title,google_email,google_picture_link,expiry_date,users.created_date,icon_url,shop_url
				  FROM users left join etsy_users on users.google_id=etsy_users.google_id
    			  ORDER BY users.created_date DESC";    
    	//echo($query);
    
    	// prepare query statement
    	$stmt = $this->conn->prepare( $query );
    	// execute query
    	$stmt->execute();
    	return $stmt;
    }
}

