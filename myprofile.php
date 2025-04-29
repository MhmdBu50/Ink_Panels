
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

     <div class="mainb">  
     <div class="title">Your Information</div>
     <form class="formc">
     <div class="infobox"> First name: <input type="text" id="FName"></div>
     
     <div class="infobox"> Last name: <input type="text" id="LName"></div>

     <div class="infobox"> Email: <input type="email" inputmode="email" id="Email"></div> 

     <div class="infobox"> Phone Number: <input type="tel" inputmode="numeric" id="phone" maxlength="12"></div>

     <div class="infobox"> Country:
     <select id="country" name="country">
     <option value="sa">Saudi Arabia</option>
     <option value="kw">Kuwait</option>
     <option value="ae">United Arab Emirates</option>
      </select></div>
      <div class="buttons">
      <input type="submit" value="Save" id="Save" class="submit">
      <input type="button" value="View Your Orders" id="vieworders" class="vieworders">
     </form>
     </div>
    </div>

</body>
</html>