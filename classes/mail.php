<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * This class contains method to send mail to user
 */
class MailClass
{
    /**
     * This method sends mail to the user
     * 
     * @param string $subject The subject of the Email
     * @param string $message The message to send in html form
     * @param string $email The email address of the user
     * @return void
     */
    public function sendMail(string $subject, string $message, string $email): void
    {
        //Load Composer's autoloader
        require 'vendor/autoload.php';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted

            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->Port = 587; // TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->isHTML(true); // Set email format to HTML

            $mail->Username = 'ucstest34@gmail.com'; // SMTP username
            $mail->Password = 'qzyd yrxj tnri hsmq'; // SMTP password

            $mail->setFrom('libgenInc@gmail.com', 'LibGen'); //Your application NAME and EMAIL
            $mail->Subject = $subject; //Message subject
            $mail->MsgHTML($message); // Message body
            $mail->addAddress($email, 'User'); // Target email

            $mail->send();
            setcookie('message', 'Link has been sent to your Email', time() + 2);
        } catch (Exception $e) {
            setcookie('error', true, time() + 2);
            setcookie('message', 'Message could not be sent', time() + 2);
        }
    }
}
?>