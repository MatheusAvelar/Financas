<?php

function autenticaUsuario() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    ?>
    <script type="text/javascript">
    function redirecionaPainel() {
        setTimeout("window.location='index.php'", 1500);
    }
    function redirecionaLogin() {
        setTimeout("window.location='login.php'", 1500);
    }
    </script>
    <?php
    $email   = $_POST['inputEmail'];
    $senha   = md5($_POST['inputPassword']);
    $query   = mysqli_query($conexao,"SELECT * FROM usuario WHERE email = '$email' and senha = '$senha'");
    $row     = mysqli_num_rows($query);
    
    if($row>0){
        $_SESSION['inputEmail'] = $email;
        $_SESSION['inputPassword'] = $senha;
        ?>
        <div class="alert alert-success" role="alert">
        <center>Autenticado com sucesso!</center>
        </div>
        <hr>
        <?php
        echo "<script>redirecionaPainel()</script>";      
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Usuario ou Senha invalidos !</center>
        </div>
        <hr> 
        <?php
        echo "<script>redirecionaLogin()</script>"; 
    }
}

function cadastraLogin(){
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $nome       = $_POST['firstName'];
    $sobrenome  = $_POST['lastName'];
    $email      = $_POST['inputEmail'];
    $repetirSenha = MD5($_POST['repeatPassword']);
    $senha      = MD5($_POST['inputPassword']);

    if( $repetirSenha === $senha ) {
        $query = "INSERT INTO usuario(nome, sobrenome, email, senha) VALUES('$nome','$sobrenome','$email','$senha')";
        if(mysqli_query($conexao,$query)){
            ?>
            <div class="alert alert-success" role="alert">
            <center>Usuario criado !</center>
            </div> 
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
            <center>Usuário não foi criado !</center>
            </div> 
            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Senhas incorretas !</center>
        </div> 
        <?php
    }
}

function geraToken() {
    $qtDigitos = 4;
    $codigo = null;
	for($i = 0; $i < $qtDigitos; $i++) {
		$codigo .= rand(0, 9);
	}
    return $codigo;
}

function esqueceuSenha($email){
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $codigo = geraToken();
    $senha = MD5($codigo);

    $query = mysqli_query($conexao,"SELECT * FROM usuario WHERE email = '$email'");
    $row   = mysqli_num_rows($query);
    if($row > 0){
        mysqli_query($conexao,"UPDATE usuario SET senha = '$senha' where email = '$email'");
        $enviaEmail = mail($email, "Esqueci a Senha", "Senha:".$codigo, "");
        if($enviaEmail){
            ?>
            <div class="alert alert-success" role="alert">
            <center>Foi enviada uma nova senha para o email cadastrado !</center>
            </div> 
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
            <center>Dados incorretos !</center>
            </div> 
            <?php
        }
        
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Dados incorretos !</center>
        </div> 
        <?php
    }
}

function insereDadosBanco() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $descricao = $_POST['descricao'];
    $qtd = $_POST["quantasVezes"];
    $valorTotalCompra = $_POST["valorTotalCompra"];
    $valorParcela = $_POST["valorParcela"];
    
    $qtd >= 1 ? $parc = "Sim" : $parc = "Nao"; 

    $sql ="INSERT INTO credicard(descricao,parcelado,vezes,totalParcelas,totalCompra) VALUES ('$descricao','$parc',$qtd,'$valorTotalCompra','$valorParcela')";

    if(mysqli_query($conexao, $sql)) {
        echo "Inserido!";
    } else {
        echo "Error: ".mysqli_error($conexao);
    }
}

function logout() {?>
    <script type="text/javascript">
    function usuarioDesconectado() {
        setTimeout("window.location='login.php'", 1500);
    }
    </script><?php
    session_destroy();
    echo "<center><h3><b>Você foi Desconectado !</b></h3></center><br><br>";
    echo "<script>usuarioDesconectado()</script>";
}

function recuperaDados() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $query = mysqli_query($conexao,"SELECT * FROM credicard");
  
    while($aux = mysqli_fetch_assoc($query)) { 
        
        if($aux["parcelado"] != '') {
            echo "-----------------------------------------<br />
                Id: ".$aux["id"]."<br />
                Descrição: ".$aux["descricao"]."<br />
                Parcelado: ".$aux["parcelado"]."<br />
                Quantas Vezes ?: ".$aux["vezes"]."<br />
                Total de Parcelas: ".$aux["totalParcelas"]."<br />
                Total da Compra: ".$aux["totalCompra"]."<br />";
        } else {
            echo "-----------------------------------------<br />
                Id: ".$aux["id"]."<br />
                Descrição: ".$aux["descricao"]."<br />
                Total da Compra: ".$aux["totalCompra"]."<br />";
        }
    }
}