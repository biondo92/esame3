<?php
require 'app.php';

$page = 1;
if (isset($_GET["page"])) {
    $page =  $_GET["page"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = "";

    if (isset($_GET["action"])) {
        $action =  $_GET["action"];
    }

    if (!empty($action)) {
        try {
            if ($action == "add") {
                $app->AddUsers($_POST['email'], $_POST['pass']);
            } else if ($action == "delete") {
                $app->DeleteUsers($_POST['id']);
            } else if ($action == "edit") {
                $app->UpdateUsers($_POST['id'], $_POST['email'], $_POST['pass']);
            }
        } catch (Exception $e) {
            die($e);
        }
    }
}

$users = $app->GetUsersPaginated($page, 1);
?>
<!DOCTYPE html>
<html>

<head>
    <title>UTENTI | AEZMA</title>
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="projects.php">Progetti</a></li>
                <li><a href="categories.php">Categorie</a></li>
                <li class="active"><a href="users.php">Utenti</a></li>
                <li><a href="index.php">Vai al sito</a></li>
            </ul>
        </nav>
    </header>

    <section id="users">
        <div class="wrapper">
            <h2>Utenti</h2>
            <div id="controls">
                <button class="tooltip" data-tooltip="Aggiungi Utente" onclick="openForm(0)">
                    <i class="fas fa-plus fa-2x"></i>
                </button>
            </div>
            <table id="users-tab">
                <thead>
                    <tr>
                        <th width="5%"># ID</th>
                        <th width="85%">Email</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users != null) {
                        foreach ($users['users'] as $us) { ?>
                            <tr>
                                <td><?php echo $us["id"] ?></td>
                                <td><?php echo $us["email"] ?></td>
                                <td>
                                    <button class="tooltip" data-tooltip="Modifica Utente" onclick="openForm(<?php echo $us['id'] ?>,'<?php echo $us['email'] ?>','<?php echo $us['pass'] ?>')">
                                        <i class="fas fa-pencil fa-2x"></i>
                                    </button>
                                    <form action="users.php?action=delete&page=<?php echo $page ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $us['id'] ?>">
                                        <button type="submit" class="tooltip" data-tooltip="Elimina Utente">
                                            <i class="fas fa-trash fa-2x"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php  } else { ?>
                        <tr>
                            <td colspan="3">
                                nessun dato presente nella tabella
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th># ID</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <?php if ($users["totalPages"] > 1) { ?>
                <div class="pagination">
                    <ul>
                        <?php if (intval($users["page"]) > 1) { ?>
                            <li><a href="/esercitazioneJS-DB/users.php?page=<?php echo (intval($users["page"]) - 1) ?>"><i class="fa-solid fa-backward"></i></a></li>
                        <?php }
                        for ($i = 1; $i <= intval($users["totalPages"]); $i++) { ?>
                            <li class="<?php if ($page == $i) {
                                            echo 'active';
                                        } ?>"><a href="/esercitazioneJS-DB/users.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php } ?>
                        <?php if (intval($users["page"]) < intval($users["totalPages"])) { ?>
                            <li><a href="/esercitazioneJS-DB/users.php?page=<?php echo (intval($users["page"]) + 1) ?>"><i class="fa-solid fa-forward"></i></a></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
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

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="title"></h2>
            <hr />
            <div class="wrapper">
                <form class="user-form" method="post">
                    <label for="name">Email</label> <br>
                    <input type="hidden" class="user-id" name="id" value="">
                    <input class="email" type="text" name="email" placeholder="nuovo utente" />
                    <br>
                    <label for="pass">Password</label> <br>
                    <input id="pass" type="password" class="pass" name="pass" value="">
                    <button type="button" onclick="togglePass()"><i id="toggler" class="fa-solid fa-eye"></i></button>
                    <br>
                    <button type="submit">Salva</button>
                </form>
                <div>
                </div>

            </div>

            <script>
                var modal = document.getElementById("myModal");
                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

                function openForm(id, Email, Pass) {
                    var form = modal.getElementsByClassName("user-form")[0];
                    var title = modal.getElementsByClassName("title")[0];
                    var email = modal.getElementsByClassName("email")[0];
                    var userId = modal.getElementsByClassName("user-id")[0];
                    var pass = modal.getElementsByClassName("pass")[0];

                    if (id == 0) {
                        form.action = "users.php?action=add";
                        title.innerHTML = "Aggiungi Utente";
                    } else {
                        userId.value = id;
                        form.action = "users.php?action=edit";
                        email.value = Email;
                        title.innerHTML = "Modifica Utente";
                        pass.value = Pass;
                    }

                    modal.style.display = "block";
                }

                function togglePass() {
                    var toggler = document.getElementById("toggler");
                    var input = document.getElementById("pass");
                    if (toggler.className == "fa-solid fa-eye") {
                        toggler.className = "fa-solid fa-eye-slash";
                        input.type = "text";
                    } else {
                        toggler.className = "fa-solid fa-eye";
                        input.type = "password";
                    }
                }
            </script>
</body>

</html>