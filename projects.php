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

                $app->AddProject($_POST['name'], $_POST['categoryId'], $_POST['image'], $_POST['desc']);
            } else if ($action == "delete") {

                $app->DeleteProject($_POST['id']);
            } else if ($action == "edit") {

                $app->UpdateProject($_POST['id'], $_POST['name'], $_POST['categoryId'], $_POST['image'], $_POST['desc']);
            }
        } catch (Exception $e) {
            die($e);
        }
    }
}

$projects = $app->GetProjectsPaginated($page, 5);
$categories = $app->GetCategories();
?>
<!DOCTYPE html>
<html>

<head>
    <title>PROGETTI | AEZMA</title>
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
                <li class="active"><a href="projects.php">Progetti</a></li>
                <li><a href="categories.php">Categorie</a></li>
                <li><a href="users.php">Utenti</a></li>
                <li><a href="index.php">Vai al sito</a></li>
            </ul>
        </nav>
    </header>

    <section id="projects">
        <div class="wrapper">
            <h2>Progetti</h2>
            <div id="controls">
                <button class="tooltip" data-tooltip="Aggiungi Progetti" onclick="openForm({id:0})">
                    <i class="fas fa-plus fa-2x"></i>
                </button>
            </div>
            <table id="propjects-tab">
                <thead>
                    <tr>
                        <th width="5%"># ID</th>
                        <th width="75%">Nome</th>
                        <th width="10%">Categoria</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($projects != null) {
                        foreach ($projects["projects"] as $proj) { ?>
                            <tr>
                                <td><?php echo $proj["id"] ?></td>
                                <td><?php echo $proj["name"] ?></td>
                                <td><?php echo $proj["category"] ?></td>
                                <td>
                                    <button class="tooltip" data-tooltip="Modifica Progetto" onclick='openForm(<?php echo json_encode($proj) ?>)'>
                                        <i class="fas fa-pencil fa-2x"></i>
                                    </button>
                                    <form action="projects.php?action=delete&page=<?php echo $page ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $proj['id'] ?>">
                                        <button type="submit" class="tooltip" data-tooltip="Elimina Porgetto">
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
                        <th>Categoria</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <?php if ($projects["totalPages"] > 1) { ?>
                <div class="pagination">
                    <ul>
                        <?php if (intval($projects["page"]) > 1) { ?>
                            <li><a href="/esercitazioneJS-DB/projects.php?page=<?php echo (intval($projects["page"]) - 1) ?>"><i class="fa-solid fa-backward"></i></a></li>
                        <?php }
                        for ($i = 1; $i <= intval($projects["totalPages"]); $i++) { ?>
                            <li class="<?php if ($page == $i) {
                                            echo 'active';
                                        } ?>"><a href="/esercitazioneJS-DB/projects.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php } ?>
                        <?php if (intval($projects["page"]) < intval($projects["totalPages"])) { ?>
                            <li><a href="/esercitazioneJS-DB/projects.php?page=<?php echo (intval($projects["page"]) + 1) ?>"><i class="fa-solid fa-forward"></i></a></li>
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
                <form class="project-form" method="post">
                    <input type="hidden" class="pro-id" name="id" value="">

                    <label for="name">Nome</label> <br>
                    <input class="name" type="text" name="name" placeholder="nuova progetto" />
                    <br>
                    <label for="categoryId">Categoria</label>
                    <br>
                    <select name="categoryId" class="cat-id">
                        <option value="0">selezionare una categoria</option>
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                        <?php }
                        ?>
                    </select>
                    <br>
                    <label for="image">Immagine</label>
                    <br>
                    <input class="image" name="image" value="" type="text" placeholder="indirizzo web immagine">
                    <br>
                    <label for="desc">Descrizione</label>
                    <br>
                    <textarea class="desc" name="desc" id="" rows="5" placeholder="descrivi il progetto">

                    </textarea>
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

                function openForm(progetto) {
                    var form = modal.getElementsByClassName("project-form")[0];
                    var title = modal.getElementsByClassName("title")[0];
                    var catName = modal.getElementsByClassName("name")[0];
                    var proId = modal.getElementsByClassName("pro-id")[0];
                    var catId = modal.getElementsByClassName("cat-id")[0];
                    var desc = modal.getElementsByClassName("desc")[0];
                    var image = modal.getElementsByClassName("image")[0];

                    if (progetto.id == 0) {
                        form.action = "projects.php?action=add&page=<?php echo $page ?>";
                        title.innerHTML = "Aggiungi Progetto";
                    } else {
                        proId.value = progetto.id;
                        form.action = "projects.php?action=edit&page=<?php echo $page ?>";
                        catName.value = progetto.name;
                        title.innerHTML = "Modifica Progetto";
                        desc.innerHTML = progetto.description;
                        image.value = progetto.image;
                        catId.value = progetto.categoryId;

                    }

                    modal.style.display = "block";
                }
            </script>
</body>

</html>