<?php require 'app.php';

$alert = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = "";
    $password = "";
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }

    // Verifica se la password è stata fornita
    if (isset($_POST["pass"])) {
        $password = $_POST["pass"];
    }
    if (!empty($email) && !empty($password)) {
        $risult = $app->db->query("select * from users where email = '$email' and pass = '$password'");

        if ($risult != null && $risult->num_rows > 0) {
            header('Location: ' . "/esercitazioneJS-DB/dashboard.php");
        } else {
            $alert = "email o password errati";
        }
    } else {
        $alert = "campi email e password sono obbligatori";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>AEZMA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style/style.css">
</head>

<body id="top">

    <header>
        <nav class="main-navbar">
            <div class="logo-wrapper">
                <a class="link-logo" href="#top">
                    <img class="logo" src="img/logo.png" alt="aezma">
                </a>
            </div>
            <!-- <ul class="navbar">
                <li><a href="index.php#top">Home</a></li>
                <li><a href="index.php#about">Chi Siamo</a></li>
                <li><a href="index.php#portfolio">Portfolio</a></li>
                <li><a href="index.php#contact">Contatti</a></li>
                <li><a href="#">Area Riservata</a></li>
            </ul> -->
        </nav>
    </header>

    <section id="login">
        <div class="wrapper">
            <h1><img class="logo" src="img/logo.png" alt="aezma"> AEZMA</h1>
            <div class="login-box">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input">
                        <?php if (!empty($alert)) { ?>
                            <div class="alert">
                                <?php echo $alert ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="input">
                        <input type="email" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="input">
                        <input type="password" id="pass" name="pass" placeholder="Email">
                    </div>
                    <div class="input">
                        <button type="submit">ACCEDI</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


    <footer>

        <div class="wrapper">
            <div class="text">
                <a href="#">AEZMA 2023</a> &copy; All Right Reserved
            </div>
            <ul>
                <li><a class="social" href="#"><i class="fa-brands fa-2x fa-square-facebook"></i></a></li>
                <li><a class="social" href="#"><i class="fa-brands fa-2x fa-square-instagram"></i></a></li>
            </ul>
        </div>
    </footer>
</body>

</html>