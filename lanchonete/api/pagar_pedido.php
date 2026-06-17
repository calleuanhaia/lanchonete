<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $tipo_pagamento = $_POST['tipo_pagamento'];
        $preco = $_POST['preco'];


        try{
            $cadastrar="INSERT INTO pagamento (tipo_pagamento,preco) VALUES ('$tipo_pagamento','$preco')";
            $env=$pdo->prepare($cadastrar);
            $env->execute();

            header("Location: ../index.php?page=lista_clientes_f.php");
            exit();
            
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>