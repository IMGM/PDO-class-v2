<?php
require_once("index.php"); //Caution : by default it connects with index.php 
Interface Browse{
    public function __construct();
    public function __destruct();
    public function trace(); 
    public function process($Query, $value = array()); //by default it accepts only SELECT, UPDATE, INSERT statements
}
class Connect implements Browse{
    const community_host = "localhost";
    const community_db = "DBX";
    const db_username = "root";
    const db_password = "";
    private $conn = null;
    private $trace = "";
    
    public function process($Query, $value = array()){   
        try{
            $pattern = "/^(SELECT)|^(INSERT)|^(UPDATE)/"; // for more statement add after update statement(^(UPDATE)|)
            if(preg_match($pattern, $Query, $matches) === 1):          
                $ready = $this->conn->prepare($Query);
                $array = array_combine(array_keys(array_fill("1", count($value), ":")), $value);
                foreach($array as $key => $keyvalue){
                        $ready->bindValue($key, $keyvalue, PDO::PARAM_STR);
                }
                $ready->execute();
                if($matches[1] === "SELECT"):
                    $row = $ready->fetchall();
                    return $row;
                else:
                    return 1;
                endif;
            else: 
                return 0;
            endif;  
        }    
        catch(Exception $e){
            $this->trace .= " • ". $e->getMessage();  
        }            
    }//end of method
    
    public function __construct(){
        $connectionString = sprintf("mysql:host=%s; dbname=%s; charset=utf8", 
                                Connect::community_host, Connect::community_db);
        try{
            $this->conn = new PDO($connectionString, Connect::db_username, Connect::db_password);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);    
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);           
            $this->trace .= "¶ ";            
        }//end of connection by PDO
        catch(PDOException $e){
            //die($e->getMessage());
            $this->trace .= " • ". $e->getMessage();
        }
    }//end of construct

   public function __destruct(){
        $this->conn = null; //close connection       
   } //end of destruct
   
   public function trace(){
        return $this->trace;
   }//end of method
}

//example-1
$call = new Connect();
        $call->process("INSERT INTO table VALUES (?, ?)", array("ID1234", "Name1")); 
        echo $call->trace();
        
//example-2
$call = new Connect();
        $getch =  $call->process("SELECT * FROM table", array(""));
        foreach($getch as $key => $value)
        {
            echo "-->$key-->$value[0]<br>";
        }
        echo $call->trace();

