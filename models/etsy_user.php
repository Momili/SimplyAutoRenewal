<?php
/**
 * Description of etsy_user
 *
 * @author Sivan
 */
class etsy_user {
    //put your code here
    private $conn; 
    private $table_name = "etsy_users"; 
    // object properties
    public $google_id;
    public $user_id;
    public $login_name;
    public $shop_name;
    public $shop_id;
    public $shop_url;
    public $title;
    public $access_token;
    public $access_token_secret;
    public $banner_url;
    public $icon_url;
    public $created_date;
    public $is_default;
    public $status;

    // constructor with $db as database connection 
    public function __construct($db){ 
        $this->conn = $db;
    } 
    
    function create_new(){ 
        
        $query = "INSERT INTO " . $this->table_name . "
                        ( google_id,  user_id,  login_name,  shop_id,  shop_name,  shop_url,  title,  access_token,  access_token_secret,  created_date,  is_default, banner_url, icon_url,status) 
                 VALUES (:google_id, :user_id, :login_name, :shop_id, :shop_name, :shop_url, :title, :access_token, :access_token_secret, :created_date, :is_default, :banner_url, :icon_url,:status)";
         try{               
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':google_id', $this->google_id);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':login_name', $this->login_name);
            $stmt->bindParam(':shop_id', $this->shop_id);
            $stmt->bindParam(':shop_name', $this->shop_name);
            $stmt->bindParam(':shop_url', $this->shop_url);
            $stmt->bindParam(':title', $this->title);            
            $stmt->bindParam(':access_token', $this->access_token);            
            $stmt->bindParam(':access_token_secret', $this->access_token_secret);  
            $stmt->bindParam(':is_default', $this->is_default); 
            $stmt->bindParam(':status', $this->status); 
            $stmt->bindParam(':banner_url', $this->banner_url); 
            $stmt->bindParam(':icon_url', $this->icon_url); 
            $stmt->bindParam(':created_date', date('Y-m-d H:i:s')); 
            // execute query
            $stmt->execute();
            print 'created!'.$this->google_id;
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    function get_user_details($google_id){ 
        // select all query
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE google_id=". $google_id ." 
                  AND status='A'" ."
                  ORDER BY title";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute(); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    function get_count($google_id, $etsy_user_id, $shop_id){
        $query ="SELECT google_id FROM " . $this->table_name 
             . " WHERE google_id=".$google_id
             . " AND user_id=".$etsy_user_id
             . " AND shop_id=".$shop_id
            .  " AND status='A'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();     
        return $stmt->rowCount();       
    }
    
    function reset_default($google_id){
        $query='update '. $this->table_name
             . ' set is_default=0'
             . ' where google_id='.$google_id;
        
        // prepare query statement
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();
    }
    
    function set_default($google_id, $user_id, $shop_id){
        $query="update ". $this->table_name
             . " set is_default=1"
             . " where google_id=".$google_id
             . " and user_id=".$user_id
             . " and shop_id=".$shop_id
             . " and status='A'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();
    }
    
    function get_shop_details($google_id, $user_id, $shop_id){ 
        // select all query
        $query = "SELECT *
                  FROM " . $this->table_name 
              . " where google_id=".$google_id
              . " and user_id=".$user_id
              . " and shop_id=".$shop_id
              . " and status='A'" 
              . " ORDER BY title";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute(); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    function remove_shop($user_id, $shop_id){
        $query="update ". $this->table_name
             . " set status='I'"
             . " where user_id=".$user_id
             . " and shop_id=".$shop_id
             . " and status='A'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query); 
        // execute query
        $stmt->execute();
    }
}