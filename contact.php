<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Rim Café</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/contact_premium.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="contact-page-body">

<?php include "includes/header.php"; ?>

<main class="contact-container">
    <section class="contact-intro">
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> Thank you! Your message has been sent to the Rim Café team.
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i> Something went wrong. Please try again.
        </div>
        <?php endif; ?>

        <h1 class="premium-title">Get In Touch</h1>
        <p class="premium-text">Have a question or want to book a private table? Reach out to our team.</p>
    </section>

    <div class="contact-wrapper">
        <div class="contact-info">
            <div class="info-tile">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Location</h3>
                <p>Addis Ababa, Ethiopia<br>Bole Road, Rim Building</p>
            </div>
            <div class="info-tile">
                <i class="fas fa-phone-alt"></i>
                <h3>Phone</h3>
                <p>+251 911 22 33 44<br>+251 116 55 44 33</p>
            </div>
            <div class="info-tile">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>info@rimcaffe.com<br>support@rimcaffe.com</p>
            </div>
        </div>

        <section class="contact-form-section">
            <form action="process_contact.php" method="POST" class="premium-form">
                <div class="form-row">
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="john@example.com" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Subject</label>
                    <input type="text" name="subject" placeholder="Reservation / Feedback">
                </div>
                <div class="input-group">
                    <label>Your Message</label>
                    <textarea name="message" rows="5" placeholder="Tell us more..." required></textarea>
                </div>
                <button type="submit" class="btn-premium">Send Message <i class="fas fa-paper-plane"></i></button>
            </form>
        </section>
    </div>

    <section class="map-section">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15762.671048866!2d38.7844!3d9.0000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85cef5ab402d%3A0x8467b6b037a24c49!2sBole%2C%20Addis%20Ababa!5e0!3m2!1sen!2set!4v1700000000000" 
            width="100%" 
            height="450" 
            style="border:0; filter: grayscale(100%) invert(92%) contrast(85%);" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </section>
</main>

<?php include "includes/footer.php"; ?>

</body>
</html>