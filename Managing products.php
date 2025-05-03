<?php
    session_start();

    require_once 'database.php';

    if($_SERVER["REQUEST_METHOD"]=="POST"){


        if(isset($_POST['delete_id'])){
            $delete=$_POST['delete_id'];
            $stmt=$db->prepare("DELETE FROM manga_comic WHERE MC_ID=:id");
            $stmt->bindParam(":id",$delete,PDO::PARAM_INT);
            $stmt->execute();
            
        }elseif(isset($_POST['add_product'])){
        $title=htmlspecialchars($_POST["title"]);
        $author=htmlspecialchars($_POST["author"]);
        $genre = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
        $type=htmlspecialchars($_POST["type"]);
        $stock=htmlspecialchars($_POST["stock"]);
        $price=htmlspecialchars($_POST["price"]); 
        $dec=htmlspecialchars($_POST["dec"]);
        $date=htmlspecialchars($_POST["date"]);

        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            $errors[] = "Error uploading image";
        }
        

  

        $errors=[];

        if(empty($title)) $errors[]="error";
        if(empty($author)) $errors[]="error";
        if(empty($genre)) $errors[]="error";
        if(empty($type)) $errors[]="error";
        if($stock<=0) $errors[]="error";
        if(empty($image)) $errors[]="error";
        if($price<=0) $errors[]="error";
        if(empty($dec)) $errors[]="error";
        if(empty($date)) $errors[]="error";

        if(empty($errors))
        {   
            try{


            $edit=$db->prepare("INSERT INTO manga_comic(title , author , price , genre , stock_quantity , release_date , cover_image ,description,type) 
                                                VALUE(:title,:author,:price,:genre,:stock,:date,:image,:dec,:type)");

                // $stmt->bindParam(":title")
                $edit->bindParam(":title", $title);
                $edit->bindParam(":author", $author);
                $edit->bindParam(":price", $price, PDO::PARAM_STR);
                $edit->bindParam(":genre", $genre);
                $edit->bindParam(":stock", $stock, PDO::PARAM_INT);
                $edit->bindParam(":date", $date);
                $edit->bindParam(":image", $image,PDO::PARAM_LOB);
                $edit->bindParam(":dec", $dec);
                $edit->bindParam(":type", $type);
                $edit->execute();
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();            
            }catch(PDOException $e){
                // echo "<p class='error'>Database error: " . $e->getMessage() . "</p>"; 
                // error_log("Database error: " . $e->getMessage());
            }
            
        }

    }}







    $query="SELECT MC_ID ,title , author , price , genre , stock_quantity , release_date ,description,type FROM manga_comic ";
    $query="SELECT MC_ID ,title , author , price , genre , stock_quantity , release_date ,description,type FROM manga_comic ";


        $stmt=$db->prepare($query);
        $stmt->execute();










    ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Managing products page</title>
    <link href="CSS\Managing_Products.css" rel="stylesheet">
    <link href="CSS\Style.css" rel="stylesheet">


