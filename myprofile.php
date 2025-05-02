<?php

  session_start();
  require_once "database.php";
  // echo '<pre>Session Contents: ';
  // print_r($_SESSION);
  // echo '</pre>';
    $user=$db->prepare("SELECT * FROM user WHERE user_ID=?");
    
    $user->execute([$_SESSION["user_ID"]]);
    $row=$user->fetch(PDO::FETCH_ASSOC);

  if($_SERVER['REQUEST_METHOD']=="POST"){

    $firstname=$_POST['first_name'];
    $lastname=$_POST['last_name'];
    $phone=$_POST['phone'];
    $country=$_POST['country'];

      $edit=$db->prepare("UPDATE user SET First_name=? , Last_name=?,`Phone number`=?, coountry=? WHERE user_ID=?");
    $edit->execute([$firstname,$lastname,$phone,$country,$_SESSION["user_ID"]]);
    }

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Profile</title>
    <link href="css/Style.css" rel="stylesheet">
    <link href="css/MyProfile.css" rel="stylesheet">
    <script>
   document.addEventListener('DOMContentLoaded', function() {
  const countrySelect = document.getElementById('country');
  const phoneInput = document.getElementById('phone');
  
  // Country codes map
  const countryCodes = {
    'sa': '+966', 
    'kw': '+965', 
    'ae': '+971'  
  };
  
  // Initialize with default country code
  updatePhoneCode();
  
  // Update phone code when country changes
  countrySelect.addEventListener('change', updatePhoneCode);
  
  // Prevent non-numeric input
  phoneInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
  
  function updatePhoneCode() {
    const selectedCountry = countrySelect.value;
    const currentPhone = phoneInput.value.replace(/^\+?\d+\s?/, '');
    phoneInput.value = countryCodes[selectedCountry] + ' ' + currentPhone;
  }
});
    
    
    </script>
</head>
<body>
<?php require"header.php"?>

     <div class="overlay" id="popup">  
          <div class="title">Your Information</div>
          <form class="formc" method="POST">
            <div class="infobox"> First name: <input type="text" id="FName" name="first_name" required></div>
            
            <div class="infobox"> Last name: <input type="text" id="LName" name="last_name" required></div>


            <div class="infobox"> Phone Number: <input type="tel" inputmode="numeric" id="phone" maxlength="12" name="phone" required></div>

            <div class="infobox"> Country:
            <select id="country" name="country">
            <option value="sa">Saudi Arabia</option>
            <option value="kw">Kuwait</option>
            <option value="ae">United Arab Emirates</option>
              </select></div>
              <div class="buttons">
              <input type="submit" value="Save" id="Save" class="submit">
              <button id="close-up" class="closebtn">&times;</button>

              </div>
          </form>
     </div>

     <div class="mainb">  
     <div class="formc">

     <div class="ainfobox"> First name: <?php echo $row['First_name']?></div>
     
     <div class="ainfobox"> Last name: <?php echo $row['Last_name']?></div>

     <div class="ainfobox"> Email: <?php echo $row['Email']?></div> 

     <div class="ainfobox"> Phone Number: <?php echo $row['Phone number']?></div>

     <div class="ainfobox"> Country: <?php echo $row['coountry']?></div>
      <div class="buttons">
      <button onclick='openPopup()' class="vieworders">Edit</button>
      <a href="myOrders.php"><input type="button" value="View Your Orders" id="vieworders" class="vieworders"></a>
    
</div>
    
    </div>

    <?php require"footer.php"?>


<script>
    // Initialize popup as hidden
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('popup');
        modal.style.display = 'none';
    });

    function openPopup() {
        document.getElementById('popup').style.display = 'flex';
    }

    document.getElementById('close-up').addEventListener('click', () => {
        document.getElementById('popup').style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === document.getElementById('popup')) {
            document.getElementById('popup').style.display = 'none';
        }
    });
</script>


</body>
</html>