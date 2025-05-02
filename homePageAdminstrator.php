<?php
    session_start();

    require_once 'database.php';
    
    if(!isset($_SESSION['admin_ID'])){
        header("location:login_page.php");
        exit();
    }
//     echo '<pre>Session Contents: ';
// print_r($_SESSION);
// echo '</pre>';


if(!$conn=mysqli_connect("localhost","root","root"))
die("cannot connect to data base");
if(!($database=mysqli_select_db($conn,"ink_panels")))
die("cannot connect to db");

$query="SELECT MC_ID , title ,cover_image ,type FROM manga_comic ";


$result=mysqli_query($conn,$query);




    if(isset($_GET['cat'])){
        $cat=mysqli_real_escape_string($conn,$_GET['cat']);
        $query .="WHERE type = '$cat'";


    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home page Adminstrator</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/HomePage.css" rel="stylesheet">

</head>
<body>

    <?php require"header.php"?>

    <div id="yellow_background">

    <?php if(isset($_SESSION['admin_ID'])): ?>
    <a href="myprofile.php" class="account-link">
        My Account: <?php echo htmlspecialchars($_SESSION['email']); ?>
        <button class="account-button">
            <i class="fas fa-user"></i>
        </button>
    </a>
<?php endif; ?>

    <div id="comic_manga" class="comic_manga-font">
            <button class="magnga_comic" data-filter="comic">Comic</button>
            <button class="magnga_comic" data-filter="manga">Manga</button>
        </div>
        
        <div id="shopcart">
            <a href="Managing products.php"><button class="shopcart">
                <svg width="35" height="35" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 12V28M12 20H28M6 2H34C36.2091 2 38 3.79086 38 6V34C38 36.2091 36.2091 38 34 38H6C3.79086 38 2 36.2091 2 34V6C2 3.79086 3.79086 2 6 2Z" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
            </button></a>
        </div>
    </div>       





<div id="nav">




   <div class="container">


   <?php

while ($row = mysqli_fetch_assoc($result)) {
    echo '
    <div data-category="'.htmlspecialchars($row['type']).'">
        <a href="Product_details.php?id='.$row['MC_ID'].'">
            <button class="img_button">
                <img src="data:image/jpeg;base64,'.base64_encode($row['cover_image']).'" 
                     class="img" 
                     alt="'.htmlspecialchars($row['title']).'">
                <div id="title">
                    <p>'.htmlspecialchars($row['title']).'</p>
                </div>
            </button>
        </a>
    </div>';
}
?>

    

    </div> 


        <footer>
            <div>
                <details>
                <summary><label><a>Contact Us</a></label></summary><ul><li><a href="tel:+966013646">+999013646</a></li><li><a href="mailto:AAAAA@email.com">AAAAA@email.com</a></li></ul></details>
                <label><a>About Us</a></label>
            </div>

        </footer>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.magnga_comic');
    const items = document.querySelectorAll('.container > div[data-category]');
    
    // Show all items initially
    items.forEach(item => item.style.display = 'block');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button styling (optional)
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            items.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

</body>

</html>