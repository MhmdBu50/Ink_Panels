<?php
session_start();
if(!isset($_SESSION['admin_ID'])) {
    header("location:home_page.php");
    exit();
}
require_once 'database.php';

$errors = [];
$edit_product = null;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['delete_id'])) {
        $delete = $_POST['delete_id'];

        $stmt = $db->prepare("DELETE FROM order_items WHERE MC_ID = :id");
        $stmt->bindParam(":id", $delete, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $db->prepare("DELETE FROM manga_comic WHERE MC_ID = :id");
        $stmt->bindParam(":id", $delete, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    elseif(isset($_POST['add_product'])) {
        $title = htmlspecialchars($_POST["title"]);
        $author = htmlspecialchars($_POST["author"]);
        $genre = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
        $type = htmlspecialchars($_POST["type"]);
        $stock = htmlspecialchars($_POST["stock"]);
        $price = htmlspecialchars($_POST["price"]); 
        $dec = htmlspecialchars($_POST["dec"]);
        $date = htmlspecialchars($_POST["date"]);

        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            $errors[] = "Error uploading image";
        }

        if(empty($title)) $errors[] = "Title is required";
        if(empty($author)) $errors[] = "Author is required";
        if(empty($genre)) $errors[] = "Genre is required";
        if(empty($type)) $errors[] = "Type is required";
        if($stock <= 0) $errors[] = "Invalid stock quantity";
        if(empty($image)) $errors[] = "Image is required";
        if($price <= 0) $errors[] = "Invalid price";
        if(empty($dec)) $errors[] = "Description is required";
        if(empty($date)) $errors[] = "Release date is required";

        if(empty($errors)) {
            try {
                $stmt = $db->prepare("INSERT INTO manga_comic(title, author, price, genre, stock_quantity, release_date, cover_image, description, type) 
                                    VALUES(:title, :author, :price, :genre, :stock, :date, :image, :dec, :type)");

                $stmt->bindParam(":title", $title);
                $stmt->bindParam(":author", $author);
                $stmt->bindParam(":price", $price, PDO::PARAM_STR);
                $stmt->bindParam(":genre", $genre);
                $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
                $stmt->bindParam(":dec", $dec);
                $stmt->bindParam(":type", $type);
                $stmt->execute();
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } catch(PDOException $e) {
                $errors[] = "Database error: ".$e->getMessage();
            }
        }
    }
    elseif(isset($_POST['update_product'])) {
        $edit_id = $_POST['edit_id'];
        $title = htmlspecialchars($_POST["edit_title"]);
        $author = htmlspecialchars($_POST["edit_author"]);
        $genre = isset($_POST['edit_genre']) ? implode(',', $_POST['edit_genre']) : '';
        $type = isset($_POST['edit_type']) ? htmlspecialchars($_POST["edit_type"]) : '';
        $stock = htmlspecialchars($_POST["edit_stock"]);
        $price = htmlspecialchars($_POST["edit_price"]); 
        $dec = htmlspecialchars($_POST["edit_dec"]);
        $date = htmlspecialchars($_POST["edit_date"]);

        try {
            if(isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] == 0) {
                $image = file_get_contents($_FILES['edit_image']['tmp_name']);
                $stmt = $db->prepare("UPDATE manga_comic SET 
                                    title = :title,
                                    author = :author,
                                    price = :price,
                                    genre = :genre,
                                    stock_quantity = :stock,
                                    release_date = :date,
                                    description = :dec,
                                    type = :type,
                                    cover_image = :image
                                    WHERE MC_ID = :id");
                $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
            } else {
                $stmt = $db->prepare("UPDATE manga_comic SET 
                                    title = :title,
                                    author = :author,
                                    price = :price,
                                    genre = :genre,
                                    stock_quantity = :stock,
                                    release_date = :date,
                                    description = :dec,
                                    type = :type
                                    WHERE MC_ID = :id");
            }
            
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":genre", $genre);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":dec", $dec);
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":id", $edit_id);
            $stmt->execute();
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } catch(PDOException $e) {
            $errors[] = "Database error: ".$e->getMessage();
        }
    }
}

