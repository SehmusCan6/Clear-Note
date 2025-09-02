<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../connection/config.php';

if ($_POST) {
    $Name = $_POST['name'];
    $Email = $_POST['email'];
    $About = $_POST['about'];

    DB::insert("INSERT INTO contact_new_user(Name, Email, About) VALUES (?,?,?)", array(
        $Name,
        $Email,
        $About,

    ));
    $email_information = "Hello Manager, a new contact form has been sent from your site. Your information is below." ."<br>";
    $email_information .= "Full Name: " . $Name . "<br>";
    $email_information .="Email: " . $Email . "<br>";
    $email_information .="About: " . $About . "<br>";


    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username = '#'; #smtp email
        $mail->Password = '#'; #smtp password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
        $mail->Port       = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // For SSL/TLS
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom('#', 'ClearNotes Team'); // Sender's email and name
        $mail->addAddress('#', 'ClearNotesTeam'); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'NEW CONTACT FORM ';
        $mail->Body    = $email_information;
        $mail->AltBody = $email_information;

        $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        echo(die);
    }



    header('Location: ../index.php?login=successMAIL');
}
?>

