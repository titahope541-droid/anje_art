
<?php
// sendmail.php  (complete replacement)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';   // adjust path if vendor is elsewhere

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'titahope541@gmail.com';      // ← YOUR GMAIL
    $mail->Password   = 'gdfj mhfx wvbd fldv';          // ← 16-char app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    //$mail->SMTPDebug = 3;           // 3 = client + server messages
//$mail->Debugoutput = 'html';    // print to browser

    // Who the email comes FROM (visitor)
    $mail->setFrom('titahope541@gmail.com', $_POST['name']);
    // Who receives it
    $mail->addAddress('titahope541@gmail.com');
    // Content
    $mail->Subject = 'Message from ' . $_POST['name'];
    $mail->Body    = $_POST['message'];

    $mail->send();
    echo "Message sent!";
} catch (Exception $e) {
    echo "Failed to send: " . $mail->ErrorInfo;
}
?>