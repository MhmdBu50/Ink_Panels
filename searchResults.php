<?php

$servername = "localhost";
$username = "root";
$password = "0509219409";
$dbname = "ink_panels";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_term = isset($_GET['manga_name']) ? $_GET['manga_name'] : '';


if (isset($_GET['show']) && $_GET['show'] === 'image' && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $sql = "SELECT cover_image FROM manga_comic WHERE MC_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        header("Content-Type: image/jpeg"); 
        echo $row['cover_image'];
    } else {
        echo "Image not found.";
    }
    $conn->close();
    exit; 
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manga Search Results</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/searchResults.css" rel="stylesheet">

</head>
<body>
<?php require"header.php"?>
    <h1>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h1>
    
   
    
    <?php
    
    if (!empty($search_term)) {
        $search_param = "%" . $search_term . "%";
        
        $sql = "SELECT MC_ID, title, description, author, price FROM manga_comic WHERE title LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['MC_ID'];
                $title = htmlspecialchars($row['title']);
                $description = htmlspecialchars($row['description']);
                $author = htmlspecialchars($row['author']);
                $price = number_format($row['price'], 2); 
                
                echo "<div class='manga-item'>";
                echo "<div class='manga-image'>";
                echo "<a href='Product_details.php?id=$id'><img src='?show=image&id=$id' alt='Cover for $title'></a>";
                echo "</div>";
                echo "<div class='manga-info'>";
                echo "<div class='manga-title'><a href='Product_details.php?id=$id'>$title</a></div>";
                echo "<div class='manga-description'>$description</div>";
                echo "<div class='manga-details'>";
                echo "<span class='manga-author'>Author: $author</span>";
                echo "<span class='manga-price'>$" . $price . "</span>";
                echo "</div>"; 
                echo "</div>"; 
                echo "</div>"; 
            }
        } else {
            echo "<div class='no-results'>No manga found matching your search for \"" . htmlspecialchars($search_term) . "\".</div>";
        }
    } else {
        echo "<div class='no-results'>Please enter a search term to find manga.</div>";
    }
    
    $conn->close();
    ?>
        <?php require"footer.php"?>

</body>
</html>