<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        //guarda as informações do formulario em variáveis
        $id = $_POST['id'];
        $lanches = $_POST['lanches'];
        $bebidas = $_POST['bebidas'];
        $preco = $_POST['preco'];
        $preco_b = $_POST['preco_b'];

        try{
            //preparar comando de inserção no banco
            $salvar="UPDATE cardapio SET lanches = '$lanches', bebidas = '$bebidas', preco = $preco , preco_bebida = $preco_b WHERE id=$id";
            $env=$pdo->prepare($salvar);
            $env->execute();

            //redireciona para algum lugar
            header("Location: ../index.php?page=lista_cardapio.php");
            exit();
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>