<?php
    include "config/config.php";
    
    $sql="SELECT * FROM pagamento ORDER BY id DESC";
    $consulta=$pdo->query($sql);
    $pagamentos=$consulta->fetchAll(PDO::FETCH_ASSOC);

    if(!isset($_SESSION['usuario_nome'])) {
        header("Location: register.php");
        exit();
    }

    $nome = $_SESSION['usuario_nome'];
    $email = $_SESSION['usuario_email'];
    $tipo = $_SESSION['usuario_tipo'];
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 w-full">
    
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Pagamentos</h2>
        </div>
    </div>
    
    <div class="overflow-x-auto overflow-y-auto max-h-[400px]">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 z-10 shadow-sm">
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-3 font-medium bg-gray-50">ID</th>
                    <th class="px-6 py-3 font-medium bg-gray-50">Tipo de Pagamento</th>
                    <th class="px-6 py-3 font-medium bg-gray-50">Preço</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <?php foreach ($pagamentos as $pagamento): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            #<?php echo $pagamento['id']; ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            <?php echo $pagamento['tipo_pagamento']; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <?php echo $pagamento['preco']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>