<?php
    include "config/config.php";

    // Garante que a sessão está ativa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Inicializa a variável do total
    $total = 0;
    $total_formatado = '0,00';

    if (isset($_GET['id'])){
        $id = $_GET['id'];

        // Proteção contra SQL Injection usando Prepared Statements
        $sql = "SELECT * FROM clientes_f WHERE id = :id";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([':id' => $id]);

        $cliente = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Pega os valores do banco. O str_replace garante que se vier "10,50" vire "10.50" para o PHP conseguir somar
            $preco = isset($cliente['preco']) ? (float) str_replace(',', '.', $cliente['preco']) : 0;
            $preco_bebida = isset($cliente['preco_bebida']) ? (float) str_replace(',', '.', $cliente['preco_bebida']) : 0;
            
            // Faz a soma do total
            $total = $preco + $preco_bebida;
            
            // Formata o valor de volta para o padrão brasileiro (ex: 15,50)
            $total_formatado = number_format($total, 2, ',', ''); 
        }
    }
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 max-w-md">
    
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Pagamento do Pedido #<?php echo isset($cliente['id']) ? $cliente['id'] : ''; ?></h2>
        <p class="text-sm text-gray-500 mt-1">Selecione a forma de pagamento e insira o valor.</p>
    </div>

    <div class="p-6">
        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'valor_menor'): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> O valor inserido é insuficiente para o pagamento.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro2']) && $_GET['erro2'] == 'valor_maior'): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> O valor inserido é maior do que o necessário.
            </div>
        <?php endif; ?>

        <form method="POST" action="api/pagar_pedido.php" class="space-y-5">
            
            <input type="hidden" name="pedido_id" value="<?php echo isset($cliente['id']) ? $cliente['id'] : ''; ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Pagamento</label>
                <div class="relative">
                    <select name="tipo_pagamento" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900 appearance-none">
                        <option value="PIX">PIX</option>
                        <option value="Débito">Débito</option>
                        <option value="Crédito">Crédito</option>
                        <option value="Dinheiro">Dinheiro</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Pago (Total: R$ <?php echo $total_formatado; ?>)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">R$</span>
                    <input name="valor_pago" value="<?php echo $total > 0 ? $total_formatado : ''; ?>" placeholder="0,00" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900">
                </div>
            </div>
            
            <div class="pt-4 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white px-8 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-check-circle mr-2"></i> Confirmar Pagamento
                </button>
            </div>
        </form>
    </div>
</div>