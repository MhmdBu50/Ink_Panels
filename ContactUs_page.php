<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Contact Form</title>
        <link href="css/Style.css" rel="stylesheet">
        <link href="css/Contact_us.css" rel="stylesheet">
        <script>
            function handleSubmit(event) {
                event.preventDefault();
                const email = "2220002615@IAU.EDU.SA";
                const subject = "Contact Form Submission";
                const firstName = document.getElementById("firstName").value;
                const lastName = document.getElementById("lastName").value;
                const userEmail = document.getElementById("userEmail").value;
                const message = document.getElementById("message").value;
    
                const body = `Name: ${firstName} ${lastName}%0D%0AEmail: ${userEmail}%0D%0AMessage: ${message}`;
                const outlookLink = `ms-outlook:compose?to=${email}&subject=${encodeURIComponent(subject)}&body=${body}`;
    
                // Try to open in Outlook first
                window.location.href = outlookLink;
    
                // Fallback to mailto if Outlook doesn't open
                setTimeout(function() {
                    const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${body}`;
                    window.location.href = mailtoLink;
                }, 1000);
            }
        </script>
    </head>

<body class="contact-body">
    <form onsubmit="handleSubmit(event)">
        <div class="container">
            <div class="form-section">
                <div class="form-header">

                    <a href="Home_page.php" class="logo-contact">
                        <svg class="logo-svg" width="93" height="83" viewBox="0 0 93 83" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.75 10.375H31C35.1109 10.375 39.0533 11.8324 41.9602 14.4267C44.867 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 45.2752 67.2345 43.0951 65.2888C40.915 63.3431 37.9581 62.25 34.875 62.25H7.75V10.375Z" fill="#D9D9D9"/>
                            <path d="M85.25 10.375H62C57.8891 10.375 53.9467 11.8324 51.0398 14.4267C48.133 17.0209 46.5 20.5395 46.5 24.2083V72.625C46.5 69.8734 47.7248 67.2345 49.9049 65.2888C52.085 63.3431 55.0419 62.25 58.125 62.25H85.25V10.375Z" fill="#D9D9D9"/>
                            <path d="M46.5 24.2083C46.5 20.5395 44.867 17.0209 41.9602 14.4267C39.0533 11.8324 35.1109 10.375 31 10.375H7.75V62.25H34.875C37.9581 62.25 40.915 63.3431 43.0951 65.2888C45.2752 67.2345 46.5 69.8734 46.5 72.625M46.5 24.2083V72.625M46.5 24.2083C46.5 20.5395 48.133 17.0209 51.0398 14.4267C53.9467 11.8324 57.8891 10.375 62 10.375H85.25V62.25H58.125C55.0419 62.25 52.085 63.3431 49.9049 65.2888C47.7248 67.2345 46.5 69.8734 46.5 72.625" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                    </a> 
                    <h3>Need some help?<br />Contact us!</h3>
                </div>
                <div class="input-group">
                    <input id="firstName" type="text" placeholder="First name" required />
                    <input id="lastName" type="text" placeholder="Last name" required />
                </div>
                <input id="userEmail" type="email" placeholder="E-mail" required /><br />
                <div class="dec">
                    <textarea id="message" placeholder="Description" required></textarea><br />
                </div>
                <div class="button-group">
                    <button class="contact-button cancel" type="reset">Cancel</button>
                    <button class="contact-button submit" type="submit">Submit</button>
                </div>
            </div>

            <div class="info-section">
                <h4>Dattebayo!</h4>
                <img width="280" height="210" viewBox="0 0 649 490" src="images/uzumaki-naruto.jpg" alt="Naruto" />
                
                <div class="icons">
                    <p>📍 <a href="https://maps.app.goo.gl/VaBkHiuTfjfN2LRA7?g_st=com.google.maps.preview.copy" target="_blank" style="color: white; text-decoration: none">Location: Imam Abdulrahman Bin Faisal University</a></p>
                    <p>📞 <a href="tel:+966013646" style="color: white; text-decoration: none">+966013646</a></p>
                    <p>💬 <a href="mailto:2220002615@IAU.EDU.SA" style="color: white; text-decoration: none">2220002615@IAU.EDU.SA</a></p>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
