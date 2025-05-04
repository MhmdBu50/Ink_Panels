<?php
    require_once "database.php";

    session_start();
 
    if(isset($_SESSION['user_ID'])){
        header("Location: Home_page.php");
        exit();
    }

    // Initialize error variables
    $error_msg = "";
    $error_passmsg = "";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        // Validate and sanitize input
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = "Please enter a valid email address.";
        } else {
            try {
                // Check if email exists in admin table
                $ifadmin = $db->prepare("SELECT * FROM admins WHERE email = :email");
                $ifadmin->bindParam(":email", $email);
                $ifadmin->execute();

                if($ifadmin->rowCount() > 0) {
                    $admin = $ifadmin->fetch(PDO::FETCH_ASSOC);
                    // Use password_verify for admin passwords too (consider updating this)
                    if($password == $admin['password_hash']) {
                        $_SESSION['admin_ID'] = $admin['admin_ID'];
                        $_SESSION['email'] = $admin['email'];
                        $_SESSION['login'] = true;
                        session_regenerate_id(true);

                        header("Location: homePageAdminstrator.php");
                        exit();
                    } else {
                        $error_passmsg = "The password you entered is incorrect.";
                    }
                } else {
                    // Check regular user table
                    $check = $db->prepare("SELECT * FROM user WHERE Email = :email");
                    $check->bindParam(":email", $email);
                    $check->execute();

                    if($check->rowCount() > 0) {
                        $user = $check->fetch(PDO::FETCH_ASSOC);
                        if(password_verify($password, $user['password_hash'])) {
                            $_SESSION['user_ID'] = $user['user_ID'];
                            $_SESSION['email'] = $user['Email'];
                            $_SESSION['logged_in'] = true;
                            session_regenerate_id(true);

                            header("Location: Home_page.php");
                            exit();
                        } else {
                            $error_passmsg = "The password you entered is incorrect.";
                        }
                    } else {
                        $error_msg = "No account found with that email address.";
                    }
                }
            } catch(PDOException $e) {
                // Log the error for admins and show a friendly message to users
                error_log("Database error: " . $e->getMessage());
                $error_msg = "A system error occurred. Please try again later.";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8" />
         <title>Ink Panels</title>
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
                <div class="form-group">
                    <div class="form-input">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required />
                    </div>
                    <div class="error-message"><?php echo $error_msg; ?></div>
                </div>
                  
                <div class="form-group">
                    <div class="form-input">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required />
                    </div>
                    <div class="error-message"><?php echo $error_passmsg; ?></div>
                </div>
                
                <div class="buttons-container">
                    <button type="submit" class="login-button">Login</button>
                    <button type="button" class="sign-up-button" onclick="location.href='sign_up.php'">Sign Up</button>
                </div>
            </form>
        </div>
    </body>
</html>