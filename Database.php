<?php 
  
class Database {
    
    # database infos
    private $host      = "localhost";

    private $db_name   = "ben";

    private $username  = "ramen";

    private $password  = "tomberry13";

    public  $connection;
    # get database connection
    public function getConnection() {
        $this->connection = null; #c'est un pote qui connait un mec qui a un cousin qui fait ça
        
        try {
           $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username,$this->password);	
           $this->connection->exec("set names utf8");
        }
        catch (PDOException $error) {      
           echo "Connection error: " . $error->getMessage(); 
        }
        
    return $this->connection;
    }
 }
?>