</head>
<body>
    
    <?php require"header.php"?>
    
    <form method="POST" enctype="multipart/form-data" id="add-form">

        <div id="popup" class="overlay">
            <div class="content-up">
                    
                <button id="close-up">close</button>

                <div>
                    <div>
                        <label>Title</label><br>
                        <input type="text" name="title"  class="type-up" required>
                    </div>
                    <div>
                        <label>author</label><br>
                        <input type="text" class="type-up" name="author"  required>
                    </div>

                    <div>
                        <label>Genre</label><br>
                        <select class="type-up" multiple name="genre[]">
                            <option value="Action">Action</option>
                            <option>Drama</option>
                            <option>Comedy</option>
                            <option>Adventure</option>
                            <option>Fantasy</option>
                            <option>Mystery</option>
                            <option>Sports</option>
                            <option>Romance</option>
                            <option>Sci-Fi</option>
                            <option>Slice of Life</option>
                            <option>Supernatural</option>
                            <option>Suspense</option>
                        </select>
                    </div>

                    <div>
                        <label>Type</label><br>
                        <select class="type-up" name="type">
                            <option>Manga</option>
                            <option>Comic</option>
                        </select>
                    </div>


                    

                    <div>
                        <label>Stock</label><br>
                        <input type="number" class="type-up" name="stock">
                    </div>

                    <div>
                            <label>Select image for manga/comic</label>
                            <input type="file" name="image" accept="image/*"  class="type-up"   required>
                    </div>


                    <div>
                        <label>Unit Price</label><br>
                        <input type="number" class="type-up" name="price">
                    </div>
                    <div>
                        <label>Release Date</label><br>
                        <input type="date" class="type-up" name="date">
                    </div>
                    <div>
                        <label>Description</label><br>
                        <textarea placeholder="What were you reading about?"  name="dec"></textarea>
                    </div>
                    <button class="save-btn" type="submit" name="add_product">Save Changes</button>
                </div>
            </div>
        </div>

    </form>


    <div class="table-wrap">

        <table class="table-prod">
            <colgroup>
                <col style="width: 5%;">
                <col>
                <col style="width: 10%;">
                <col style="width: 8%;">
                <col style="width: 10%;">
                <col style="width: 15%;">
                <col style="width: 12.5%;">
            </colgroup>
            <tr>
                <th class="th-prod">NO.</th>
                <th class="th-prod">Title</th>
                <th class="th-prod">author</th>
                <th class="th-prod">type</th>
                <th class="th-prod">Genre</th>
                <th class="th-prod">Stock</th>
                <th class="th-prod">Unit Price</th>
                <th class="th-prod">Release Date</th>

                
                <th class="th-prod">
                    <div>            
                        <button class="buttonmg open-up"onclick='open' id="add"> add</button>
                    </div>
                </th>
            </tr>
            <?php 
                $_cou=0;
                while($row=$stmt->fetch(PDO::FETCH_ASSOC)){ $_cou++?>

            <tr class="tr-prod">
                <td class="td-prod"><?php echo $_cou?></td>
                <td class="td-prod"><?php echo $row['title'];?></td>
                <td class="td-prod"><?php echo $row['author'];?></td>
                <td class="td-prod"><?php echo $row['type'];?></td>
                <td class="td-prod"><?php echo $row['genre'];?></td>
                <td class="td-prod"><?php echo $row['stock_quantity'];?></td>
                <td class="td-prod"><?php echo $row['price'];?></td>
                <td class="td-prod"><?php echo $row['release_date'];?></td>
                <td class="img-container">


                    </form>
                <form method="post" onsubmit="return confirmdeletion();">
                    <input type="hidden" name="delete_id" value="<?= $row['MC_ID']; ?>">
                    <button id="buttonmg" type="submit" name="delete"><img src="images\delete.png" name="delet" alt="delete"></button>
                    </form>
                </td>
                
            </tr>
            <?php } ?>

            
        </table>
    </div>

    <?php require"footer.php"?>

    <script>
     // Only open popup when explicitly clicked, not on page load
document.addEventListener('DOMContentLoaded', function() {
    // Hide popup initially
    document.getElementById('popup').style.display = 'none';
    
    // Set up event listeners
    document.querySelectorAll('.open-up').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default behavior
            document.getElementById('popup').style.display = 'flex';
        });
    });

    // Close button
    document.getElementById('close-up').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup').style.display = 'none';
        document.getElementById('add-form').reset(); // Clear form
    });
});

        function confirmdeletion(){
            return confirm("To erase this creation, to sever the ties of existence itself... Do you grasp the magnitude of what you are about to do? Once you erase this, there will be no returning. A soul extinguished, a life forgotten, wiped from the very fabric of time itself. Do you truly have the resolve to carry this sin? Is your hand steady enough to cast them into oblivion? No mercy, no second chances. You must ask yourself... can you bear the weight of their absence, forever? Would this work for your scene?")
        }
    </script>



</body>

</html>