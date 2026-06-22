<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        //guarda as informações do formulario em variáveis
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $lanche = $_POST['lanche'];
        $bebida = $_POST['bebida'];
        $preco = $_POST['preco'];
        $preco_b = $_POST['preco_b'];

        try{
            //preparar comando de inserção no banco
            $salvar="UPDATE clientes_f SET nome = '$nome', telefone = '$telefone', endereco = '$endereco', lanche = '$lanche', bebida = '$bebida', preco = '$preco', preco_bebida = '$preco_b' WHERE id=$id";
            $env=$pdo->prepare($salvar);
            $env->execute();

            //redireciona para algum lugar
            header("Location: ../index.php?page=lista_clientes_f.php");
            exit();
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>