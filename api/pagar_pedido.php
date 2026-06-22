<?php
    include "../config/config.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $pedido_id = $_POST['pedido_id'];
        $tipo_pagamento = $_POST['tipo_pagamento'];
        
        // Pega o valor digitado e troca vírgula por ponto para fazer a matemática corretamente
        $valor_pago = str_replace(',', '.', $_POST['valor_pago']);

        try{
            // 1. Busca os dois preços daquele pedido no banco de dados (preco e preco_bebida)
            $sql_busca = "SELECT preco, preco_bebida FROM clientes_f WHERE id = :id";
            $consulta = $pdo->prepare($sql_busca);
            $consulta->execute([':id' => $pedido_id]);
            $cliente = $consulta->fetch(PDO::FETCH_ASSOC);

            if($cliente) {
                // Trata os valores vindos do banco para garantir que as vírgulas sejam pontos
                $preco_banco = str_replace(',', '.', $cliente['preco']);
                $preco_bebida_banco = str_replace(',', '.', $cliente['preco_bebida']);

                // Soma os dois valores para obter o Total do pedido
                $preco_total = (float)$preco_banco + (float)$preco_bebida_banco;

                // 2. Compara se o valor pago é menor que o preço TOTAL no banco
                if ((float)$valor_pago < $preco_total) {
                    // Valor abaixo! Retorna para o form passando um aviso na URL
                    header("Location: ../index.php?page=paga_pedido.php&id=$pedido_id&erro=valor_menor");
                    exit();
                } elseif ((float)$valor_pago > $preco_total) {
                    // Valor abaixo! Retorna para o form passando um aviso na URL
                    header("Location: ../index.php?page=paga_pedido.php&id=$pedido_id&erro2=valor_maior");
                    exit();
                }

                // 3. O valor é exato ou maior (passou na validação). Registra o pagamento!
                $cadastrar = "INSERT INTO pagamento (tipo_pagamento, preco) VALUES (:tipo, :preco)";
                $env = $pdo->prepare($cadastrar);
                $env->execute([
                    ':tipo' => $tipo_pagamento,
                    // Se preferir registrar apenas o exato devido e ignorar o troco, mude $valor_pago para $preco_total
                    ':preco' => $valor_pago 
                ]);

                // Redireciona com sucesso para a lista
                header("Location: ../index.php?page=lista_clientes_f.php");
                exit();

            } else {
                echo "Pedido não encontrado.";
            }
            
        } catch(PDOException $e){
            echo "Erro: ".$e->getMessage();
        }
    }
?>