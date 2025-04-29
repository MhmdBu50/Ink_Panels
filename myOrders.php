<?php
session_start();
require_once "database.php";



$stmt = $db->prepare("
    SELECT 
           mc.title, mc.cover_image ,mc.description
    FROM orders o
    JOIN order_items oi ON o.order_ID = oi.order_ID
    JOIN manga_comic mc ON oi.MC_ID = mc.MC_ID
    WHERE o.user_ID = ?
    ORDER BY o.order_ID DESC
");
$stmt->execute([$_SESSION['user_ID']]);
$ordersData = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&family=Press+Start+2P&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
   
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap');
    </style>

  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
<link href="css/Style.css" rel="stylesheet" >
<link href="css/myOrders.css" rel="stylesheet">
</head>
<body>

  <?php require"header.php"?>
<?php foreach($ordersData as $order) :?>
    <div class="container">
        <div><button class="imgButton"><img src="data: $mimetype; base64,<?php echo base64_encode($order['cover_image']); ?>" class="img"><div id="title"><p><?php echo $order['title']?></p></div><div id="detiles"><p><?php echo $order["description"]?></p></div></button></div>
    
    </div>
    <?php endforeach; ?>
<!--
<script>
document.addEventListener('DOMContentLoaded', function() {
  const imgButtons = document.querySelectorAll('.imgButton');
  const colorThief = new ColorThief();

  imgButtons.forEach(button => {
    const img = button.querySelector('img');
    
    // Handle CORS for external images (if needed)
    img.crossOrigin = 'Anonymous';

    // Check if image is already loaded
    if (img.complete) {
      setButtonColor(button, img);
    } else {
      img.addEventListener('load', function() {
        setButtonColor(button, img);
      });
      // Fallback if image fails to load
      img.addEventListener('error', function() {
        button.style.backgroundColor = '#cccccc'; // Fallback gray
      });
    }
  });

  function setButtonColor(button, img) {
    try {
      // Extract dominant color
      const dominantColor = colorThief.getColor(img);
      const rgbColor = `rgb(${dominantColor.join(',')})`;
      
      // Apply to button background
      button.style.background = `linear-gradient(to bottom, ${rgbColor}, ${darkenColor(rgbColor, 20)})`;
      
      // Auto-adjust text color for readability
      const brightness = (dominantColor[0] * 299 + dominantColor[1] * 587 + dominantColor[2] * 114) / 1000;
      button.style.color = brightness > 128 ? '#000' : '#fff';
    } catch (e) {
      console.error("Color extraction failed:", e);
      button.style.background = '#cccccc'; // Fallback
    }
  }

  // Helper: Darken a color (for gradient)
  function darkenColor(rgbColor, percent) {
    const match = rgbColor.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
    if (!match) return rgbColor;
    const r = Math.max(0, parseInt(match[1]) - percent);
    const g = Math.max(0, parseInt(match[2]) - percent);
    const b = Math.max(0, parseInt(match[3]) - percent);
    return `rgb(${r},${g},${b})`;
  }
});
</script>


-->


</body>
</html>