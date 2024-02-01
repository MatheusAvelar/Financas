<?php
// Verifica se foi recebido um valor válido para a descrição
if (isset($_POST['descricao'])) {
    $descricao = $_POST['descricao'];

    // Realiza a conexão com o banco de dados
    $conexao = mysqli_connect("localhost", "root", "", "controle");

    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    // Executa a consulta de exclusão
    $sql = "DELETE FROM refeicao WHERE descricao = '$descricao'";
    $resultado = mysqli_query($conexao, $sql);

    // Verifica se a exclusão foi bem-sucedida
    if ($resultado) {
        echo "Exclusão realizada com sucesso!";
    } else {
        echo "Erro ao excluir a linha: " . mysqli_error($conexao);
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    echo "Descrição inválida";
}
?>