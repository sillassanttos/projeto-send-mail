<?php

  require "private/PHPMailer/Exception.php";
  require "private/PHPMailer/OAuth.php";
  require "private/PHPMailer/PHPMailer.php";
  require "private/PHPMailer/POP3.php";
  require "private/PHPMailer/SMTP.php";

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public function __get($attr) {
      return $this->$attr;
    }

    public function __set($attr, $value) {
      $this->$attr = $value;
    }

    public function mensagemValida() {
      if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
        return false;
      }

      return true;
    }
  }

  $mensagem = new Mensagem();
  $mensagem->__set('para', $_POST['para']);
  $mensagem->__set('assunto', $_POST['assunto']);
  $mensagem->__set('mensagem', $_POST['mensagem']);

  if (!$mensagem->mensagemValida()) {
    echo 'Mensagem não é válida';
    die();
  }

  $mail = new PHPMailer(true);

  try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = '';                     //SMTP username
      $mail->Password   = '';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom('softsolweb.ssw@gmail.com', 'Mailer');
      $mail->addAddress('softsolweb.ssw@gmail.com', 'Joe User');     //Add a recipient
      //$mail->addAddress('ellen@example.com');               //Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
     // $mail->addCC('cc@example.com');
     // $mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Assunto do email';
      $mail->Body    = 'Conteúdo do e-mail';
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
  }
