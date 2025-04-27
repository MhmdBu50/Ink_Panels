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
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>

        <div id="comic_manga" class="comic_manga-font">
            <button class="magnga_comic">Comic</button>
            <button class="magnga_comic">Manga</button>
        </div>
        
        <div id="shopcart">
            <a href="Managing products.html"><button class="shopcart">
                <svg width="35" height="35" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 12V28M12 20H28M6 2H34C36.2091 2 38 3.79086 38 6V34C38 36.2091 36.2091 38 34 38H6C3.79086 38 2 36.2091 2 34V6C2 3.79086 3.79086 2 6 2Z" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
            </button></a>
        </div>
    </div>       

<div id="sideNav" class="sideNav">

<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

<div id="nav-contant">
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>
<div class="lastpurchases"><img src="/images/aka chan.png" class="navimg"></div>


</div>

</div>




<div id="nav">





    <div class="container">
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/aka chan.png" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
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
        <div><a href="Product_details.html"><button class="img_button"><img src="/images/Sakamoto.jpg" class="img"><div id="title"><p>aka chan boku</p></div></a></button></div>
        
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