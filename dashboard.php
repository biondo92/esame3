<?php
require 'app.php';
$jsonString = file_get_contents("data.json");
$jsonData = json_decode($jsonString, true);

$email_inviata = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $messaggio = $_POST["messaggio"];
    $email_inviata = true;
}
$projects = $app->db->query('select count(*) as qnt from projects');
$categories =  $app->db->query('select count(*) as qnt from category');
$users =  $app->db->query('select count(*) as qnt from users');
$stat = array();
foreach ($projects as $p) {
    $stat['prog'] = $p['qnt'];
}
foreach ($categories as $p) {
    $stat['cat'] = $p['qnt'];
}
foreach ($users as $p) {
    $stat['users'] = $p['qnt'];
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
            <ul class="navbar">
                <li class="active"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="projects.php">Progetti</a></li>
                <li><a href="categories.php">Categorie</a></li>
                <li><a href="users.php">Utenti</a></li>
                <li><a href="index.php">Vai al sito</a></li>
            </ul>
        </nav>
    </header>

    <section id="dashboard">
        <div class="wrapper">
            <h2>BENVENUTO</h2>
            <div class="square">
                <div class="stat">
                    <h4>Progetti: <strong><?php echo $stat['prog'] ?></strong> </h4>
                    <a href="projects.php">Gestisci</a>
                </div>
                <div class="stat">
                    <h4>Categorie: <strong><?php echo $stat['cat'] ?></strong> </h4>
                    <a href="categories.php">Gestisci</a>
                </div>
                <div class="stat">
                    <h4>Utenti: <strong><?php echo $stat['users'] ?></strong> </h4>
                    <a href="users.php">Gestisci</a>
                </div>
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