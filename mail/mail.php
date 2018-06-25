<?php

use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';

if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   return false;
   }

$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;

$mail->Username = "<your.email.sender>@gmail.com";
$mail->Password = "<password.email.sender>";
$mail->setFrom('<your.email.sender>@gmail.com',$name);
$mail->addAddress('contacto@<your.site>.com', '<site admin>');
$mail->isHTML(true);

$mail->Subject = 'Mail enviado de <your site>';
$mail->Body    = "Ha recibido un nuevo mensaje desde el formulario de contacto del Sitio Web.\n\n"."Aquí están los detalles:\n\nNombre: $name\n ----- Email: $email_address\n ----- Teléfono: $phone\n ----- Mensaje: $message";
$mail->AltBody = "Ha recibido un nuevo mensaje desde el formulario de contacto del Sitio Web.\n\n"."Aquí están los detalles:\n\nNombre: $name\n ----- Email: $email_address\n ----- Teléfono: $phone\n ----- Mensaje: $message";

if ($mail->send()) {
    if (save_mail($mail)) {
        return true;
    } else {
      return false;
    }
} else {
    return false;
}

function save_mail($mail)
{
    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);
    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);
    return $result;
}
