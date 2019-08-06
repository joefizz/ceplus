<html>
<head>
<script src="jquery-3.3.1.min.js"></script>
<script src="checkall.js"></script>
</head>
<P>
<body>
<H3>CE Plus Test Files for end user device checks
</H3>
<p>

<!--
 This is a script built to assist with end user device checks for Cyber Essentials Plus.
 The use is hopefully fairly obvious - Enter an email address, select files (or not), and send.

 This script requires PHP > 5.5, and also the PHPMailer library to be installed in a reference path.
 PHPLibrary is available from https://github.com/PHPMailer/PHPMailer.

 Please update the Server Settings section with your required email settings.
-->


<form method ="post" action="emailer.php" enctype="multipart/form-data">
Target User Email Address:
<?php
        if (isset($_POST['Email'])){
                $address = $_POST['Email'];
                echo "<input name='Email' type='text' id='Email' size='66' value='$address' />";
        } else {
                echo "<input name='Email' type='text' id='Email' size='66' />";
        }
?>
<p>
Please choose attachments to send.  if no attachments are chosen a test email will be sent :</br>
<?php
        $files = scandir("files");
        foreach ($files as $file) {
                if ($file != "." AND $file != ".."){
                        echo "<input type='checkbox' class='child' name=\"attachments[]\" value='$file' />$file<br>";
                }
        }
?>
<br>
<input type="checkbox" class="checkall" label="check all"  />check all<br>
<input type="submit" value="Send selected files" onclick=return confirm('SEND selected files?'); />
</form>


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $err=0;

        $Email = $_POST['Email'];
        #$Filename = $_REQUEST['Filename'];

        if (empty($Email)) {
                if ($err==0) echo("<html> <h1>Error</h1><p>"); $err=1;
                echo("<p>No email address supplied!<p>"); }

        if (preg_match('/[^a-z0-9.@-_]/i', $Email)){
                print "Invalid characters found in email address<p>";
        $err=1; }

        if ($err==1) echo("Please enter the target address and try again. </p> </body> </html>");

        if(isset($_POST['attachments'])){
                $myboxes = $_POST['attachments'];
        }

        //Server settings
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = '127.0.0.1';                              // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;                               // Enable SMTP authentication
        #$mail->Username = 'user@example.com';                 // SMTP username
        #$mail->Password = 'secret';                           // SMTP password
        #$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connect to

        $mail->setFrom('security@email.com', 'From Name');
        $mail->addAddress($Email);     // Add a recipient
        $mail->addReplyTo('security@email.com', 'Reply Name');
        #$mail->addCC('cc@example.com');
        #$mail->addBCC('bcc@example.com');

        if(empty($myboxes)){
                echo("Sending test email to " . $Email . "<br>");
                try {
                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Cyber Essentials Plus test email';
                        $mail->Body    = 'This email has been sent with no attachments to ensure email delivery is functioning';
                        $mail->AltBody = 'This email has been sent with no attachments to ensure email delivery is functioning';

                        $mail->send();
                        echo 'Message has been sent';
                } catch (Exception $e) {
                        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
        } else {
                $i = count($myboxes);
                echo("You selected $i box(es): <br>");
                for ($j = 0; $j < $i; $j++){
                        try {
                                $attach = $myboxes[$j];
                                echo 'Sending email ' . ($j+1) . '/' . $i . ' with attachment ' . $attach . ' to ' . $Email . '<br>';
                                //Attachments
                                $mail->clearAttachments();
                                $mail->addAttachment('files/' . $attach);         // Add attachments
                                #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                                //Content
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'Cyber Essentials Plus with attachment ' . ($j+1) . '/' . $i . ' - ' . $attach;
                                $mail->Body    = 'Sent with attachment '.$attach;
                                $mail->AltBody = 'Sent with attachment '.$attach;

                                $mail->send();
                                echo 'Message has been sent <br>';
                        }
                        catch (Exception $e) {
                                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                        }
                echo 'Message sending complete';
                }
        }
}
?>



</body>
