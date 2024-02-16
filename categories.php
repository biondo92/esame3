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
                $app->AddCategory($_POST['name']);
            } else if ($action == "delete") {
                $app->DeleteCategory($_POST['id']);
            } else if ($action == "edit") {
                $app->UpdateCategory($_POST['id'], $_POST['name']);
            }
        } catch (Exception $e) {
            die($e);
        }
    }
}

$categories = $app->GetCategoriesPaginated($page, 5);
?>
<!DOCTYPE html>
<html>

<head>
    <title>CATEGORIE | AEZMA</title>
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
                <li class="active"><a href="categories.php">Categorie</a></li>
                <li><a href="users.php">Utenti</a></li>
                <li><a href="index.php">Vai al sito</a></li>
            </ul>
        </nav>
    </header>

    <section id="categories">
        <div class="wrapper">
            <h2>Categorie</h2>
            <div id="controls">
                <button class="tooltip" data-tooltip="Aggiungi Categoria" onclick="openForm(0)">
                    <i class="fas fa-plus fa-2x"></i>
                </button>
            </div>
            <table id="categories-tab">
                <thead>
                    <tr>
                        <th width="5%"># ID</th>
                        <th width="85%">Nome</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categories != null) {
                        foreach ($categories['categories'] as $cat) { ?>
                            <tr>
                                <td><?php echo $cat["id"] ?></td>
                                <td><?php echo $cat["name"] ?></td>
                                <td>
                                    <button class="tooltip" data-tooltip="Modifica Categoria" onclick="openForm(<?php echo $cat['id'] ?>,'<?php echo $cat['name'] ?>')">
                                        <i class="fas fa-pencil fa-2x"></i>
                                    </button>
                                    <form action="categories.php?action=delete&page=<?php echo $page ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $cat['id'] ?>">
                                        <button type="submit" class="tooltip" data-tooltip="Elimina Categoria">
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
                        <th>Nome</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <?php if ($categories["totalPages"] > 1) { ?>
                <div class="pagination">
                    <ul>
                        <?php if (intval($categories["page"]) > 1) { ?>
                            <li><a href="/esercitazioneJS-DB/categories.php?page=<?php echo (intval($categories["page"]) - 1) ?>"><i class="fa-solid fa-backward"></i></a></li>
                        <?php }
                        for ($i = 1; $i <= intval($categories["totalPages"]); $i++) { ?>
                            <li class="<?php if ($page == $i) {
                                            echo 'active';
                                        } ?>"><a href="/esercitazioneJS-DB/categories.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php } ?>
                        <?php if (intval($categories["page"]) < intval($categories["totalPages"])) { ?>
                            <li><a href="/esercitazioneJS-DB/categories.php?page=<?php echo (intval($categories["page"]) + 1) ?>"><i class="fa-solid fa-forward"></i></a></li>
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
                <form class="category-form" method="post">
                    <label for="name">Nome</label> <br>
                    <input type="hidden" class="cat-id" name="id" value="">
                    <input class="name" type="text" name="name" placeholder="nuova categoria" />
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

                function openForm(id, Name) {
                    var form = modal.getElementsByClassName("category-form")[0];
                    var title = modal.getElementsByClassName("title")[0];
                    var catName = modal.getElementsByClassName("name")[0];
                    var catId = modal.getElementsByClassName("cat-id")[0];

                    if (id == 0) {
                        form.action = "categories.php?action=add";
                        title.innerHTML = "Aggiungi Categoria";
                    } else {
                        catId.value = id;
                        form.action = "categories.php?action=edit";
                        catName.value = Name;
                        title.innerHTML = "Modifica Categoria";
                    }

                    modal.style.display = "block";
                }
            </script>
</body>

</html>