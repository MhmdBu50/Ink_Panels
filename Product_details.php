<?php
  session_start();
  require_once 'database.php'; // Include your database connection file


  // echo '<pre>Session Contents: ';
  // print_r($_SESSION);
  // echo '</pre>';


  error_reporting(E_ALL);
  ini_set('display_errors', 1);



  $product_id = $_GET['id'] ?? null;

  if (!is_numeric($product_id)) {
    die("Invalid product ID");
  }



// Handle "Add to Cart"
  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
    echo "<pre>POST Data: ";
    // print_r($_POST);
    echo "</pre>";
    
    if(!isset($_SESSION['user_ID'])) {
        header('location: login_page.php?redirect=product_details.php?id='.$product_id);
        exit();
    }

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    // echo "Quantity: $quantity<br>";
    
    try {
        // Get product price
        $stmt = $db->prepare("SELECT price FROM manga_comic WHERE MC_ID = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$product) {
            throw new Exception("Product not found");
        }

        $price = $product['price'];
        $total_price = $price * $quantity;
        echo "Price: $price, Total: $total_price<br>";

        $stmt = $db->prepare("SELECT cart_id, quantity FROM shopping_cart WHERE user_id = ? AND MC_ID = ?");
        $stmt->execute([$_SESSION['user_ID'], $product_id]);
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "Existing item: ";
        print_r($existing_item);
        echo "<br>";

        if($existing_item) {
            $new_quantity = $quantity + $existing_item['quantity'];
            $new_total = $price * $new_quantity;
            
            $stmt = $db->prepare("UPDATE shopping_cart SET quantity = ?, total_price = ? WHERE cart_id = ?");
            $result = $stmt->execute([$new_quantity, $new_total, $existing_item['cart_id']]);
            echo "Update result: " . ($result ? "Success" : "Failed") . "<br>";
        } else {
            $stmt = $db->prepare("INSERT INTO shopping_cart (user_id, MC_ID, quantity, total_price, created_at) 
                                 VALUES (?, ?, ?, ?, NOW())");
            $result = $stmt->execute([$_SESSION['user_ID'], $product_id, $quantity, $total_price]);
            echo "Insert result: " . ($result ? "Success" : "Failed") . "<br>";
            echo "Last insert ID: " . $db->lastInsertId() . "<br>";
        }
        
        
        header("Location: cart_page.php");
        exit();

    } catch(PDOException $e) {
        echo "<div style='color:red;padding:10px;border:1px solid red;'>Database Error: " . $e->getMessage() . "</div>";
    }
  }
  
// Handle "Buy Now"
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['buy_now'])) {
 
    if(!isset($_SESSION['user_ID'])) {
        header('location: login_page.php?redirect=product_details.php?id='.$product_id);
        exit();
    }

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $_SESSION['checkout_now'] = [
        'product_id' => $product_id,
        'quantity' => $quantity
    ];

    header("Location: checkout_page.php");
    exit();


}





  $conn=$db->prepare("SELECT * FROM manga_comic WHERE MC_ID=?"); 
  $conn->execute([$product_id]);

  $mimetype=[
    'jpg'=>"image/jpg",
    'jpeg'=>"image/jepg",
    'png'=>"image/png",
    'gif'=>"image/gif",
    'webp'=>"image/webp"
  ];

  $row = $conn->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Details page</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/Product_details.css" rel="stylesheet">

