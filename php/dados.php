<?php

function autenticaUsuario() {
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
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

function logout() {
    session_destroy();
    echo "<center><h3><b>Você foi Desconectado !</b></h3></center><br><br>";
    echo "<script>usuarioDesconectado()</script>";
    ?>
    <script type="text/javascript">
    function usuarioDesconectado() {
        setTimeout("window.location='login.php'", 1500);
    }
    </script><?php
}

function cadastraLogin(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $nome       = $_POST['firstName'];
    $sobrenome  = $_POST['lastName'];
    $email      = $_POST['inputEmail'];
    $repetirSenha = md5($_POST['repeatPassword']);
    $senha      = md5($_POST['inputPassword']);

    if(!verificaEmailExistente($email)) {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Já existe um usuário cadastrado neste email !</center>
        </div> 
        <?php
    }

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
            <center>Houve uma falha na conexão com o banco de dados !</center>
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

function cadastraSalario(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $usuario = $_SESSION['inputEmail'];
    $salarioCentavos = str_replace(array('.', ','), array('', '.'), $_POST['inputSalario']);
    //$salario = number_format(strval($salarioCentavos), 2, '.', '') * 100;

    $query = "INSERT INTO salario(idUsuario, salario) VALUES('$usuario','$salarioCentavos')";
    if(mysqli_query($conexao,$query)){
        echo "Salário cadastrado !";
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Houve uma falha na conexão com o banco de dados !</center>
        </div> 
        <?php
    }

}

function verificaEmailExistente($email){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    $query = mysqli_query($conexao,"SELECT * FROM usuario WHERE email = '$email'");
    $row   = mysqli_num_rows($query);
    if($row > 0){
        return false;
    } else {
        return true;
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
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
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
            <center>Foi enviado um e-mail para recuperação de senha !</center>
            </div> 
            <?php
        }
        
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Foi enviado um e-mail para recuperação de senha !</center>
        </div> 
        <?php
    }
}

function insereDadosRefeicao() {    
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = str_replace('.', '', $_POST["valorTotalCompra"]);
    $valorTotalCompra = str_replace(',', '.', $valorTotalCompra);
    $email = $_SESSION['inputEmail'];

    $sql ="INSERT INTO refeicao(descricao,totalCompra,dataCompra,idUsuario) VALUES ('$descricao','$valorTotalCompra',NOW(),'$email')";

    if(!mysqli_query($conexao, $sql)) {
        echo "Error: ".mysqli_error($conexao);
    }
}

function insereDadosAlimentacao() {    
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = str_replace('.', '', $_POST["valorTotalCompra"]);
    $valorTotalCompra = str_replace(',', '.', $valorTotalCompra);

    $email = $_SESSION['inputEmail'];
    $sql ="INSERT INTO alimentacao(descricao,totalCompra,dataCompra,idUsuario) VALUES ('$descricao','$valorTotalCompra',NOW(),'$email')";

    if(!mysqli_query($conexao, $sql)) {
        echo "Error: ".mysqli_error($conexao);
    }
    totalAlimentacao();
}

function insereDadosCartao1() {    
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    $descricao = $_POST['descricao'];
    $valorTotalCompra = str_replace('.', '', $_POST["valorTotalCompra"]);
    $valorTotalCompra = str_replace(',', '.', $valorTotalCompra);
    $email = $_SESSION['inputEmail'];

    $sql ="INSERT INTO xpinvestimentos(descricao,totalCompra,dataCompra,idUsuario) VALUES ('$descricao','$valorTotalCompra',NOW(),'$email')";
    
    if(!mysqli_query($conexao, $sql)) {
        echo "Error: ".mysqli_error($conexao);
    }
}


function recuperaDadosRefeicao() {
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];
    $sql = "SELECT dataCompra,descricao,totalCompra FROM refeicao WHERE idUsuario = '$email' ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT SUM(totalCompra) AS total FROM refeicao WHERE idUsuario = '$email'";
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
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    ";
        
        while ($row = mysqli_fetch_assoc($result)) {
            $dataFormatada = date('d/m/Y', strtotime($row["dataCompra"]));
            echo "<tr><td>".$dataFormatada."</td><td>".$row["descricao"]."</td><td>R$ ".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th></th><th id='qtdtotal'>R$ ".formataNumero($row2['total'])."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function recuperaDadosAlimentacao() {
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];

    $sql = "SELECT dataCompra,descricao,totalCompra FROM alimentacao WHERE idUsuario = '$email' ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT SUM(totalCompra) AS total FROM alimentacao WHERE idUsuario = '$email'";
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
            $dataFormatada = date('d/m/Y', strtotime($row["dataCompra"]));
            echo "<tr><td>".$dataFormatada."</td><td>".$row["descricao"]."</td><td>R$ ".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th></th><th id='qtdtotal'>R$ ".formataNumero($row2['total'])."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function recuperaDadosCartao1() {
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];

    $sql = "SELECT dataCompra,descricao,totalCompra FROM xpinvestimentos WHERE idUsuario = '$email' ORDER BY idCompra";
    $result = mysqli_query($conexao, $sql);

    $sql2 = "SELECT SUM(totalCompra) AS total FROM xpinvestimentos WHERE idUsuario = '$email'";
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
            $dataFormatada = date('d/m/Y', strtotime($row["dataCompra"]));
            echo "<tr><td>".$dataFormatada."</td><td>".$row["descricao"]."</td><td>R$ ".$row["totalCompra"]."</td></tr>";
        }
        
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<thead><tr><th>Total do Extrato</th><th></th><th id='qtdtotal'>R$ ".formataNumero($row2['total'])."</th></tr></thead><tbody></table><tbody>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function formataNumero($valor) {
    // Verifica se o valor tem casas decimais
    $decimais = fmod($valor, 1) !== 0;

    // Formata o número
    $valorFormatado = number_format($valor, $decimais ? 2 : 0, ',', '.');

    return $valorFormatado;
}

function receitaTotal(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $email = $_SESSION['inputEmail'];

    $sql = "SELECT SUM(totalCompra) AS total FROM xpinvestimentos WHERE idUsuario = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $valorTotal = $row['total'];
            $diferencaFormatada = number_format($valorTotal, 2, ',', '.');
            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>R$ ".$diferencaFormatada."</div>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function despesaTotal(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];

    $sql = "SELECT SUM(totalCompra) AS total FROM xpinvestimentos WHERE idUsuario = '$email'";
    $result = mysqli_query($conexao, $sql);
    $atual = retornaSalario();

    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $valorTotal = $row['total'];
            $diferenca = round($atual, 2) - round($valorTotal, 2);
            $diferencaFormatada = number_format($diferenca, 2, ',', '.');
            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>R$ ".$diferencaFormatada."</div>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function totalAlimentacao(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];
    $sql = "SELECT SUM(totalCompra) AS total FROM alimentacao WHERE idUsuario = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $alimentacao = retornaAlimentacao();
            $valorTotal = $row['total'];
            $diferenca = round($alimentacao, 2) - round($valorTotal, 2);
            $diferencaFormatada = number_format($diferenca, 2, ',', '.');
            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>R$ ".$diferencaFormatada."</div>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function totalRefeicao() {
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    $email = $_SESSION['inputEmail'];
    $sql = "SELECT SUM(totalCompra) AS total FROM refeicao WHERE idUsuario = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $refeicao = retornaRefeicao();
            $valorTotal = $row['total'];
            $diferenca = round($refeicao, 2) - round($valorTotal, 2);
            $diferencaFormatada = number_format($diferenca, 2, ',', '.');
            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>R$ ".$diferencaFormatada."</div>";
        }

    } else {
        echo "Nenhum resultado encontrado";
    }

    mysqli_close($conexao);
}

