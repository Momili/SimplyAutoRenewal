<?php

class payment_history {
    private $conn; 
    private $table_name = "payment_history"; 
    // object properties
    public $google_id;
    public $description;
    public $months;
    public $amount;
    public $start_date;
    public $end_date;
    public $created_date;   

    // constructor with $db as database connection 
    public function __construct($db){ 
        $this->conn = $db;
    }
    
    function add_payment(){ 
        
        $query = "INSERT INTO " . $this->table_name . "
                        (google_id, description, months, amount, start_date, end_date, created_date) 
                        VALUES (:google_id, :description, :months, :amount, :start_date, :end_date, :created_date)";
         try{               
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':google_id', $this->google_id);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':months', $this->months);            
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':start_date', $this->start_date);
            $stmt->bindParam(':end_date', $this->end_date);
            $stmt->bindParam(':created_date', date('Y-m-d H:i:s'));             
            // execute query
            $stmt->execute();
            echo 'created!'.$this->google_id;
            echo 'created!'.$this->description;
        }catch(PDOException $e){
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    function get_payment_history($userId){ 
        // select all query
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE google_id=". $userId ."
                  ORDER BY created_date DESC";
 
        // prepare query statement
        $stmt = $this->conn->prepare( $query );     
        // execute query
        $stmt->execute();     
        return $stmt;
    }
}