<?php
require 'config.php';

class App
{
    public $db;
    public function __construct()
    {
        $this->db = new mysqli(SERVER, USERNAME, PASS, DB);
    }

    public function GetProjectsPaginated($page, $pageSize)
    {
        if ($page > 1) {
            $skip = $pageSize * ($page - 1);
        } else {
            $skip = 0;
        }
        $projects = $this->db->query("SELECT p.*,c.name AS category FROM projects AS p INNER JOIN category AS c ON p.categoryId=c.id  LIMIT $pageSize offset $skip");
        $qnt = $this->db->query("SELECT count(*) AS qnt FROM projects");
        $n = 0;
        foreach ($qnt as $q) {
            $n = $q["qnt"];
        }
        $totalPages = $n / $pageSize;
        $decimal = str_contains(strval($totalPages), ".");
        if ($decimal) {
            $str = strval($totalPages);
            $pos = strpos($str, ".");
            $num = substr($str, 0, $pos);
            $int = intval($num);
            $totalPages = $int + 1;
        }
        $result = array(
            "total" => $n,
            "page" => $page,
            "totalPages" => $totalPages,
            "projects" => array()
        );
        foreach ($projects as $key => $val) {
            $result["projects"][$key] = $val;
        }
        return $result;
    }


    public function AddProject($name, $catId, $image, $desc)
    {
        $this->db->query("INSERT INTO projects (name,categoryId,image,description) VALUES ('$name',$catId,'$image','$desc')");
    }
    public function DeleteProject($Id)
    {
        $this->db->query("DELETE FROM projects WHERE id = $Id");
    }
    public function UpdateProject($Id, $name, $catId, $image, $desc)
    {
        $this->db->query("UPDATE projects SET name = '$name', categoryId= $catId, image='$image', description='$desc' WHERE id = $Id");
    }

    public function GetCategories()
    {
        return  $this->db->query("SELECT * FROM category");
    }

    public function GetCategoriesPaginated($page, $pageSize)
    {
        if ($page > 1) {
            $skip = $pageSize * ($page - 1);
        } else {
            $skip = 0;
        }
        $categories = $this->db->query("SELECT * FROM category  LIMIT $pageSize offset $skip");
        $qnt = $this->db->query("SELECT count(*) AS qnt FROM category");
        $n = 0;
        foreach ($qnt as $q) {
            $n = $q["qnt"];
        }
        $totalPages = $n / $pageSize;
        $decimal = str_contains(strval($totalPages), ".");
        if ($decimal) {
            $str = strval($totalPages);
            $pos = strpos($str, ".");
            $num = substr($str, 0, $pos);
            $int = intval($num);
            $totalPages = $int + 1;
        }
        $result = array(
            "total" => $n,
            "page" => $page,
            "totalPages" => $totalPages,
            "categories" => array()
        );
        foreach ($categories as $key => $val) {
            $result["categories"][$key] = $val;
        }
        return $result;
    }

    public function AddCategory($catName)
    {
        $this->db->query("INSERT INTO category (name) VALUES ('$catName')");
    }

    public function UpdateCategory($id, $catName)
    {
        $this->db->query("UPDATE category SET name = '$catName' WHERE id = $id");
    }
    public function DeleteCategory($id)
    {
        $projects = $this->db->query("SELECT id FROM projects WHERE categoryId=$id");
        if ($projects->num_rows > 0) {
            foreach ($projects as $proj) {
                $this->DeleteProject($proj['id']);
            }
        }
        $this->db->query("DELETE FROM category WHERE id = $id");
    }

    public function GetUsersPaginated($page, $pageSize)
    {
        if ($page > 1) {
            $skip = $pageSize * ($page - 1);
        } else {
            $skip = 0;
        }
        $categories = $this->db->query("SELECT * FROM users  LIMIT $pageSize offset $skip");
        $qnt = $this->db->query("SELECT count(*) AS qnt FROM users");
        $n = 0;
        foreach ($qnt as $q) {
            $n = $q["qnt"];
        }
        $totalPages = $n / $pageSize;
        $decimal = str_contains(strval($totalPages), ".");
        if ($decimal) {
            $str = strval($totalPages);
            $pos = strpos($str, ".");
            $num = substr($str, 0, $pos);
            $int = intval($num);
            $totalPages = $int + 1;
        }
        $result = array(
            "total" => $n,
            "page" => $page,
            "totalPages" => $totalPages,
            "users" => array()
        );
        foreach ($categories as $key => $val) {
            $result["users"][$key] = $val;
        }
        return $result;
    }

    public function AddUsers($email, $pass)
    {
        $this->db->query("INSERT INTO users (email,pass) VALUES ('$email','$pass')");
    }

    public function UpdateUsers($id, $email, $pass)
    {
        $this->db->query("UPDATE users SET email = '$email', pass = '$pass' WHERE id = $id");
    }
    public function DeleteUsers($id)
    {

        $this->db->query("DELETE FROM users WHERE id = $id");
    }
}


try {
    // $db = new mysqli(SERVER, USERNAME, PASS, DB);
    $app = new App();
} catch (Exception $e) {

    $db = new mysqli(SERVER, USERNAME, PASS, "");
    if ($db->connect_error) {
        die("Connessione al database fallita: " . $db->connect_error);
    }

    $result = $db->query(INIT_DB_QUERY);
    if ($result) {
        $db->query("use esercitazione3");
        $db->query(CREATE_TABLE_USERS);
        $db->query(CREATE_TABLE_CATEGORY);
        $db->query(CREATE_TABLE_PROJECTS);
        $db->query("use esercitazione3");
        $db->query(INSERT_USERS);
        $db->query(INSERT_CATEGORY);
    }
}

if ($app->db->connect_error) {
    die("Connessione al database fallita: " . $db->connect_error);
}
