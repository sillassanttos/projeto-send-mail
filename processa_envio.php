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
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = '';
      $mail->Password   = '';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

      $mail->setFrom('softsolweb.ssw@gmail.com', 'E-mail com PHP Mailer');
      $mail->addAddress($mensagem->__get('para'));

      $mail->isHTML(true);
      $mail->Subject = $mensagem->__get('assunto');
      $mail->Body    = $mensagem->__get('mensagem');

      $mail->send();
      echo 'E-mail enviado com sucesso!';
  } catch (Exception $e) {
      echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
  }
