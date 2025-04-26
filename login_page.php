<?php
    require_once "database.php";

    session_start();
 
    if(isset($_SESSION['user_ID'])){
        header("Location: Home_page.php");
        exit();
    }


    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $email = htmlspecialchars($_POST["email"]);
        $password = $_POST["password"];   
        try{

                    
                    $check=$db->prepare("SELECT * FROM user Where email=:email");


                    $check->bindParam(":email",$email);
                    $check->execute();
                    if($check->rowCount()>0){


                        $user = $check->fetch(PDO::FETCH_ASSOC);
                        if(password_verify($password,$user['password_hash'])){

                            $_SESSION['user_ID']=$user['user_ID'];
                            $_SESSION['email']=$user['Email'];
                            $_SESSION['logged_in']=true;
                        session_regenerate_id(true);
                        header("Location: Home_page.php");
                        exit();
                        }   
                        else{
                            echo"Invalid";
                        }
                    }
                    else{
                        echo"invaild email";

                    }
                    
                    
                    }
                    catch(PDOException $e){
                        if($e->getCode()==23000)
                        echo"<p class='error'>This email is already registered.</p>";
                     else {
                        echo "<p class='error'>Error: " . $e->getMessage() . "</p>"; // Show actual error
            error_log("Database error: ".$e->getMessage());
                    }                        }
}
?>
<!DOCTYPE html>
<html>
    <head>

         <meta charset="UTF-8" />
         <title>Ink Panles</title>
         <link href="css/LoginStyle.css" rel="stylesheet">


    </head>


    <body>

        <div class="sidebar">
            <h1>Ink Panels</h1>
            <p>your every day panel of entertainment</p>
            <img src="images/book.png" class="sidebar-image" alt="sidebar image">

        </div>

        <div class="main">

            <form class="login-form" method="post">  

                <div class="form-input">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required />
                  </div>
                  
                  <div class="form-input">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                  </div>
                  

                <a href="#" class="forgot-password">Forgot my password?</a>


                <div class="buttons-container">
                    <button type="submit" class="login-button">Login</button>
                    <button type="button" class="sign-up-button" onclick="location.href='sign_up.php'">Sign Up</button>
                </div>

            </form>

        </div>


    </body>
</html>
