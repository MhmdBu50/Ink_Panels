<header class="menu">
    <?php 
if(isset($_SESSION['admin_ID'])): ?>
  <a href="homePageAdminstrator.php" class="logo">
        <svg class="logo" width="93" height="83" viewBox="0 0 93 83" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.75 10.375H31C35.1109 10.375 39.0533 11.8324 41.9602 14.4267C44.867 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 45.2752 67.2345 43.0951 65.2888C40.915 63.3431 37.9581 62.25 34.875 62.25H7.75V10.375Z" fill="#D9D9D9"/>
            <path d="M85.25 10.375H62C57.8891 10.375 53.9467 11.8324 51.0398 14.4267C48.133 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 47.7248 67.2345 49.9049 65.2888C52.085 63.3431 55.0419 62.25 58.125 62.25H85.25V10.375Z" fill="#D9D9D9"/>
            <path d="M46.5 24.2083C46.5 20.5395 44.867 17.0209 41.9602 14.4267C39.0533 11.8324 35.1109 10.375 31 10.375H7.75V62.25H34.875C37.9581 62.25 40.915 63.3431 43.0951 65.2888C45.2752 67.2345 46.5 69.8734 46.5 72.625M46.5 24.2083V72.625M46.5 24.2083C46.5 20.5395 48.133 17.0209 51.0398 14.4267C53.9467 11.8324 57.8891 10.375 62 10.375H85.25V62.25H58.125C55.0419 62.25 52.085 63.3431 49.9049 65.2888C47.7248 67.2345 46.5 69.8734 46.5 72.625" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>     
    <?php else : ?>
    <a href="Home_page.php" class="logo">
        <svg class="logo" style="padding-left: 0px;" width="93" height="83" viewBox="0 0 93 83" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.75 10.375H31C35.1109 10.375 39.0533 11.8324 41.9602 14.4267C44.867 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 45.2752 67.2345 43.0951 65.2888C40.915 63.3431 37.9581 62.25 34.875 62.25H7.75V10.375Z" fill="#D9D9D9"/>
            <path d="M85.25 10.375H62C57.8891 10.375 53.9467 11.8324 51.0398 14.4267C48.133 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 47.7248 67.2345 49.9049 65.2888C52.085 63.3431 55.0419 62.25 58.125 62.25H85.25V10.375Z" fill="#D9D9D9"/>
            <path d="M46.5 24.2083C46.5 20.5395 44.867 17.0209 41.9602 14.4267C39.0533 11.8324 35.1109 10.375 31 10.375H7.75V62.25H34.875C37.9581 62.25 40.915 63.3431 43.0951 65.2888C45.2752 67.2345 46.5 69.8734 46.5 72.625M46.5 24.2083V72.625M46.5 24.2083C46.5 20.5395 48.133 17.0209 51.0398 14.4267C53.9467 11.8324 57.8891 10.375 62 10.375H85.25V62.25H58.125C55.0419 62.25 52.085 63.3431 49.9049 65.2888C47.7248 67.2345 46.5 69.8734 46.5 72.625" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a> 
<?php endif; ?>      
    <form action="searchResults.php" method="GET" class="search-container">
        <button type="submit" class="search-and-filter-buttons">  
            <svg class="search-icon" width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 29C25.4183 29 29 25.4183 29 21C29 16.5817 25.4183 13 21 13C16.5817 13 13 16.5817 13 21C13 25.4183 16.5817 29 21 29Z" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M31.0002 31L26.7002 26.7" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>                     
        <input class="search poppins-regular" type="text" name="manga_name" placeholder="Search ..." style="color: #ABB7C2;"> 
        <button type="button" class="search-and-filter-buttons">
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
    </form>
    <div class="signlog-container">
        <?php if(isset($_SESSION['user_ID']) || isset($_SESSION['admin_ID'])):?>
            <a href="log_out.php"><button class="signlog" id="login">Log Out</button></a>
        <?php else:  ?>
            <a href="sign_up.php"><button class="signlog" id="signup">Sign up</button></a>
            <a href="login_page.php"><button class="signlog" id="login">Log in</button></a>
        <?php endif; ?>
    </div>
    
    <script>
    document.querySelector('.search').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.querySelector('form.search-container').submit();
        }
    });
    </script>
</header>