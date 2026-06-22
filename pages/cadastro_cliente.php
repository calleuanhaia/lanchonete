<?php
    include "config/config.php";

    // Garante que a sessão está ativa para pegar o e-mail do sidebar
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Pega o e-mail direto da sessão (do usuário logado)
    if (isset($_SESSION['usuario_email'])) {
        $email = $_SESSION['usuario_email'];

        // Proteção contra SQL Injection usando Prepared Statements (:email)
        $sql = "SELECT * FROM clientes WHERE email = :email";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([':email' => $email]);

        $cliente = $consulta->fetch(PDO::FETCH_ASSOC);
    }

    $sql="SELECT * FROM cardapio ORDER BY id DESC";
    $consulta=$pdo->query($sql);
    $cardapios=$consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 max-w-2xl">
    
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Registrar Novo Pedido</h2>
        <p class="text-sm text-gray-500 mt-1">Confirme seus dados e insira o que deseja pedir.</p>
    </div>

    <div class="p-6">
        <form method="POST" action="api/cadastrar_cliente.php" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo isset($cliente['id']) ? $cliente['id'] : ''; ?>">
            
            <input type="hidden" name="id_cardapio" value="<?php echo isset($cardapios[0]['id']) ? $cardapios[0]['id'] : ''; ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input name="nome" value="<?php echo isset($cliente['nome']) ? $cliente['nome'] : '';?>" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                <input name="telefone" value="<?php echo isset($cliente['telefone']) ? $cliente['telefone'] : '';?>" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                <input name="endereco" value="<?php echo isset($cliente['endereco']) ? $cliente['endereco'] : '';?>" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lanche</label>
                <select name="pedido" id="pedido_lanche" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900 placeholder-gray-400">
                    <option value="" data-preco="">Selecione um lanche...</option>
                    <option value="---" data-preco="0.00">Nenhum Lanche</option>
                    <?php foreach ($cardapios as $cardapio): ?>
                        <option value="<?php echo $cardapio['lanches']; ?>" data-preco="<?php echo $cardapio['preco']; ?>">
                            <?php echo $cardapio['lanches']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço do Lanche</label>
                <input type="text" name="preco" id="preco_lanche" readonly placeholder="R$ 0,00" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed focus:outline-none text-gray-900">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bebida</label>
                <select name="pedido_b" id="pedido_bebida" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors text-gray-900 placeholder-gray-400">
                    <option value="" data-preco="">Selecione uma bebida...</option>
                    <option value="---" data-preco="0.00">Nenhuma Bebida</option>
                    <?php foreach ($cardapios as $cardapio): ?>
                        <option value="<?php echo $cardapio['bebidas']; ?>" data-preco="<?php echo $cardapio['preco_bebida']; ?>">
                            <?php echo $cardapio['bebidas']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Preço da Bebida</label>
                <input type="text" name="preco_b" id="preco_bebida" readonly placeholder="R$ 0,00" class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed focus:outline-none text-gray-900">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Enviar Pedido
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Função para atualizar o preço
        function atualizarPreco(selectId, inputPrecoId) {
            const selectElement = document.getElementById(selectId);
            const inputPrecoElement = document.getElementById(inputPrecoId);
            
            selectElement.addEventListener('change', function() {
                // Pega a opção que foi selecionada
                const selectedOption = this.options[this.selectedIndex];
                
                // Pega o valor do atributo 'data-preco' dessa opção
                const preco = selectedOption.getAttribute('data-preco');
                
                // Preenche o campo de input com o preço
                inputPrecoElement.value = preco ? preco : ''; 
            });
        }

        // Aplica a lógica para os Lanches e Bebidas
        atualizarPreco('pedido_lanche', 'preco_lanche');
        atualizarPreco('pedido_bebida', 'preco_bebida');
        
    });
</script>