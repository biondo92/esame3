<?php
require 'config.php';
try {
    $db = new mysqli(SERVER, USERNAME, PASS, DB);
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

if ($db->connect_error) {
    die("Connessione al database fallita: " . $db->connect_error);
}