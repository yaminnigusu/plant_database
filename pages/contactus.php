<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../database\styles.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        
    </style>
</head>

<body  class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h1>Le Jardin de Kakoo</h1>
                </div>
                <div class="col-auto">
                <button id="navToggleButton" onclick="toggleNavVisibility()" class="btn btn-dark d-block d-lg-none">Menu</button>
            </div>
                
            </div>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="../pages/shop.php">Shop</a>
                <a href="../pages/about.php">About Us</a>
                <a href="../pages/contactus.php">Contact Us</a>
                <a href="../database/database.php">Database</a>
                <div class="col-auto">
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
            </nav>
        </div>
    </header>
            
    <div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3940.806200589748!2d38.78011627381029!3d8.989973289601116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85b929c6b7af%3A0x98d4d7aa2da96f42!2sLe%20jardin%20de%20kakoo!5e0!3m2!1sam!2set!4v1708430226241!5m2!1sam!2set" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <section id="contact" class="mt-4">
    <div class="container">
        <h3>Contact Us</h3>
        <div class="row">
            <div class="col-md-6">
                <p><a href="tel:0940384999">+251940384999</a></p>
                <p><a href="mailto:nigusuyamin@gmail.com">nigusuyamin@gmail.com</a></p>
                <p>Address: Addis Ababa, Bole Japan</p>
            </div>
            <div class="col-md-6 text-center">
                <a href="https://www.facebook.com/lejardindekakoo?mibextid=ZbWKwL"><i class="fab fa-facebook fa-2x mr-3"></i></a>
                <a href="https://www.instagram.com/lejardindekakoo?igsh=MWNzdWhwb3EwNDYwMw=="><i class="fab fa-instagram fa-2x mr-3"></i></a>
                <a href="https://t.me/LeGiardinDeKakoo"><i class="fab fa-telegram fa-2x mr-3"></i></a>
                <a href="https://wa.me/message/47J4IQW4KT3SL1"><i class="fab fa-whatsapp fa-2x mr-3"></i></a>
                <a href="https://www.tiktok.com/@plantparadise25?_t=8imWxp8efJd&_r=1"><i class="fab fa-tiktok fa-2x mr-3"></i></a>
            </div>
        </div>
    </div>
</section>


    <footer>
        <div class="container">
            <p>&copy; 2024 Gardening Essentials</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