</head>
<body class="body-style">
  
    <?php require"header.php"?>


    <div class="details-grid-container">
        <div class=" manga-img" style="grid-area: box-1;">
            <img src="data: $mimetype; base64,<?php echo base64_encode($row['cover_image']); ?>">
            </div>
        <div class="details-container overflowen" style="grid-area: box-2;">
            <h1 class="title"><?php echo $row['title']?> <span class="release-date"><?php echo substr($row['release_date'], 0,4)?></span></h1>
            <div class="meta-row">
                <span>Author: <?php echo $row['author']?></span>
                <span>Category: <a href="#"><u><?php echo $row['category']?></u></a></span>
                <span>Genre: <?php echo $row['genre']?></span>
            </div>
            
            <p class="details inter-font"><?php echo $row['description']?> </p>

        </div>
        <div class="money-lost-wrapper">
            <div class="money-lost" style="grid-area: box-3;">
                <div class="selector-grid-container">
                    <div class="inter-font vol-qua" style="grid-area: box3-1;">
                        <span>Unit Price: <div class="price-amount">
                        <svg width="27" height="29" viewBox="0 0 34 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.9415 17.9117V0.0411447V0C15.9415 0 15.6897 0.148767 15.53 0.246868C15.2098 0.44365 15.0263 0.548935 14.7209 0.768034C14.5346 0.901663 14.4349 0.983096 14.2546 1.12462C14.1197 1.23048 14.0435 1.28918 13.9117 1.39892C13.7984 1.4933 13.7338 1.55028 13.628 1.64355L13.61 1.6595C13.4251 1.82251 13.3232 1.91588 13.1437 2.08466C12.9311 2.28441 12.8091 2.39388 12.6088 2.60583C12.4707 2.75194 12.3955 2.83618 12.2659 2.98985C12.1726 3.10046 12.0327 3.27786 12.0327 3.27786V18.7483L3.31007 20.5998C3.31007 20.5998 3.03313 21.2451 2.88491 21.6695C2.77935 21.9718 2.72828 22.1441 2.63804 22.4513C2.5582 22.7231 2.51091 22.875 2.44604 23.1507C2.37483 23.4534 2.34409 23.6254 2.29517 23.9325C2.23914 24.2841 2.18545 24.8377 2.18545 24.8377L12.0327 22.7393V27.7452L1.48599 29.9671C1.48599 29.9671 1.27558 30.4311 1.15684 30.7351C1.00029 31.1358 0.917708 31.3635 0.800248 31.7774C0.705546 32.1111 0.667126 32.3023 0.594524 32.6415C0.522627 32.9773 0.480542 33.1657 0.429946 33.5055C0.388649 33.7828 0.347656 34.2187 0.347656 34.2187L11.7584 31.7774C11.7584 31.7774 12.1727 31.6279 12.4168 31.4894C12.6507 31.3566 12.7764 31.2686 12.9791 31.0917C13.1474 30.9447 13.3768 30.6802 13.3768 30.6802L15.6398 27.3612C15.6398 27.3612 15.6946 27.2652 15.8181 27.0458C15.9415 26.8263 15.9415 26.5246 15.9415 26.5246V21.9164L19.8228 21.0935V29.0619L32.3171 26.4012C32.3171 26.4012 32.4557 26.1108 32.5365 25.9212C32.6224 25.7199 32.6676 25.6057 32.7422 25.4C32.8648 25.0624 32.9202 24.8682 33.0165 24.5222C33.1273 24.1243 33.1837 23.899 33.2634 23.4936C33.3667 22.9682 33.4554 22.1358 33.4554 22.1358L23.7316 24.2342V20.2569L32.3171 18.4328C32.3171 18.4328 32.4563 18.1258 32.5365 17.9254C32.6234 17.7083 32.6653 17.5839 32.7422 17.3631C32.857 17.0336 32.919 16.8477 33.0165 16.5127C33.1248 16.1411 33.1846 15.9317 33.2634 15.5527C33.3724 15.0284 33.4554 14.1949 33.4554 14.1949L23.7316 16.2933V1.96123L23.567 2.05723C23.4024 2.15324 22.9925 2.38639 22.6892 2.59212C22.4888 2.72809 22.2105 2.92621 22.0172 3.07214C21.7561 3.26922 21.6168 3.38936 21.3726 3.60702C21.1859 3.77342 21.0849 3.8709 20.9063 4.04589C20.6979 4.24998 20.5822 4.36581 20.3851 4.58078C20.1566 4.82998 19.8228 5.23909 19.8228 5.23909V17.1025L15.9415 17.9117Z" fill="black"/>
                        <path d="M19.8914 36.3033L19.8228 36.9891L32.3171 34.3558C32.3171 34.3558 32.4542 34.0404 32.5365 33.8484C32.6188 33.6564 32.6805 33.4918 32.7422 33.3272C32.804 33.1626 32.9617 32.6689 33.0165 32.4632C33.0714 32.2574 33.0851 32.1889 33.1057 32.1066L33.1948 31.75C33.1948 31.75 33.2908 31.3385 33.3457 30.9545C33.4006 30.5705 33.4554 30.0905 33.4554 30.0905L20.9474 32.7512L20.824 33.0529C20.824 33.0529 20.5743 33.6574 20.44 34.0541C20.327 34.3879 20.1794 34.9181 20.1794 34.9181C20.1794 34.9181 20.0594 35.3586 20.0011 35.645C19.9491 35.9004 19.8914 36.3033 19.8914 36.3033Z" fill="black"/>
                        </svg>
                        <?php echo number_format($row['price'], 0, 2); ?>
                    </div>
                </span>
                    </div>
                    <div class="inter-font vol-qua dropdown"  style="grid-area: box3-2;">
                        <span>Quantity</span>
                        <div class="dropdown-container" id="dropdown-quantity">
                            <button class="dropbtn dromdown-font" id="dropbtn-quantity">
                              <span id="selected-text-quantity" >1</span>
                              <svg class="arrow" viewBox="0 0 28 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.2614 15.6035L8.54883 10.6743H19.974L14.2614 15.6035Z" fill="#1D1B20"/>
                              </svg>
                            </button>
                            <div class="dropdown-content">
                              <?php 
                              $avaliable_quantity = $row['stock_quantity'];
                                if ($avaliable_quantity > 0) {
                                    for ($i = 1; $i <= $avaliable_quantity; $i++) {
                                        echo '<a href="#" onclick="selectItem(this)">' . $i . '</a>';
                                    }
                                } else {
                                    echo '<a href="#" style="color: gray; pointer-events: none;">Out of Stock</a>';
                                }
                              ?>
                            </div>
                        </div>  
                    </div>
                
                <div style="grid-area: box3-5;" id="total-price">
                    <p>Total Price: <svg width="34" height="37" viewBox="0 0 34 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.9415 17.9117V0.0411447V0C15.9415 0 15.6897 0.148767 15.53 0.246868C15.2098 0.44365 15.0263 0.548935 14.7209 0.768034C14.5346 0.901663 14.4349 0.983096 14.2546 1.12462C14.1197 1.23048 14.0435 1.28918 13.9117 1.39892C13.7984 1.4933 13.7338 1.55028 13.628 1.64355L13.61 1.6595C13.4251 1.82251 13.3232 1.91588 13.1437 2.08466C12.9311 2.28441 12.8091 2.39388 12.6088 2.60583C12.4707 2.75194 12.3955 2.83618 12.2659 2.98985C12.1726 3.10046 12.0327 3.27786 12.0327 3.27786V18.7483L3.31007 20.5998C3.31007 20.5998 3.03313 21.2451 2.88491 21.6695C2.77935 21.9718 2.72828 22.1441 2.63804 22.4513C2.5582 22.7231 2.51091 22.875 2.44604 23.1507C2.37483 23.4534 2.34409 23.6254 2.29517 23.9325C2.23914 24.2841 2.18545 24.8377 2.18545 24.8377L12.0327 22.7393V27.7452L1.48599 29.9671C1.48599 29.9671 1.27558 30.4311 1.15684 30.7351C1.00029 31.1358 0.917708 31.3635 0.800248 31.7774C0.705546 32.1111 0.667126 32.3023 0.594524 32.6415C0.522627 32.9773 0.480542 33.1657 0.429946 33.5055C0.388649 33.7828 0.347656 34.2187 0.347656 34.2187L11.7584 31.7774C11.7584 31.7774 12.1727 31.6279 12.4168 31.4894C12.6507 31.3566 12.7764 31.2686 12.9791 31.0917C13.1474 30.9447 13.3768 30.6802 13.3768 30.6802L15.6398 27.3612C15.6398 27.3612 15.6946 27.2652 15.8181 27.0458C15.9415 26.8263 15.9415 26.5246 15.9415 26.5246V21.9164L19.8228 21.0935V29.0619L32.3171 26.4012C32.3171 26.4012 32.4557 26.1108 32.5365 25.9212C32.6224 25.7199 32.6676 25.6057 32.7422 25.4C32.8648 25.0624 32.9202 24.8682 33.0165 24.5222C33.1273 24.1243 33.1837 23.899 33.2634 23.4936C33.3667 22.9682 33.4554 22.1358 33.4554 22.1358L23.7316 24.2342V20.2569L32.3171 18.4328C32.3171 18.4328 32.4563 18.1258 32.5365 17.9254C32.6234 17.7083 32.6653 17.5839 32.7422 17.3631C32.857 17.0336 32.919 16.8477 33.0165 16.5127C33.1248 16.1411 33.1846 15.9317 33.2634 15.5527C33.3724 15.0284 33.4554 14.1949 33.4554 14.1949L23.7316 16.2933V1.96123L23.567 2.05723C23.4024 2.15324 22.9925 2.38639 22.6892 2.59212C22.4888 2.72809 22.2105 2.92621 22.0172 3.07214C21.7561 3.26922 21.6168 3.38936 21.3726 3.60702C21.1859 3.77342 21.0849 3.8709 20.9063 4.04589C20.6979 4.24998 20.5822 4.36581 20.3851 4.58078C20.1566 4.82998 19.8228 5.23909 19.8228 5.23909V17.1025L15.9415 17.9117Z" fill="black"/>
                        <path d="M19.8914 36.3033L19.8228 36.9891L32.3171 34.3558C32.3171 34.3558 32.4542 34.0404 32.5365 33.8484C32.6188 33.6564 32.6805 33.4918 32.7422 33.3272C32.804 33.1626 32.9617 32.6689 33.0165 32.4632C33.0714 32.2574 33.0851 32.1889 33.1057 32.1066L33.1948 31.75C33.1948 31.75 33.2908 31.3385 33.3457 30.9545C33.4006 30.5705 33.4554 30.0905 33.4554 30.0905L20.9474 32.7512L20.824 33.0529C20.824 33.0529 20.5743 33.6574 20.44 34.0541C20.327 34.3879 20.1794 34.9181 20.1794 34.9181C20.1794 34.9181 20.0594 35.3586 20.0011 35.645C19.9491 35.9004 19.8914 36.3033 19.8914 36.3033Z" fill="black"/>
                        </svg>
                        <span id="total-price-amount"><?php echo $row['price'] ?></span></p>
                </div>
                <div class="add-to-cart" style="grid-area: box3-6;">
                    <form method="post" action="product_details.php?id=<?php echo $product_id; ?>" >
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['MC_ID'] ?>">
                        <input type="hidden" id="selected-quantity" name="quantity" value="1">
                        <button type="submit" id="add-to-cart" class="button-base">
                            <svg width="50" height="48" viewBox="0 0 60 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2H10L15.36 28.78C15.5429 29.7008 16.0438 30.5279 16.7751 31.1166C17.5064 31.7053 18.4214 32.018 19.36 32H38.8C39.7386 32.018 40.6536 31.7053 41.3849 31.1166C42.1162 30.5279 42.6171 29.7008 42.8 28.78L46 12H12M20 42C20 43.1046 19.1046 44 18 44C16.8954 44 16 43.1046 16 42C16 40.8954 16.8954 40 18 40C19.1046 40 20 40.8954 20 42ZM42 42C42 43.1046 41.1046 44 40 44C38.8954 44 38 43.1046 38 42C38 40.8954 38.8954 40 40 40C41.1046 40 42 40.8954 42 42Z" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Add to cart
                        </button>
                    </form>
                    <form method="post" action="checkout_page.php" >
                        <input type="hidden" name="buy_now" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['MC_ID'] ?>">
                        <input type="hidden" id="selected-quantity" name="quantity" value="1">
                    <button class="you-are-rich button-base add-to-cart" class="add"><span>Buy now</span></button>
                    </form>

                </div>

                </div>
            </div>
        </div>
        <div style="grid-area: box-4;">
            <h1 class="More-like-this">More like this!</h1>
            <div class="More-container">
                <div><button class="img_button"><img src="/images/aka chan.png" class="img"><div id="title"><p>aka chan boku</p></div></button></div>
                <div><button class="img_button"><img src="/images/fist of the north star.png" class="img"><div id="title"><p>acka chan boku</p></button></div>
                <div><button class="img_button"><img src="/images/hajme no ippo.png" class="img"><div id="title"><p>acka chan boku</p></button></div>
                <div><button class="img_button"><img src="/images/world trigger.png"class="img"><div id="title"><p>acka chan boku</p></button></div>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 32L32 24M32 24L24 16M32 24H16M44 24C44 35.0457 35.0457 44 24 44C12.9543 44 4 35.0457 4 24C4 12.9543 12.9543 4 24 4C35.0457 4 44 12.9543 44 24Z" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                    
            </div>
        </div>
    </div>
    </div>
    <?php require"footer.php"?>