function retornaSalario(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $email = $_SESSION['inputEmail'];

    $sql = "SELECT salario AS salarioAtual FROM salario WHERE idUsuario = '$email' ORDER BY idSalario LIMIT 1";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $salarioAtual = $row['salarioAtual'];
        return $salarioAtual;
    }


    mysqli_close($conexao);
}

function retornaAlimentacao(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $email = $_SESSION['inputEmail'];

    $sql = "SELECT alimentacao AS alimentacaoAtual FROM salario WHERE idUsuario = '$email' ORDER BY idSalario LIMIT 1";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $alimentacaoAtual = $row['alimentacaoAtual'];
        return $alimentacaoAtual;
    }


    mysqli_close($conexao);
}

function retornaRefeicao(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $email = $_SESSION['inputEmail'];

    $sql = "SELECT refeicao AS refeicaoAtual FROM salario WHERE idUsuario = '$email' ORDER BY idSalario LIMIT 1";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $refeicaoAtual = $row['refeicaoAtual'];
        return $refeicaoAtual;
    }


    mysqli_close($conexao);
}

function salarioAtual(){
    //$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    $conexao = mysqli_connect("localhost", "root", "", "controle");
    
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $email = $_SESSION['inputEmail'];

    $sql = "SELECT salario AS salarioAtual FROM salario WHERE idUsuario = '$email' AND idSalario = (SELECT MAX(idSalario) FROM salario WHERE idUsuario = '$email');";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $salarioAtual = number_format($row['salarioAtual'], 2, ',', '.');
            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>R$ ".$salarioAtual."</div>";
        }

    } else {
        echo "Nenhum salário cadastrado! <a href='#' id='cadastrarLink'>Clique aqui para cadastrar</a>";
        ?>
        <script>
        document.getElementById('cadastrarLink').addEventListener('click', function() {
            window.location.href = 'cadastraSalario.php';
        });
        </script>
        <?php
    }

    mysqli_close($conexao);
}

