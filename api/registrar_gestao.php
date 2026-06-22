<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $nome = $_POST['nome_g'];
        $email = $_POST['email_g'];
        $senha = $_POST['senha_g'];
        $telefone = $_POST['telefone_g'];


        try{
            $cadastrar="INSERT INTO gestao (nome,email,senha,telefone) VALUES ('$nome','$email','$senha','$telefone')";
            $env=$pdo->prepare($cadastrar);
            $env->execute();

            header("Location: ../pages/register.php");
            exit();
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>