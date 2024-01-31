<?php
const HOST = "smtp.gmail.com";
const PORT = 587;
const USERNAME = "micheleiacuitto92@gmail.com";
const PASS = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';

$to = "";
$mex = "";
$result = "";
$isinvalid = false;

if (isset($_POST["email"])) {
    $to = $_POST["email"];
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $result .= "Campo email non valido <br/>";
        $isinvalid = true;
        $to = "";
    }
} else {
    $result .= "Campo email obbligatorio <br/>";
    $isinvalid = true;
    $to = "";
}

if (isset($_POST["message"])) {
    $mex = $_POST["message"];

    if (empty($mex)) {
        $result .= "Campo messaggio non può essere vuoto <br/>";
        $isinvalid = true;
        $mex = "";
    } else {
        if (filter_var($mex, FILTER_SANITIZE_STRING)) {
            $result .= "Campo messaggio contiene caratteri non consentiti <br/>";
            $isinvalid = true;
            $mex = "";
        }
        if (strlen($mex) > 120) {
            $result .= "Campo messaggio eccede il numero di caratteri consentito : max 120 <br/>";
            $isinvalid = true;
            $mex = "";
        }
    }
} else {
    $result .= "Campo messaggio obbligatorio <br/>";
    $isinvalid = true;
    $mex = "";
}

if ($isinvalid) {

    $result .= "impossibile procedere sono presenti errori di validazione";
    header("location: /esercitazione 2 PHP-SCSS/index.php?result=$result&email=$to&messaggio=$mex");
}

$mail = new PHPMailer(true);
try {
    // Configure the PHPMailer instance
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = HOST;
    $mail->SMTPAuth = true;
    $mail->Username = USERNAME;
    $mail->Password = PASS;
    $mail->SMTPSecure = "tls";
    $mail->Port = PORT;

    // Set the sender, recipient, subject, and body of the message

    $mail->setFrom(USERNAME, 'no-reply');

    $mail->addAddress($to);
    $mail->isHTML(false);
    $mail->Subject = "INVIO EMAIL DAL SITO AEZMA 2021";
    $mail->Body = $mex;

    // Send the message
    $mail->send();

    $result = "Grazie per averci contattato";
} catch (Exception $e) {
    $m = $e->errorMessage();
    var_dump($m);
    die();
    $result = "Si è verificato un errore durante l'invio dell'email-$m";
}
header("location: /esercitazione 2 PHP-SCSS/index.php?result=$result");
