<?php
    //Verifica se a Sessao ainda esta ativa para continuar e poder acessar outras URLs
    if (!isset($_SESSION["inputEmail"])) {
        header("Location:http://inovacodigo.com.br");
        exit(0);
    }
    include "php/dados.php";
    session_start();
    logout();
?>