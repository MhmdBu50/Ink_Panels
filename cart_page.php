<?php
session_start();
echo '<pre>Session Contents: ';
print_r($_SESSION);
echo '</pre>';


require_once "database.php";
// $product_id=$_GET["id"];
// $stmt=$db->prepare("SELECT * FROM shopping_cart WHERE MC_ID=?");
// $stmt->execute([$product_id]);





if(!isset($_SESSION["user_ID"]) && !isset($_SESSION['admin_ID'])){
    header("location:login_page.php?redirect=cart_page.php");
    exit();
}



if($_SERVER['REQUEST_METHOD'] == 'POST') {
// $user_cart=$db->prepare("SELECT cart_id, quantity from shopping_cart where user_id=? AND MC_ID=?");

    try {
        if(isset($_POST['update'])) {
            $cart_id = $_POST['cart_id'] ?? null;
            $new_quantity = (int)($_POST['quantity'] ?? 0);
            
            if($new_quantity > 0 && $cart_id) {
                $stmt = $db->prepare("SELECT mc.price 
                                     FROM shopping_cart sc 
                                     JOIN manga_comic mc ON sc.MC_ID = mc.MC_ID 
                                     WHERE sc.cart_id = ? AND sc.user_id = ?");
                $stmt->execute([$cart_id, $_SESSION['user_ID']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($product) {
                    $new_total = $product['price'] * $new_quantity;
                    $stmt = $db->prepare("UPDATE shopping_cart 
                                         SET quantity = ?, total_price = ? 
                                         WHERE cart_id = ? AND user_id = ?");
                    $stmt->execute([$new_quantity, $new_total, $cart_id, $_SESSION['user_ID']]);
                }
            }
        }
        elseif(isset($_POST['remove_item'])) {
            $cart_id = $_POST['cart_id'] ?? null;
            if($cart_id) {
                $stmt = $db->prepare("DELETE FROM shopping_cart WHERE cart_id = ? AND user_id = ?");
                $stmt->execute([$cart_id, $_SESSION['user_ID']]);
            }
        }
    } catch (PDOException $e) {
        $error = "Error updating cart: " . $e->getMessage();
    }
}
    try {
        $stmt = $db->prepare("SELECT sc.*, mc.title, mc.description, mc.cover_image, mc.price as unit_price FROM shopping_cart sc JOIN manga_comic mc ON sc.MC_ID = mc.MC_ID WHERE sc.user_id = ?");
        $stmt->execute([$_SESSION['user_ID']]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        $stmt = $db->prepare("SELECT 
                                COUNT(*) as item_count,
                                SUM(quantity) as total_quantity,
                                SUM(total_price) as subtotal
                             FROM shopping_cart
                             WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_ID']]);
        $cart_summary = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $shipping_fee = 22.00; 
        $cart_summary['shipping_fee'] = $shipping_fee;
        $cart_summary['total'] = ($cart_summary['subtotal'] ?? 0) + $shipping_fee;
        
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cart page</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/CartPage.css" rel="stylesheet">

</head>
<body>
    
    <?php require"header.php"?>

    <div class="productsmainbox"><p class="text1">Shopping Cart</p><?php if (!empty($cart_items)): ?><br><br>
        
        <p style="color: white">  </p>
        <div class="submain">
        <?php foreach($cart_items as $item):
            
            $extension = pathinfo($item['cover_image'], PATHINFO_EXTENSION);
            $mime_type = [
                'jpg' => "image/jpg",
                'jpeg' => "image/jpeg", 
                'png' => "image/png",
                'gif' => "image/gif",
                'webp' => "image/webp"
            ][strtolower($extension)] ?? "image/jpeg";

            ?>
        <div class ="itembox">
                 <img src="data:<?= $mime_type ?>;base64,<?= base64_encode($item['cover_image']) ?>">

                                <p class="title"><?php echo $item['title']?></p>
                                <div class="descbox"><p><?=$item['description'] ?? 'No description available' ?></p></div>
                <div class="price">
                            <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.00704 9.20059V0.0211346V0L7.79577 0.126808L7.38028 0.394512L7.14084 0.577679L6.96479 0.718576L6.80986 0.852429L6.57042 1.07082L6.29577 1.33852L6.11972 1.53578L6 1.68372V9.63033L1.52113 10.5814L1.30282 11.1309L1.17606 11.5324L1.07746 11.8917L1 12.2933L0.943662 12.7583L6 11.6804V14.2518L0.584507 15.393L0.415493 15.7875L0.232394 16.323L0.126761 16.7668L0.0422535 17.2106L0 17.5769L5.85915 16.323L6.19718 16.175L6.48592 15.9707L6.69014 15.7594L7.85211 14.0545L7.94366 13.8925L8.00704 13.6248V11.2577L10 10.835V14.9281L16.4155 13.5614L16.5282 13.3148L16.6338 13.0471L16.7746 12.5962L16.9014 12.0679L17 11.3704L12.007 12.4483V10.4053L16.4155 9.4683L16.5282 9.20764L16.6338 8.9188L16.7746 8.48202L16.9014 7.98888L17 7.29144L12.007 8.3693V1.00742H11.9859L11.3873 1.39488L11.1268 1.57805L10.7958 1.8528L10.5563 2.07824L10.2887 2.35298L10 2.69114V8.78495L8.00704 9.20059Z" fill="black"/>
                                <path d="M10.0352 18.6478L10 19L16.4155 17.6474L16.5282 17.3867L16.6338 17.119L16.7746 16.6752L16.8662 16.3089L16.9437 15.9003L17 15.4564L10.5775 16.8231L10.5141 16.9781L10.3169 17.4924L10.1831 17.9362L10.0915 18.3096L10.0352 18.6478Z" fill="black"/>
                            </svg><?= number_format((float)($item['unit_price'] ?? 0), 2) ?>

                </div>
                <form method="post" class="cart-item-form" id="dick">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
    
                        <div class="doradd">
                            <div><button type="button" class="doraddbuttons" onclick="adjustQuantity(this, -1)">-</button></div>
                            <span class="quantity-display"><?= $item['quantity'] ?></span>
                            <input type="hidden" name="quantity" value="<?= $item['quantity'] ?>">
                            <div><button type="button" class="doraddbuttons" onclick="adjustQuantity(this, 1)">+</button></div>

                            </div>
                            <button type="hidden" name="remove_item" class="doraddbuttons" style="display: none;">Remove</button>

                    </form>

        
         </div>
                
                        <?php endforeach; ?>
        </div>


         
        
          

    </div>
 
    
















    <div class="summarybox">
        <p class="summarycenter">Summary</p>
        <div class="summaryprices">
            
        <div>Subtotal</div>
            
            <div><svg width="30" height="25" viewBox="0 0 30 33" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.1301 15.98V0.0367075V0L13.7572 0.220245L13.024 0.685206L12.6015 1.00334L12.2908 1.24805L12.0174 1.48053L11.5949 1.85984L11.1102 2.32481L10.7995 2.66741L10.5882 2.92436V16.7264L2.68434 18.3782L2.29909 19.3326L2.07539 20.03L1.90141 20.6541L1.76471 21.3515L1.66529 22.1591L10.5882 20.287V24.7531L1.03148 26.7353L0.733223 27.4205L0.410108 28.3504L0.223695 29.1212L0.074565 29.8921L0 30.5284L10.3397 28.3504L10.9362 28.0934L11.4457 27.7386L11.8061 27.3715L13.8567 24.4105L14.0182 24.129L14.1301 23.6641V19.5528L17.6471 18.8187V25.9277L28.9685 23.5539L29.1674 23.1257L29.3538 22.6607L29.6023 21.8776L29.826 20.96L30 19.7486L21.1889 21.6207V18.0723L28.9685 16.4449L29.1674 15.9922L29.3538 15.4905L29.6023 14.7319L29.826 13.8754L30 12.6641L21.1889 14.5362V1.74972H21.1516L20.0953 2.42269L19.6355 2.74082L19.0514 3.21802L18.6288 3.60957L18.1566 4.08676L17.6471 4.67408V15.2581L14.1301 15.98Z" fill="black"/>
<path d="M17.7092 32.3882L17.6471 33L28.9685 30.6507L29.1674 30.198L29.3538 29.733L29.6023 28.9622L29.7639 28.3259L29.9006 27.6162L30 26.8454L18.6661 29.2191L18.5543 29.4883L18.2063 30.3815L17.9702 31.1524L17.8086 31.8009L17.7092 32.3882Z" fill="black"/>
><?= number_format($cart_summary['subtotal'],2) ?></svg>
</div>
            
        </div>
        <br>
         <div class="summaryprices"><div>Shipping fee</div>
        <div><svg width="30" height="25" viewBox="0 0 30 33" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.1301 15.98V0.0367075V0L13.7572 0.220245L13.024 0.685206L12.6015 1.00334L12.2908 1.24805L12.0174 1.48053L11.5949 1.85984L11.1102 2.32481L10.7995 2.66741L10.5882 2.92436V16.7264L2.68434 18.3782L2.29909 19.3326L2.07539 20.03L1.90141 20.6541L1.76471 21.3515L1.66529 22.1591L10.5882 20.287V24.7531L1.03148 26.7353L0.733223 27.4205L0.410108 28.3504L0.223695 29.1212L0.074565 29.8921L0 30.5284L10.3397 28.3504L10.9362 28.0934L11.4457 27.7386L11.8061 27.3715L13.8567 24.4105L14.0182 24.129L14.1301 23.6641V19.5528L17.6471 18.8187V25.9277L28.9685 23.5539L29.1674 23.1257L29.3538 22.6607L29.6023 21.8776L29.826 20.96L30 19.7486L21.1889 21.6207V18.0723L28.9685 16.4449L29.1674 15.9922L29.3538 15.4905L29.6023 14.7319L29.826 13.8754L30 12.6641L21.1889 14.5362V1.74972H21.1516L20.0953 2.42269L19.6355 2.74082L19.0514 3.21802L18.6288 3.60957L18.1566 4.08676L17.6471 4.67408V15.2581L14.1301 15.98Z" fill="black"/>
<path d="M17.7092 32.3882L17.6471 33L28.9685 30.6507L29.1674 30.198L29.3538 29.733L29.6023 28.9622L29.7639 28.3259L29.9006 27.6162L30 26.8454L18.6661 29.2191L18.5543 29.4883L18.2063 30.3815L17.9702 31.1524L17.8086 31.8009L17.7092 32.3882Z" fill="black"/>
</svg>  
            20.00
        </div>
        </div><br><br>
        
        <div class="summarycenter2">Total: &nbsp&nbsp   <span><svg width="25" height="25" viewBox="0 0 30 33" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.1301 15.98V0.0367075V0L13.7572 0.220245L13.024 0.685206L12.6015 1.00334L12.2908 1.24805L12.0174 1.48053L11.5949 1.85984L11.1102 2.32481L10.7995 2.66741L10.5882 2.92436V16.7264L2.68434 18.3782L2.29909 19.3326L2.07539 20.03L1.90141 20.6541L1.76471 21.3515L1.66529 22.1591L10.5882 20.287V24.7531L1.03148 26.7353L0.733223 27.4205L0.410108 28.3504L0.223695 29.1212L0.074565 29.8921L0 30.5284L10.3397 28.3504L10.9362 28.0934L11.4457 27.7386L11.8061 27.3715L13.8567 24.4105L14.0182 24.129L14.1301 23.6641V19.5528L17.6471 18.8187V25.9277L28.9685 23.5539L29.1674 23.1257L29.3538 22.6607L29.6023 21.8776L29.826 20.96L30 19.7486L21.1889 21.6207V18.0723L28.9685 16.4449L29.1674 15.9922L29.3538 15.4905L29.6023 14.7319L29.826 13.8754L30 12.6641L21.1889 14.5362V1.74972H21.1516L20.0953 2.42269L19.6355 2.74082L19.0514 3.21802L18.6288 3.60957L18.1566 4.08676L17.6471 4.67408V15.2581L14.1301 15.98Z" fill="black"/>
<path d="M17.7092 32.3882L17.6471 33L28.9685 30.6507L29.1674 30.198L29.3538 29.733L29.6023 28.9622L29.7639 28.3259L29.9006 27.6162L30 26.8454L18.6661 29.2191L18.5543 29.4883L18.2063 30.3815L17.9702 31.1524L17.8086 31.8009L17.7092 32.3882Z" fill="black"/>
            </svg></span>
            <?= number_format($cart_summary['total'], 2) ?>
        </div>
       <button class="buybtn">Buy</button>
        <hr>
        <p class="protection">Buyer protection</p>
        <p>Get a full refund if the item is not as described or not delivered.</p>

        <?php else: ?>
            <p style="color: white">Your cart is empty</p>
            <a href="index.php" class="continue-shopping">Continue Shopping</a>
        <?php endif; ?>
    </div>








    <script>
function adjustQuantity(button, change) {
    const form = button.closest('form');
    const quantityDisplay = form.querySelector('.quantity-display');
    const quantityInput = form.querySelector('input[name="quantity"]');
    const removeBtn = form.querySelector('button[name="remove_item"]');
    let currentQty = parseInt(quantityDisplay.textContent);
    
    // Calculate new quantity
    const newQty = currentQty + change;
    
    if (newQty < 1) {
        // Programmatically click the remove button
        removeBtn.click();
    } else {
        // Update display and hidden input
        quantityDisplay.textContent = newQty;
        quantityInput.value = newQty;
        
        // Create hidden input to indicate this is an update
        const updateInput = document.createElement('input');
        updateInput.type = 'hidden';
        updateInput.name = 'update';
        updateInput.value = '1';
        form.appendChild(updateInput);
        
        form.submit();
    }
}
</script>













</body>

</html>