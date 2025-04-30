<?php

    session_start();
// echo '<pre>Session Contents: ';
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
    <title>Home page</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/HomePage.css" rel="stylesheet">

</head>
<body>

    <?php require"header.php"?>

    <div id="yellow_background">

    <?php if(isset($_SESSION['user_ID'])): ?>
    <a href="myprofile.php" class="account-link">
        My Account: <?php echo htmlspecialchars($_SESSION['email']); ?>
        <button class="account-button">
            <i class="fas fa-user"></i> <!-- Optional icon -->
        </button>
    </a>
<?php endif; ?>

        <div id="comic_manga" class="comic_manga-font">
            <button class="magnga_comic" data-filter="comic">Comic</button>
            <button class="magnga_comic" data-filter="manga">Manga</button>
        </div>
    
        
        <div id="shopcart">
            <a href="cart_page.php"><button class="shopcart">
                <svg width="35" height="35" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M46 2H38L32.64 28.78C32.4571 29.7008 31.9562 30.5279 31.2249 31.1166C30.4936 31.7053 29.5786 32.018 28.64 32H9.2C8.2614 32.018 7.34636 31.7053 6.61509 31.1166C5.88381 30.5279 5.38289 29.7008 5.2 28.78L2 12H36M28 42C28 43.1046 28.8954 44 30 44C31.1046 44 32 43.1046 32 42C32 40.8954 31.1046 40 30 40C28.8954 40 28 40.8954 28 42ZM6 42C6 43.1046 6.89543 44 8 44C9.10457 44 10 43.1046 10 42C10 40.8954 9.10457 40 8 40C6.89543 40 6 40.8954 6 42Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button></a>
        </div>
    </div>       

<div id="sideNav" class="sideNav">

<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

<div id="nav-contant">
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/dragon ball.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/fist of the north star.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>


</div>

</div>




<div id="nav">



    <div class="container">
        
        <!-- <div><a href="Product_details.html"><button class="img_button"><img src="/images/aka chan.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/dragon ball.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/hajme no ippo.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/world trigger.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/the beginning after the end.jpg" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/Sakamoto.jpg" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/aka chan.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/dragon ball.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/hajme no ippo.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/world trigger.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/the beginning after the end.jpg" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/Sakamoto.jpg" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div> -->
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
        <footer id="site-footer">
            <div class="footer-content">
            <h2><a href="ContactUs_page.php">Contact Us</a></h2>
            <h2><a href="#">About Us</a></h2>
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