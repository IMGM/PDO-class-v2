PDO-class-v2
============

Object oriented PDO class


PDO-class-v2.php file is used for connecting server with prepared statement,

process method accepts all Sql queries. (By default it accepts 3 statement (INSERT, SELECT, UPDATE))

you can enable the remaining queries in 

please read the codes carefully before using it.


//example-1

        $call = new Connect();
        $call->process("INSERT INTO table VALUES (?, ?)", array("ID1234", "Name1"));
        echo $call->trace();
 
 
//example-2

        $call = new Connect();
        $getch = $call->process("SELECT * FROM table", array(""));
        foreach($getch as $key => $value)
        {
        
            echo "-->$key-->$value[0]<br>";
        }
        echo $call->trace();
