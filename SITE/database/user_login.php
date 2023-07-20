<?php
session_start();
include "../01_ASSETS/03_PHP/function.php";
include "../01_ASSETS/03_PHP/var.php";

 // insert which DB to connect to
$db_to_use = "u933389189_transroad_user"; 

if(isset($_POST['email']) && isset($_POST['password']))
{

    $my_Db_Connection= db_connect($db_to_use,$username,$password);
    
    // insert data sets
    $email = $_POST['email'];
    $password = $_POST['password'];


    // **************** A revoir **********************************
    // prepare SQL statement     
    $stm_login = $my_Db_Connection->prepare("SELECT username, surname, email, service, password 
        FROM users 
        WHERE email = :email ") ;

    $stm_access = $my_Db_Connection->prepare("SELECT service, dashboard, cost_estimation, cost_database 
    FROM access 
    WHERE service = :service") ;
    
    // bind parameter
    $stm_login ->bindParam(':email', $email_db);
    $stm_access ->bindParam(':service', $service);

    $email_db = $_POST['email'];

    $stm_login->execute();
    $arr_login = $stm_login->fetchAll();  //recup of the value given by $statement (array with username, surname, email and password)

        // check that the password and the email match the DB entries
    foreach ($arr_login as $row) {
        if ($row['email']==$email && $row['password']==$password)
        {
            $_SESSION['username'] = $row['username'];
            $_SESSION['surname'] = $row['surname'];
            $service= $row["service"];                    
        }else
        {
            header('Location: ../index.html?erreur=1');
        }
    }  

        // if connected: check the access of the person
    if (isset($service))
    {
        $stm_access->execute();
        $arr_access = $stm_access->fetchAll();

        foreach ($arr_access as $row) {
            $_SESSION['dashboard'] = $row['dashboard'];
            $_SESSION['cost_estimation'] = $row['cost_estimation'];
            $_SESSION['cost_database'] = $row['cost_database'];                 
        }
        header('Location: ../landing_page.html');
    }
    
}
else
{
   header('Location: ../index.html');
}

?> 

