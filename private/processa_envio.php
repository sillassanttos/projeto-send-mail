<?php

  require "PHPMailer/Exception.php";
  require "PHPMailer/OAuth.php";
  require "PHPMailer/PHPMailer.php";
  require "PHPMailer/POP3.php";
  require "PHPMailer/SMTP.php";

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo' => null, 'descricao' => '');

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
    header('Location: index.php');
  }

  $mail = new PHPMailer(true);

  try {
      $mail->SMTPDebug = false; //SMTP::DEBUG_SERVER;
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

      $mensagem->status['codigo'] = 1;
      $mensagem->status['descricao'] = 'E-mail enviado com sucesso!';

  } catch (Exception $e) {
    $mensagem->status['codigo'] = 2;
    $mensagem->status['descricao'] = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>App Mail Send</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<div class="container">

<div class="py-3 text-center">
  <img class="d-block mx-auto mb-2" src="assets/img/logo.png" alt="" width="72" height="72">
  <h2>Send Mail</h2>
  <p class="lead">Seu app de envio de e-mails particular!</p>
</div>

<div class="row">
  <div class="col-md-12">
    <?php if($mensagem->status['codigo'] == 1) { ?>
      <div class="container">
        <h1 class="display-4 text-success">Sucesso</h1>
        <p><?php echo $mensagem->status['descricao'] ?></p>
        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
      </div>
    <?php } ?>

    <?php if($mensagem->status['codigo'] == 2) { ?>
      <div class="container">
        <h1 class="display-4 text-danger">Ops!</h1>
        <p><?php echo $mensagem->status['descricao'] ?></p>
        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
      </div>
    <?php } ?>
  </div>

</div>

</div>

</body>
</html>