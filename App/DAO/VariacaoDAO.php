<?php
namespace App\DAO;

use App\Model\Variacoes;
use App\DAO\ProdutoDAO;
use PDO;
use PDOException;
use Exception;

class VariacaoDAO {
    private PDO $conexao;
    private ProdutoDAO $produtoDAO;

    public function __construct(PDO $conexao, ProdutoDAO $produtoDAO) {
        $this->conexao = $conexao;
        $this->produtoDAO = $produtoDAO;
    }

    public function salvar(Variacoes $variacao): void {
        $this->conexao->beginTransaction();
        try {
            $sql = "INSERT INTO variacoes (tamanho, estoque, produto_id)
                    VALUES (:tamanho, :estoque, :produto_id)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":tamanho", $variacao->getTamanho(), PDO::PARAM_STR);
            $stmt->bindValue(":estoque", $variacao->getEstoque(), PDO::PARAM_INT);
            $stmt->bindValue(":produto_id", $variacao->getProdutoId(), PDO::PARAM_INT);
            $stmt->execute();

            $variacao->setId((int) $this->conexao->lastInsertId());
            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao salvar variação: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Variacoes {
        try {
            $sql = "SELECT * FROM variacoes WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados) return null;

            $produto = $this->produtoDAO->buscarPorId((int) $dados["produto_id"]);
            if (!$produto) {
                throw new Exception("Produto não encontrado para ID: " . $dados["produto_id"]);
            }

            $variacao = new Variacoes(
                $dados["tamanho"],
                (int) $dados["estoque"],
                $dados[$produto]
            );
            $variacao->setId((int) $dados["id"]);

            return $variacao;

        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar variação: " . $e->getMessage());
        }
    }

    public function buscarTodos(): array {
        try {
            $sql = "SELECT * FROM variacoes";
            $stmt = $this->conexao->query($sql);
            $lista = [];

            $produtosCache = [];
            $produtoIds = [];

            while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtoId = (int) $dados["produto_id"];
                $produtoIds[$produtoId] = $produtoId;
            }

            if (!empty($produtoIds)) {
                $produtos = $this->produtoDAO->buscarPorIds(array_values( $produtoIds));
                foreach ($produtos as $produto) {
                    $produtosCache[$produto->getId()] = $produto;
                }
            }

            $stmt->execute();

            while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtoId = (int) $dados["produto_id"];

                if (!isset($produtosCache[$produtoId])) {
                    throw new Exception("Produto não encontrado para ID: $produtoId");
                }

                $variacao = new Variacoes(
                    $dados["tamanho"],
                    (int) $dados["estoque"],
                    $produtosCache[$produtoId]
                );
                $variacao->setId((int) $dados["id"]);
                $lista[] = $variacao;
            }

            return $lista;

        } catch (PDOException $e) {
            throw new Exception("Erro ao listar variações: " . $e->getMessage());
        }
    }

    public function atualizar(Variacoes $variacao): void {
        $this->conexao->beginTransaction();
        try {
            $sql = "UPDATE variacoes SET
                        tamanho = :tamanho,
                        estoque = :estoque,
                        produto_id = :produto_id
                    WHERE id = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":tamanho", $variacao->getTamanho(), PDO::PARAM_STR);
            $stmt->bindValue(":estoque", $variacao->getEstoque(), PDO::PARAM_INT);
            $stmt->bindValue(":produto_id", $variacao->getProdutoId(), PDO::PARAM_INT);
            $stmt->bindValue(":id", $variacao->getId(), PDO::PARAM_INT);
            $stmt->execute();

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao atualizar variação: " . $e->getMessage());
        }
    }

    public function atualizarEstoque(Variacoes $variacao): void {
        $this->conexao->beginTransaction();
        try {
            $sql = "UPDATE variacoes SET estoque = :estoque WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":estoque", $variacao->getEstoque(), PDO::PARAM_INT);
            $stmt->bindValue(":id", $variacao->getId(), PDO::PARAM_INT);
            $stmt->execute();

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao atualizar estoque: " . $e->getMessage());
        }
    }

    public function excluir(int $id): void {
        $this->conexao->beginTransaction();
        try {
            $sql = "DELETE FROM variacoes WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao excluir variação: " . $e->getMessage());
        }
    }
}
?>