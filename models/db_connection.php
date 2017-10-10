<?php class db_connection{ 
    
/* BlueHost
private $host = "localhost"; 
private $db_name = "blendinc_Etsy"; 
private $username = "blendinc_etsy"; 
private $password = "Backbreaker12!"; 
*/
    
/*work    
private $host = "localhost:3306"; 
private $db_name = "blendinc_etsy"; 
private $username = "root"; 
private $password = "";
*/
//Home    
private $host = "localhost:3306"; 
private $db_name = "blendinc_etsy"; 
private $username = "root"; 
private $password = "";//sa";
//

public $conn; 
public function getConnection(){ $this->conn = null;         
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }         
        return $this->conn;
    }
}