<?php
namespace App\DAO;

use App\Model\ItensPedido;
use PDO;

class ItensPedidoDAO {
    private PDO $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    public function salvar(ItensPedido $item): void {
        $sql = "INSERT INTO itens_pedido
                (preco_venda, quantidade, pedido_id, variacao_id)
                VALUES
                (:preco_venda, :quantidade, :pedido_id, :variacao_id)";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':preco_venda' => $item->getPrecoVenda(),
            ':quantidade' => $item->getQuantidade(),
            ':pedido_id' => $item->getPedidoId(),
            ':variacao_id' => $item->getVariacaoId()
        ]);

        $item->setId((int)$this->conexao->lastInsertId());
    }

    public function buscarPorId(int $id): ?ItensPedido {
        $sql = "SELECT * FROM itens_pedido WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) return null;

        return $this->mapearParaObjeto($dados);
    }

    public function buscarTodos(): array {
        $sql = "SELECT * FROM itens_pedido";
        $stmt = $this->conexao->query($sql);

        return array_map(
            fn($dados) => $this->mapearParaObjeto($dados),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function buscarPorPedido(int $pedidoId): array {
        $sql = "SELECT * FROM itens_pedido WHERE pedido_id = :pedido_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':pedido_id' => $pedidoId]);

        return array_map(
            fn($dados) => $this->mapearParaObjeto($dados),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function atualizar(ItensPedido $item): void {
        $sql = "UPDATE itens_pedido SET
                preco_venda = :preco_venda,
                quantidade = :quantidade,
                pedido_id = :pedido_id,
                variacao_id = :variacao_id
                WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':preco_venda' => $item->getPrecoVenda(),
            ':quantidade' => $item->getQuantidade(),
            ':pedido_id' => $item->getPedidoId(),
            ':variacao_id' => $item->getVariacaoId(),
            ':id' => $item->getId()
        ]);
    }

    public function excluir(int $id): void {
        $sql = "DELETE FROM itens_pedido WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public function excluirPorPedido(int $pedidoId): void {
        $sql = "DELETE FROM itens_pedido WHERE pedido_id = :pedido_id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':pedido_id' => $pedidoId]);
    }

    private function mapearParaObjeto(array $dados): ItensPedido {
        $item = new ItensPedido(
            (float)$dados['preco_venda'],
            (int)$dados['quantidade'],
            (int)$dados['pedido_id'],
            (int)$dados['variacao_id']
        );
        $item->setId((int)$dados['id']);
        return $item;
    }
}
?>