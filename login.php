<!DOCTYPE html>
<html lang="en">
<head>


<?php
    include 'Configuracoes/headgerais.php';
	renderHead("Login");
?>


</head>
<body class="account-page">

<div class="main-wrapper">
<div class="account-content">
<div class="login-wrapper">
<div class="login-content">
<div class="login-userset">
<div class="login-logo">
<img src="assets/img/logo.png" alt="img">
</div>

<div class="login-userheading">
<h3>Login</h3>
<!--<h4>Please login to your account</h4>-->
</div>
<div class="form-login">
<label>E-mail</label>
<div class="form-addons">
<input type="text" placeholder="Digite seu e-mail" id="emaillogin" name="emaillogin">
<img src="assets/img/icons/mail.svg" alt="img">
</div>
</div>
<div class="form-login">
<label>Senha</label>
<div class="pass-group">
<input type="password" class="pass-input" placeholder="Digite sua senha" id="senhalogin" name="senhalogin">
<span class="fas toggle-password fa-eye-slash"></span>
</div>
</div>
<div class="form-login">
<div class="alreadyuser">
<h4><a href="recuperarsenha.php" class="hover-a">Esqueceu a senha?</a></h4>
</div>
</div>
<div class="form-login">
<a class="btn btn-login" href="#" id="botaologin" name="botaologin">Entrar</a>
</div>
<div class="signinform text-center">
<h4>Algum problema? <a href="#" id="cadastreSe" class="hover-a">Contato</a></h4>
</div>

<div class="form-setlogin">
<h4>Mais opções</h4>
</div>

<div class="form-sociallink">
<ul>
<li>
  <a href="cadastro.php">
    <i class="ion-person-add" style="margin-right: 8px; font-size: 18px;"></i>
    Cadastrar-se
  </a>
</li>
<li>
  <a href="alterarsenha.php">
    <i class="ion-arrow-swap" style="margin-right: 8px; font-size: 18px;"></i>
    Alterar senha
  </a>
</li>
</ul>
</div>

</div>
<div class="copyright" style="padding-top: 80px; text-align: center; ">
					
					<img src="assets/img/logodhl.png" width="100" height="20">
					<p><strong>DHL Supply Chain</strong> © 2024 All Rights Reserved</p>
				</div>
</div>
<div class="login-img">
<img src="assets/img/login.jpg" alt="img">
</div>
</div>
</div>
</div>

</body>
</html>