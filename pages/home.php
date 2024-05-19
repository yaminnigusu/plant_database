<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin-Home</title>
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
            <p>&copy; 2024 LE JARDIN DE KAKOO</p>
        </div>
    </footer>

    <script src="../js/script.js"></script>
    
    <script>
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('nav');
            nav.classList.toggle('sticky', window.scrollY > 0);
        });
    </script>
    
</body>
</html>
