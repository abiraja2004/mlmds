<!DOCTYPE html>
<?php
session_start();
include 'db.inc.php';
$db=mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD) or die('unable to connect . ');

mysql_select_db(MYSQL_DB,$db) or die(mysql_error($db));

$username=(isset($_POST['username']))?trim($_POST['username']):'';
$password=(isset($_POST['password']))?trim($_POST['password']):'';

if(isset($_POST['submit']) && $_POST['submit'] == 'submit')
{
     $errors=array();
    
    if(empty($username))
    {
        $errors[]='Username cannot be blank';
    }
     elseif(empty($password))
    {
        $errors[]='password cannot be blank';
    }
    
    elseif(count($errors) >0)
    {
        echo'<p style="color: white;"> Unable to process </p>';
        echo '<ul style="color: white;">';
        foreach($errors as $error)
        {
            echo'<li>'.$error.'</li>';
        }
        echo '</ul>';
    }
    else{
        $query='SELECT * FROM login WHERE username="'.$username.'" and password="'.$password.'"';
        $result=mysql_query($query,$db) or die(mysql_error($db));
        $rows =mysql_num_rows($result);
        while($row = mysql_fetch_assoc($result))
        {
          $_SESSION['user_id']=$row['user_id'];
        }
        if($rows > 0)
        {          
            $_SESSION['logged']=1;
            $_SESSION['username']="$username";
            header('Refresh: 0; URL=home2.php');
            echo '<head><link rel="stylesheet" href="css/style.css"  /></head><body style="color: white;">YOU ARE BEING REDIRECTED TO MAIN PAGE . PLEASE WAIT .......</body>';
           
            die();
        }
        else{
            $_SESSION['logged']=0;
            echo '<strong>Invalid username and password . Try again</strong>';
        }
    }
}



?>
<html>
    <head>
        <title>Login</title>
        <style>
            body{
                background: black;
            }
            #big_wrapper{
                background: white;
                padding: 30px;
                text-align: center;
                width: 700px;
                margin: 100px auto;
                border-radius: 25px;
                border: 13px solid red;
                box-shadow: 5px 5px 10px white;
            }
        </style>
    </head>
    <body>
        <div id="big_wrapper">
        <form action="home.php" method="post">
            <h1>Login</h1>
            <label for="username">Username: </label>
            <input type="name" name="username" /><br />
            
            <label for="password">Password:</label>
            <input type="password" name="password" /><br />
            
            <input type="submit" value="submit" name="submit" />
        </form>
         </div>
    </body>
</html>