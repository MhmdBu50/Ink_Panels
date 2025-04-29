<?php

    session_start();
echo '<pre>Session Contents: ';
print_r($_SESSION);
echo '</pre>';


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
        <div id="burger"><span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span></div>

        <div id="comic_manga" class="comic_manga-font">
            <button class="magnga_comic">Comic</button>
            <button class="magnga_comic">Manga</button>
        </div>
        
        <div id="shopcart">
            <a href="cart_page.html"><button class="shopcart">
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

<?php

    $query="SELECT MC_ID , title ,cover_image FROM manga_comic ";

    if(!$conn=mysqli_connect("localhost","root","pass123","ink_panels", 3307))
        die("cannot connect to data base");
    if(!($database=mysqli_select_db($conn,"ink_panels")))
        die("cannot connect to db");
    $result=mysqli_query($conn,$query);

?>


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

    $mimetype=[
        'jpg'=>"image/jpg",
        'jpeg'=>"image/jepg",
        'png'=>"image/png",
        'gif'=>"image/gif",
        'webp'=>"image/webp"
    ];

    echo '
    <div>
        <a href="Product_details.php?id='.$row['MC_ID'].'">
            <button class="img_button">
                <!-- Display binary image directly -->
                <img src="data: $mimetype ;base64,'.base64_encode($row['cover_image']).'" 
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
    <details>
      <summary>Contact Us</summary>
      <ul>
        <li><a href="tel:+966013646">+966013646</a></li>
        <li><a href="mailto:2220002615@IAU.EDU.SA">2220002615@IAU.EDU.SA</a></li>
      </ul>
    </details>

    <div class="about-link">
      <a href="#">About Us</a>
    </div>
  </div>
</footer>


    </div>

<script>
    function openNav(){
        document.getElementById("sideNav").style.width="250px";
        document.getElementById("nav").style.marginLeft="250px";

    }
    function closeNav(){
        document.getElementById("sideNav").style.width="0px";
        document.getElementById("nav").style.marginLeft="0px";

    }
</script>
</body>

</html>