<?php
    session_start();

    require_once 'database.php';
    
    if(!isset($_SESSION['admin_ID'])){
        header("location:login_page.php");
        exit();
    }
    echo '<pre>Session Contents: ';
print_r($_SESSION);
echo '</pre>';


        $product=$db->prepare("SELECT * FROM manga_comic");
            $product->execute();

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

        <div id="comic_manga" class="comic_manga-font">
            <button class="magnga_comic">Comic</button>
            <button class="magnga_comic">Manga</button>
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
    while($row = $product->fetch(PDO::FETCH_ASSOC)){ ?>

    <div><a href="Product_details.php?id=<?php echo $row['MC_ID'];?>"><button class="img_button"><img src="data: $mimetype; base64,<?php echo base64_encode($row['cover_image']); ?>"class="img"><div id="title"><p><?php echo $row['title']?></p></div></a></button></div>


<?php } ?>

    

    </div> 


        <footer>
            <div>
                <details>
                <summary><label><a>Contact Us</a></label></summary><ul><li><a href="tel:+966013646">+999013646</a></li><li><a href="mailto:AAAAA@email.com">AAAAA@email.com</a></li></ul></details>
                <label><a>About Us</a></label>
            </div>

        </footer>
    </div>


</body>

</html>