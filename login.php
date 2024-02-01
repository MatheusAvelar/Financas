<!DOCTYPE html>
<html lang="en">
<?php
include "php/dados.php";
session_start();
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tela de Login</title>
    <link rel="icon" type="imagem/png" href="img/contabilidade.png" />
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bem vindo de volta!</h1>
                                    </div>
                                    <form class="user" action="login.php" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="inputEmail" id="inputEmail" aria-describedby="emailHelp" placeholder="Insira o endereço de e-mail...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="inputPassword" id="inputPassword" placeholder="Senha">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Lembre-me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Entrar
                                        </button>
                                        <hr>
                                    </form>
                                    <?php
                                    /*if(!$_SESSION['logado']){
                                        include_once "form_user.php";
                                    } else {
                                        include_once "arquivo_restrito.php";
                                    }*/
                                    if (isset($_POST['inputEmail']) && isset($_POST['inputPassword'])) {
                                        autenticaUsuario();
                                    } /*else {
                                        include "php/arquivo_restrito.php";
                                    }*/
                                    ?>
                                    <!--<hr>-->
                                    <div class="text-center">
                                        <a class="small" href="esqueceuSenha.php">Esqueceu a senha ?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="registro.php">Crie a sua conta aqui !</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>