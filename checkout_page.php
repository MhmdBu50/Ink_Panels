<?php
session_start();
require_once "database.php";

if(!isset($_SESSION["user_ID"])) {
    header("Location: login_page.php?redirect=checkout_page.php");
    exit();
}

$total = 0;
$shipping = 22.00;
$grandTotal = 0;
$cartItems = [];

$stmt = $db->prepare("
    SELECT sc.*, mc.title, mc.cover_image 
    FROM shopping_cart sc
    JOIN manga_comic mc ON sc.MC_ID = mc.MC_ID
    WHERE sc.user_id = ?
");
$stmt->execute([$_SESSION['user_ID']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartItems as $item) {
    $total += $item['total_price'];
}
$grandTotal = $total + $shipping;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
      $db->beginTransaction();
      
      if (empty($cartItems)) {
          throw new Exception("Your cart is empty");
      }

      // 1. First create the main order record
      $createOrder = $db->prepare("
          INSERT INTO orders 
          (user_ID, total_price, status) 
          VALUES (?, ?, 'pending')
      ");
      $createOrder->execute([
          $_SESSION['user_ID'],
          $grandTotal
      ]);
      $order_ID = $db->lastInsertId(); // Get the auto-generated order ID
      
      // 2. Now insert order items with the correct order_ID
      $insertOrderItem = $db->prepare("
          INSERT INTO order_items 
          (order_ID, MC_ID, quantity, price_per_item) 
          VALUES (?, ?, ?, ?)
      ");
      
      foreach ($cartItems as $item) {
          $unitPrice = $item['total_price'] / $item['quantity'];
          $insertOrderItem->execute([
              $order_ID, // This is the critical value that was missing
              $item['MC_ID'],
              $item['quantity'],
              $unitPrice
          ]);
      }
      
      // 3. Clear the shopping cart
      $clearCart = $db->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
      $clearCart->execute([$_SESSION['user_ID']]);
      
      $db->commit();
      
      header("Location:myOrders?order_id=".$order_ID);
      exit();
      
  } catch (Exception $e) {
      $db->rollBack();
      die("Error processing order: " . $e->getMessage());
  }
}
?>


<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>Checkout</title>
  <link href="css/COStyle.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <section class="form-section">
      <h1>Checkout</h1>
      <form action="" method="POST">
      <input type="hidden" name="total_amount" value="<?= $grandTotal ?>">

        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required />

        <div class="row">
          <div class="column">
            <label for="city">City</label>
            <select id="city" name="city" required>
              <option value="">Select a city</option>
              <option value="dammam">Dammam</option>
              <option value="khobar">Khobar</option>
              <option value="riyadh">Riyadh</option>
              <option value="kwc">Kuwait City</option>
              <option value="db">Dubai</option>
              <option value="adb">Abu Dhabi</option>

            </select>
          </div>
          <div class="column">
            <label for="zip">ZIP Code</label>
            <input type="text" id="zip" name="zip" required />
          </div>
        </div>

        <label for="country">Country</label>
        <select id="country" name="country" required>
          <option value="">Select a country</option>
          <option value="sa">Saudi Arabia</option>
          <option value="uae">United Arab Emirtes</option>
          <option value="kw">Kuwait</option>
        </select>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" required />

        <button type="button" class="back-button">Back</button>
        <button type="submit" class="place-order">Place Order</button>

      </form>
    </section>

    <section class="payment-section">
      <p class="payment-method">
        Payment Method:<br />
        <label><input type="radio" name="payment" value="cod" /> C.O.D</label><br />
        <label><input type="radio" name="payment" value="card" checked /> Credit/Debit Card</label>
      </p>

      <div class="card-box">
        <p>Card type</p>
        <div class="card-icons">
        
          <div class="card-icons-wrapper">
            <img src="images/mastercard.png" alt="MasterCard" class="card-icons" />
          </div>
          <div class="card-icons-wrapper">
            <img src="images/visa.png" alt="Visa" class="card-icons" />
          </div>
          <div class="card-icons-wrapper">
            <img src="images/rupay.png" alt="RuPay" class="card-icons" />
          </div>
        </div>
       

        <label for="cardName">Name on Card</label>
        <input type="text" id="cardName" name="cardName" placeholder="Name" required />

        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" name="cardNumber" placeholder="1111 2222 3333 4444" required />

        <div class="row">
          <div class="column">
            <label for="expiry">Expiration Date</label>
            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required />
          </div>
          <div class="column">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" required />
          </div>
        </div>

        <div class="summary">
        <p>Subtotal: <span><?= number_format($total, 2) ?> SAR</span></p>
        <p>Shipping: <span><?= number_format($shipping, 2) ?> SAR</span></p>
        <p><strong>Total (incl. tax):</strong> <span><strong><?= number_format($grandTotal, 2) ?> SAR</strong></span></p>
        </div>

      </div> 
    </div>
    </section>
  </div>
</body>
</html>
