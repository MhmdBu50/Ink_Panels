

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Ink Panels - Sign Up</title>
    <link href="css/LoginStyle.css" rel="stylesheet">

</head>
<body>

    <div class="sidebar">
        <h1>Ink Panels</h1>
        <p>your every day panel of entertainment</p>
        <img src="images/book.png" class="sidebar-image" alt="sidebar image">
    </div>
    <?php

        if($_SERVER["REQUEST_METHOD"]=="POST"){

            $first_name = htmlspecialchars($_POST["first_name"]);
            $last_name = htmlspecialchars($_POST["last_name"]);
            $email = htmlspecialchars($_POST["email"]);
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];


            $errors=[];

            if(empty($first_name)) $errors[]="First name is required";
            if(empty($last_name)) $errors[]="Last name is required";
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]="Invalid email format";
            if(strlen($password)<8) $errors[] = "Password must be 8 characters";
            if($password !== $confirm_password) $errors[]="passwords don't match";
            if(!isset($_POST["terms"])) $errors[]="You must accept the terms";

            if(empty($errors)){
                try{
                        // $query="SELECT First_name , Email , password_hash  FROM user";

                        // if(!$conn=mysqli_connect("localhost","root","root"))
                        //     die("cannot connect to data base");
                        // if(!($database=mysqli_select_db($conn,"ink_panels")))
                        //     die("cannot connect to db");
                        // $result=mysqli_query($conn,$query);

                
  

                    $db=new PDO('mysql:host=localhost;dbname=ink_panels','root','root');

                        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                            $hashed_password = password_hash($password,PASSWORD_DEFAULT);

                        $stmt = $db->prepare("INSERT INTO user (First_name,Last_name,Email,password_hash) VALUES(:First_name,:Last_name,:Email,:password_hash)");
               
                        $stmt->bindParam(':First_name',$first_name);
                        $stmt->bindParam(':Last_name',$last_name);
                        $stmt->bindParam(':Email',$email);
                        $stmt->bindParam(':password_hash',$hashed_password);

                        $stmt->execute();
                        echo "<p class='success'>Registration successful! Welcome, $first_name!</p>";
                
            }catch(PDOException $e){
                if($e->getCode()==23000)
                    echo"<p class='error'>This email is already registered.</p>";
                 else {
                    echo "<p class='error'>Error: " . $e->getMessage() . "</p>"; // Show actual error
    error_log("Database error: ".$e->getMessage());
                }
            }
        }
        else{
            foreach($errors as $error)
            echo"<p class='error'> $error </p>";
        }
        }
    
    ?>

    <div class="main">
        <form class="login-form" method="post" >
            <div class="form-input-row">
                <div class="form-input">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name"  name="first_name"required />
                </div>
                <div class="form-input">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name"name="last_name" required />
                </div>
            </div>

            <div class="form-input">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />
            </div>

            <div class="form-input">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>

            <div class="form-input">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required />
            </div>

            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required />
                <label for="terms">
                    I have read and agreed to the 
                    <a href="terms.html" target="_blank">terms of service</a>
                </label>
            </div>

            <div class="buttons-container">
                <button type="submit" class="login-button">Sign Up</button>
                <button type="button" class="sign-up-button" onclick="location.href='login_page.html'">Back to Login</button>
            </div>
        </form>
    </div>

</body>
</html>
