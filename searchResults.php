<?php
 session_start();
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
                echo "<span class='manga-price'>";
                
                echo '<svg width="27" height="27" viewBox="0 0 34 37" fill="#29eb02"" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.9415 17.9117V0.0411447V0C15.9415 0 15.6897 0.148767 15.53 0.246868C15.2098 0.44365 15.0263 0.548935 14.7209 0.768034C14.5346 0.901663 14.4349 0.983096 14.2546 1.12462C14.1197 1.23048 14.0435 1.28918 13.9117 1.39892C13.7984 1.4933 13.7338 1.55028 13.628 1.64355L13.61 1.6595C13.4251 1.82251 13.3232 1.91588 13.1437 2.08466C12.9311 2.28441 12.8091 2.39388 12.6088 2.60583C12.4707 2.75194 12.3955 2.83618 12.2659 2.98985C12.1726 3.10046 12.0327 3.27786 12.0327 3.27786V18.7483L3.31007 20.5998C3.31007 20.5998 3.03313 21.2451 2.88491 21.6695C2.77935 21.9718 2.72828 22.1441 2.63804 22.4513C2.5582 22.7231 2.51091 22.875 2.44604 23.1507C2.37483 23.4534 2.34409 23.6254 2.29517 23.9325C2.23914 24.2841 2.18545 24.8377 2.18545 24.8377L12.0327 22.7393V27.7452L1.48599 29.9671C1.48599 29.9671 1.27558 30.4311 1.15684 30.7351C1.00029 31.1358 0.917708 31.3635 0.800248 31.7774C0.705546 32.1111 0.667126 32.3023 0.594524 32.6415C0.522627 32.9773 0.480542 33.1657 0.429946 33.5055C0.388649 33.7828 0.347656 34.2187 0.347656 34.2187L11.7584 31.7774C11.7584 31.7774 12.1727 31.6279 12.4168 31.4894C12.6507 31.3566 12.7764 31.2686 12.9791 31.0917C13.1474 30.9447 13.3768 30.6802 13.3768 30.6802L15.6398 27.3612C15.6398 27.3612 15.6946 27.2652 15.8181 27.0458C15.9415 26.8263 15.9415 26.5246 15.9415 26.5246V21.9164L19.8228 21.0935V29.0619L32.3171 26.4012C32.3171 26.4012 32.4557 26.1108 32.5365 25.9212C32.6224 25.7199 32.6676 25.6057 32.7422 25.4C32.8648 25.0624 32.9202 24.8682 33.0165 24.5222C33.1273 24.1243 33.1837 23.899 33.2634 23.4936C33.3667 22.9682 33.4554 22.1358 33.4554 22.1358L23.7316 24.2342V20.2569L32.3171 18.4328C32.3171 18.4328 32.4563 18.1258 32.5365 17.9254C32.6234 17.7083 32.6653 17.5839 32.7422 17.3631C32.857 17.0336 32.919 16.8477 33.0165 16.5127C33.1248 16.1411 33.1846 15.9317 33.2634 15.5527C33.3724 15.0284 33.4554 14.1949 33.4554 14.1949L23.7316 16.2933V1.96123L23.567 2.05723C23.4024 2.15324 22.9925 2.38639 22.6892 2.59212C22.4888 2.72809 22.2105 2.92621 22.0172 3.07214C21.7561 3.26922 21.6168 3.38936 21.3726 3.60702C21.1859 3.77342 21.0849 3.8709 20.9063 4.04589C20.6979 4.24998 20.5822 4.36581 20.3851 4.58078C20.1566 4.82998 19.8228 5.23909 19.8228 5.23909V17.1025L15.9415 17.9117Z" fill="black"/>
                        <path d="M19.8914 36.3033L19.8228 36.9891L32.3171 34.3558C32.3171 34.3558 32.4542 34.0404 32.5365 33.8484C32.6188 33.6564 32.6805 33.4918 32.7422 33.3272C32.804 33.1626 32.9617 32.6689 33.0165 32.4632C33.0714 32.2574 33.0851 32.1889 33.1057 32.1066L33.1948 31.75C33.1948 31.75 33.2908 31.3385 33.3457 30.9545C33.4006 30.5705 33.4554 30.0905 33.4554 30.0905L20.9474 32.7512L20.824 33.0529C20.824 33.0529 20.5743 33.6574 20.44 34.0541C20.327 34.3879 20.1794 34.9181 20.1794 34.9181C20.1794 34.9181 20.0594 35.3586 20.0011 35.645C19.9491 35.9004 19.8914 36.3033 19.8914 36.3033Z" fill="black"/>
                        </svg>';
                echo " $price";
                
                echo "</span>";
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