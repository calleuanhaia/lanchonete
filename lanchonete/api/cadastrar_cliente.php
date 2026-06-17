<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $pedido = $_POST['pedido'];

        try{
            $cadastrar="INSERT INTO clientes_f (nome,telefone,endereco,pedido) VALUES ('$nome','$telefone','$endereco','$pedido')";
            $env=$pdo->prepare($cadastrar);
            $env->execute();

            header("Location: ../index.php?page=lista_clientes_f.php");
            exit();
            
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>