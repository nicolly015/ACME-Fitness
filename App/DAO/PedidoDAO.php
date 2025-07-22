<?php
namespace App\DAO;

require_once __DIR__ . '/../Model/Pedido.php';
require_once __DIR__ . '/../DAO/ItensPedidoDAO.php';

use App\Model\Pedido;
use App\DAO\ItensPedidoDAO;
use PDO;
use PDOException;
use RuntimeException;

class PedidoDAO {
    private PDO $conexao;
    private ItensPedidoDAO $itensDao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
        $this->itensDao = new ItensPedidoDAO($conexao);
    }

    public function criar(Pedido $pedido): void {
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO pedidos
                    (valor_total, valor_frete, desconto, forma_pagamento, data_pedido, endereco_id, cliente_id)
                    VALUES
                    (:valor_total, :valor_frete, :desconto, :forma_pagamento, :data_pedido, :endereco_id, :cliente_id)";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':valor_total' => $pedido->getValorTotal(),
                ':valor_frete' => $pedido->getValorFrete(),
                ':desconto' => $pedido->getDesconto(),
                ':forma_pagamento' => $pedido->getFormaPagamento()->value,
                ':data_pedido' => $pedido->getDataPedido()->format('Y-m-d H:i:s'),
                ':endereco_id' => $pedido->getEnderecoId(),
                ':cliente_id' => $pedido->getClienteId()
            ]);

            $pedidoId = (int)$this->conexao->lastInsertId();
            $pedido->setId($pedidoId);

            foreach ($pedido->getItens() as $item) {
                $item->setPedidoId($pedidoId);
                $this->itensDao->salvar($item);
            }

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new RuntimeException("Erro ao criar pedido: " . $e->getMessage());
        }
    }

    public function buscarProdutosMaisVendidos(int $limit = 10): array {
        $sql = "SELECT
                p.id_produto AS produto_id,
                p.nome AS produto_nome,
                SUM(ip.quantidade) AS total_vendido
            FROM itens_pedido ip
            JOIN variacoes_produto v ON v.id_variacoes_produto = ip.variacao_id
            JOIN produtos p ON p.id_produto = v.produto_id
            GROUP BY p.id_produto
            ORDER BY total_vendido DESC
            LIMIT :limit";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>