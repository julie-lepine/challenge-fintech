<?php
include "../assets/php/function.php";

 // insert which DB to connect to
 $db_name="u933389189_transroad_user";
 $servername = "mysql.hostinger.com";
 $db_username = "u933389189_transroad";
 $db_password = "Findeveleven_11";
 $db_to_use = "u933389189_transroad_user"; 
 $dbtable="users";

 
 
 //Insert which value we check to avoid insert data
 $checkfield = array('email');

 if(isset($_POST['username']) && isset($_POST['surname'])&& isset($_POST['service'])&& isset($_POST['email'])&& isset($_POST['password']))
 {
 
    $my_Db_Connection= db_connect($servername,$db_to_use,$db_username,$db_password);
        
    // create an array of all the POST variables you want to use
    $fields = array('username','surname','service','position','email','password');

    // prepare SQL statement and bind values    
    $stm_insert = $my_Db_Connection->prepare(query_insert($dbtable,$fields)) ;

    $count=1;
    foreach($fields as $field){
        $stm_insert ->bindParam($count, $_POST[$field]);
        $count++;
    }

     // verification of value not in DB to allow the writing
    try
    {
        $query_check= $my_Db_Connection->prepare("SELECT email
            from users
            where
            email = :email");
        $query_check ->bindParam(":email", $_POST["email"]);

        $query_check->execute();
        $answers = $query_check->fetch();

        if($answers) 
        {
            //this email already exist
            header('Location: ../pages/administration/user_mgt.html?=erreur1');
        }

    }
    catch (PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    $stm_insert->execute();
    
    header('Location: ../pages/administration/user_mgt.html?=success');

 }
 else
 {
    header('Location: ../pages/administration/user_mgt.html?=erreur2');

 }


 ?> 