</body>
<script>
    // This handles opening/closing dropdowns
    document.querySelectorAll(".dropbtn").forEach(button => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        const container = button.closest(".dropdown-container");
        container.classList.toggle("open");
      });
    });
  
    // This updates selected text
    function selectItem(element) {
      const text = element.textContent;
      const container = element.closest(".dropdown-container");
      const display = container.querySelector("span#selected-text, span[id^='selected-text']");
      if (display) {
        display.textContent = text;
      }
      container.classList.remove("open");
    }
  
    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
      document.querySelectorAll(".dropdown-container.open").forEach(container => {
        if (!container.contains(e.target)) {
          container.classList.remove("open");
        }
      });
    });

    // Update the hidden quantity field when dropdown changes
document.querySelector('#dropdown-quantity').addEventListener('click', function(e) {
    if(e.target.tagName === 'A') {
        const quantity = e.target.textContent;
        document.getElementById('selected-quantity').value = quantity;
        console.log("Quantity set to:", quantity);
    }
});

// Debug form submission
document.querySelector('form').addEventListener('submit', function(e) {
    console.log("Form submitted with data:", {
        add_to_cart: this.elements['add_to_cart'].value,
        product_id: this.elements['product_id'].value,
        quantity: this.elements['quantity'].value
    });
});
// Get initial price from PHP
const unitPrice = <?php echo $row['price']; ?>;
let currentQuantity = 1;

function updateTotalPrice() {
    const totalElement = document.getElementById('total-price-amount');
    const total = (unitPrice * currentQuantity).toFixed(2);
    totalElement.textContent = total;
}

// Update quantity and total price when selection changes
document.querySelector('#dropdown-quantity').addEventListener('click', function(e) {
    if(e.target.tagName === 'A') {
        currentQuantity = parseInt(e.target.textContent);
        document.getElementById('selected-quantity').value = currentQuantity;
        document.getElementById('selected-text-quantity').textContent = currentQuantity;
        updateTotalPrice();
    }
});

// Initial calculation
updateTotalPrice();
  </script>
  
</html>