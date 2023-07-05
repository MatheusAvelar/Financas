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

function insereDadosRefeicao() {    
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = $_POST["valorTotalCompra"];

    $sql ="INSERT INTO refeicao(descricao,totalCompra) VALUES ('$descricao','$valorTotalCompra')";

    if(!mysqli_query($conexao, $sql)) {
        echo "Error: ".mysqli_error($conexao);
    }
}

function insereDadosAlimentacao() {    
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = $_POST["valorTotalCompra"];
    $data = date('d/m/Y');

    $sql ="INSERT INTO alimentacao(descricao,totalCompra,dataCompra) VALUES ('$descricao','$valorTotalCompra','".$data."')";

    if(!mysqli_query($conexao, $sql)) {
        echo "Error: ".mysqli_error($conexao);
    }
}

function insereDadosXP() {    
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = $_POST["valorTotalCompra"];
    $data = date('d/m/Y');

    $sql ="INSERT INTO xpinvestimentos(descricao,totalCompra,dataCompra) VALUES ('$descricao','$valorTotalCompra','".$data."')";

    if(!mysqli_query($conexao, $sql)) {
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

function recuperaDadosRefeicao() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $sql = "SELECT descricao,totalCompra FROM refeicao ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT ROUND(SUM(CAST(REPLACE(totalCompra, '.', '') AS DECIMAL(10,2))), 2) AS total FROM refeicao";
    $result2 = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($result) > 0) {

        echo "<div class='card shadow mb-4'>
        <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Extrato - Cartão Refeição</h6>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    ";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>".$row["descricao"]."</td><td>".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th id='qtdtotal'>R$ ".$row2['total']."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function recuperaDadosAlimentacao() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $sql = "SELECT dataCompra,descricao,totalCompra FROM alimentacao ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT ROUND(SUM(CAST(REPLACE(totalCompra, '.', '') AS DECIMAL(10,2))), 2) AS total FROM alimentacao";
    $result2 = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($result) > 0) {

        echo "<div class='card shadow mb-4'>
        <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Extrato - Cartão Alimentação</h6>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    ";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>".$row["dataCompra"]."</td><td>".$row["descricao"]."</td><td>".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th></th><th id='qtdtotal'>R$ ".$row2['total']."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function recuperaDadosXP() {
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $sql = "SELECT dataCompra,descricao,totalCompra FROM xpinvestimentos ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT ROUND(SUM(CAST(REPLACE(totalCompra, '.', '') AS DECIMAL(10,2))), 2) AS total FROM xpinvestimentos";
    $result2 = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($result) > 0) {

        echo "<div class='card shadow mb-4'>
        <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-primary'>Extrato - XP Cartão</h6>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    ";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>".$row["dataCompra"]."</td><td>".$row["descricao"]."</td><td>".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th></th><th id='qtdtotal'>R$ ".$row2['total']."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}