$query = "SELECT MC_ID, title, author, price, genre, stock_quantity, release_date, description, type FROM manga_comic";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $db->prepare("SELECT * FROM manga_comic WHERE MC_ID = ?");
    $stmt->execute([$edit_id]);
    $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Managing products page</title>
    <link href="CSS/Managing_Products.css" rel="stylesheet">
    <link href="CSS/Style.css" rel="stylesheet">

</head>
<body>
    <?php require "header.php"; ?>
    
    <div id="popup" class="overlay">
        <div class="content-up">
            <button id="close-up" type="button">close</button>
            <?php if(!empty($errors)): ?>
                <div class="error">
                    <?php foreach($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" id="add-form">
                <div>
                    <div>
                        <label>Title</label><br>
                        <input type="text" name="title" class="type-up" required>
                    </div>
                    <div>
                        <label>Author</label><br>
                        <input type="text" class="type-up" name="author" required>
                    </div>
                    <div>
                        <label>Genre</label><br>
                        <select class="type-up" multiple name="genre[]">
                            <option value="Action">Action</option>
                            <option value="Drama">Drama</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Sports">Sports</option>
                            <option value="Romance">Romance</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Slice of Life">Slice of Life</option>
                            <option value="Supernatural">Supernatural</option>
                            <option value="Suspense">Suspense</option>
                        </select>
                    </div>
                    <div>
                        <label>Type</label><br>
                        <select class="type-up" name="type">
                            <option value="Manga">Manga</option>
                            <option value="Comic">Comic</option>
                        </select>
                    </div>
                    <div>
                        <label>Stock</label><br>
                        <input type="number" class="type-up" name="stock" required>
                    </div>
                    <div>
                        <label>Select image for manga/comic</label>
                        <input type="file" name="image" accept="image/*" class="type-up" required>
                    </div>
                    <div>
                        <label>Unit Price</label><br>
                        <input type="number" step="0.01" class="type-up" name="price" required>
                    </div>
                    <div>
                        <label>Release Date</label><br>
                        <input type="date" class="type-up" name="date" required>
                    </div>
                    <div>
                        <label>Description</label><br>
                        <textarea placeholder="What were you reading about?" name="dec" required></textarea>
                    </div>
                    <button class="save-btn" type="submit" name="add_product">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-popup" class="overlay">
        <div class="content-up">
            <button id="close-edit" class="close-up" type="button">close</button>
            <?php if(!empty($errors)): ?>
                <div class="error">
                    <?php foreach($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" id="edit-form">
                <input type="hidden" name="edit_id" id="edit-product-id">
                <div>
                    <div>
                        <label>Title</label><br>
                        <input type="text" name="edit_title" id="edit-title" class="type-up" required>
                    </div>
                    <div>
                        <label>Author</label><br>
                        <input type="text" class="type-up" name="edit_author" id="edit-author" required>
                    </div>
                    <div>
                        <label>Genre</label><br>
                        <select class="type-up" multiple name="edit_genre[]" id="edit-genre">
                            <option value="Action">Action</option>
                            <option value="Drama">Drama</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Sports">Sports</option>
                            <option value="Romance">Romance</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Slice of Life">Slice of Life</option>
                            <option value="Supernatural">Supernatural</option>
                            <option value="Suspense">Suspense</option>
                        </select>
                    </div>
                    <div>
                        <label>Type</label><br>
                        <select class="type-up" name="edit_type" id="edit-type">
                            <option value="Manga">Manga</option>
                            <option value="Comic">Comic</option>
                        </select>
                    </div>
                    <div>
                        <label>Stock</label><br>
                        <input type="number" class="type-up" name="edit_stock" id="edit-stock" required>
                    </div>
                    <div>
                        <label>Select new image (optional)</label>
                        <input type="file" name="edit_image" accept="image/*" class="type-up">
                    </div>
                    <div>
                        <label>Unit Price</label><br>
                        <input type="number" step="0.01" class="type-up" name="edit_price" id="edit-price" required>
                    </div>
                    <div>
                        <label>Release Date</label><br>
                        <input type="date" class="type-up" name="edit_date" id="edit-date" required>
                    </div>
                    <div>
                        <label>Description</label><br>
                        <textarea placeholder="What were you reading about?" name="edit_dec" id="edit-dec" required></textarea>
                    </div>
                    <button class="save-btn" type="submit" name="update_product">Update Product</button>
                </div>
            </form>
        </div>
    </div>

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
                <th class="th-prod">Author</th>
                <th class="th-prod">Type</th>
                <th class="th-prod">Genre</th>
                <th class="th-prod">Stock</th>
                <th class="th-prod">Unit Price</th>
                <th class="th-prod">Release Date</th>
                <th class="th-prod"><button class="buttonmg open-up" id="add">Add New Product</button>
                </th>
            </tr>
            <?php foreach($products as $i => $row): ?>
            <tr class="tr-prod">
                <td class="td-prod"><?= $i + 1 ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['title']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['author']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['type']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['genre']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['stock_quantity']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['price']) ?></td>
                <td class="td-prod"><?= htmlspecialchars($row['release_date']) ?></td>
                <td class="td-prod" style="display: none;"><?= htmlspecialchars($row['description']) ?></td>
                <td class="img-container">
                    <form method="post" class="edit-form">
                        <input type="hidden" name="edit_id" value="<?= $row['MC_ID'] ?>">
                        <button type="button" class="buttonmg open-edit" data-id="<?= $row['MC_ID'] ?>">
                            <img src="images/edit.png" alt="edit">
                        </button>
                    </form>
                    <form method="post" onsubmit="return confirmDeletion();">
                        <input type="hidden" name="delete_id" value="<?= $row['MC_ID'] ?>">
                        <button type="submit" name="delete" class="buttonmg">
                            <img src="images/delete.png" alt="delete">
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php require "footer.php"; ?>

    <script>
        document.getElementById('add').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('popup').style.display = 'flex';
        });

        document.getElementById('close-up').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('popup').style.display = 'none';
            document.getElementById('add-form').reset();
        });

        document.querySelectorAll('.open-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const row = this.closest('tr');
                const cells = row.cells;
                
                document.getElementById('edit-product-id').value = productId;
                document.getElementById('edit-title').value = cells[1].textContent;
                document.getElementById('edit-author').value = cells[2].textContent;
                document.getElementById('edit-type').value = cells[3].textContent.trim();
                
                const genres = cells[4].textContent.split(',').map(g => g.trim());
                const genreSelect = document.getElementById('edit-genre');
                Array.from(genreSelect.options).forEach(option => {
                    option.selected = genres.includes(option.value);
                });
                
                document.getElementById('edit-stock').value = cells[5].textContent;
                document.getElementById('edit-price').value = cells[6].textContent;
                document.getElementById('edit-date').value = new Date(cells[7].textContent).toISOString().split('T')[0];
                document.getElementById('edit-dec').value = row.querySelector('td:nth-child(9)').textContent;
                
                document.getElementById('edit-popup').style.display = 'flex';
            });
        });

        document.getElementById('close-edit').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('edit-popup').style.display = 'none';
        });

        document.querySelectorAll('.overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if(e.target === this) {
                    this.style.display = 'none';
                }
            });
        });

        function confirmDeletion() {
            return confirm("To erase this creation, to sever the ties of existence itself... Do you grasp the magnitude of what you are about to do? Once you erase this, there will be no returning. A soul extinguished, a life forgotten, wiped from the very fabric of time itself. Do you truly have the resolve to carry this sin? Is your hand steady enough to cast them into oblivion? No mercy, no second chances. You must ask yourself... can you bear the weight of their absence, forever? Would this work for your scene?");
        }
    </script>
</body>
</html>