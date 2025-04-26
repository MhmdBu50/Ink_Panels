<?php
    session_start();

    require_once 'database.php';

    if($_SERVER["REQUEST_METHOD"]=="POST"){


        if(isset($_POST['delete_id'])){
            $delete=$_POST['delete_id'];
            $stmt=$db->prepare("DELETE FROM manga_comic WHERE MC_ID=:id");
            $stmt->bindParam(":id",$delete,PDO::PARAM_INT);
            $stmt->execute();
            
        }else{
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
                echo"DONE";
            }catch(PDOException $e){
                echo "<p class='error'>Database error: " . $e->getMessage() . "</p>"; 
                error_log("Database error: " . $e->getMessage());
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
    
    <header class="menu">
        <a href="homePageAdminstrator.html">
        <button class="buttonmg">
        <svg class="logo-prod" width="93" height="83" viewBox="0 0 93 83" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.75 10.375H31C35.1109 10.375 39.0533 11.8324 41.9602 14.4267C44.867 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 45.2752 67.2345 43.0951 65.2888C40.915 63.3431 37.9581 62.25 34.875 62.25H7.75V10.375Z" fill="#D9D9D9"/>
            <path d="M85.25 10.375H62C57.8891 10.375 53.9467 11.8324 51.0398 14.4267C48.133 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 47.7248 67.2345 49.9049 65.2888C52.085 63.3431 55.0419 62.25 58.125 62.25H85.25V10.375Z" fill="#D9D9D9"/>
            <path d="M46.5 24.2083C46.5 20.5395 44.867 17.0209 41.9602 14.4267C39.0533 11.8324 35.1109 10.375 31 10.375H7.75V62.25H34.875C37.9581 62.25 40.915 63.3431 43.0951 65.2888C45.2752 67.2345 46.5 69.8734 46.5 72.625M46.5 24.2083V72.625M46.5 24.2083C46.5 20.5395 48.133 17.0209 51.0398 14.4267C53.9467 11.8324 57.8891 10.375 62 10.375H85.25V62.25H58.125C55.0419 62.25 52.085 63.3431 49.9049 65.2888C47.7248 67.2345 46.5 69.8734 46.5 72.625" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button></a>

        <div class="search-container">
            <button class="search-and-filter-buttons">  
            <svg class="search-icon" width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <!--the SVG icons are copied from figma-->
                <path d="M21 29C25.4183 29 29 25.4183 29 21C29 16.5817 25.4183 13 21 13C16.5817 13 13 16.5817 13 21C13 25.4183 16.5817 29 21 29Z" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M31.0002 31L26.7002 26.7" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>                     
            <input class="search poppins-regular" type="text" placeholder="Search ..." style="color: #ABB7C2;"> 
            <button class="search-and-filter-buttons">
                <svg id="filter" width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M31 14H24" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20 14H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M31 22H22" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18 22H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M31 30H26" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 30H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24 12V16" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18 20V24" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M26 28V32" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>           
        </div><div></div>
        
    </header>
    

    
    <form method="POST" enctype="multipart/form-data">

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
                    <button class="save-btn" type="submit">Save Changes</button>
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
                    <div class="search-container-table">
                        <button class="search-and-filter-buttons">  
                        <svg class="search-icon" width="26" height="26" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <!--the SVG icons are copied from figma-->
                            <path d="M21 29C25.4183 29 29 25.4183 29 21C29 16.5817 25.4183 13 21 13C16.5817 13 13 16.5817 13 21C13 25.4183 16.5817 29 21 29Z" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M31.0002 31L26.7002 26.7" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </button>                     
                        <input class="table-search-input" type="text" placeholder="Search ..." style="color: #ABB7C2;"> 
                        <button class="search-and-filter-buttons">
                            <svg id="filter" width="26" height="26" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M31 14H24" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 14H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M31 22H22" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18 22H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M31 30H26" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 30H13" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M24 12V16" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18 20V24" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M26 28V32" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>           
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

                <form method="post">
                    <input type="hidden" name="edit_id" value="<?= $row['MC_ID']; ?>">
                    <button class="buttonmg open-up" ><img src="images\edit.png"  alt="edit"  onclick='open'></button>

                    </form>
                <form method="post" onsubmit="return confirmdeletion();">
                    <input type="hidden" name="delete_id" value="<?= $row['MC_ID']; ?>">
                    <button class="buttonmg" type="submit" name="delete"><img src="images\delete.png" name="delet" alt="delete"></button>
                    </form>
                    

                </td>
            </tr>
            <?php } ?>
           
            
        </table>
    </div>

    <script>
        const openBtns = document.querySelectorAll('.open-up');
        const closeBtn = document.getElementById('close-up');
        const modal = document.getElementById('popup');

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => {
            modal.style.display = 'flex';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
            modal.style.display = 'none';
            }
        });

        function confirmdeletion(){
            return confirm("To erase this creation, to sever the ties of existence itself... Do you grasp the magnitude of what you are about to do? Once you erase this, there will be no returning. A soul extinguished, a life forgotten, wiped from the very fabric of time itself. Do you truly have the resolve to carry this sin? Is your hand steady enough to cast them into oblivion? No mercy, no second chances. You must ask yourself... can you bear the weight of their absence, forever? Would this work for your scene?")
        }
    </script>



</body>

</html>