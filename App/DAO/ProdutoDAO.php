<?php
namespace App\DAO;

require_once __DIR__ . '/../Model/Produto.php';
require_once __DIR__ . '/../DAO/CategoriaDAO.php';

use App\Model\Produto;
use App\DAO\CategoriaDAO;
use PDO;
use PDOException;
use DateTime;
use Exception;

class ProdutoDAO {
    private PDO $conexao;
    private CategoriaDAO $categoriaDAO;

    public function __construct(PDO $conexao, CategoriaDAO $categoriaDAO) {
        $this->conexao = $conexao;
        $this->categoriaDAO = $categoriaDAO;
    }

    public function salvar(Produto $produto): void {
        $this->conexao->beginTransaction();

        try {
            $sql = "INSERT INTO produtos (nome, cor, imagem, preco, descricao, data_cadastro, peso, categoria_id)
                    VALUES (:nome, :cor, :imagem, :preco, :descricao, :data_cadastro, :peso, :categoria_id)";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":nome", $produto->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(":cor", $produto->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(":imagem", $produto->getImagem(), PDO::PARAM_STR);
            $stmt->bindValue(":preco", $produto->getPreco(), PDO::PARAM_STR);
            $stmt->bindValue(":descricao", $produto->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(":data_cadastro", $produto->getDataCadastro()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(":peso", $produto->getPeso(), PDO::PARAM_STR);
            $stmt->bindValue(":categoria_id", $produto->getCategoriaId(), PDO::PARAM_INT);

            $stmt->execute();

            $produto->setId((int) $this->conexao->lastInsertId());
            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao salvar produto: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Produto {
        try {
            $sql = "SELECT * FROM produtos WHERE id_produto = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$dados) return null;

            $categoria = $this->categoriaDAO->buscarPorId((int) $dados["categoria_id"]);
            if (!$categoria) {
                throw new Exception("Categoria não encontrada para o Id: " . $dados["categoria_id"]);
            }

            $produto = new Produto(
                $dados["nome"],
                $dados["cor"],
                $dados["imagem"],
                (float) $dados["preco"],
                $dados["descricao"],
                new DateTime($dados["data_cadastro"]),
                (float) $dados["peso"],
                $categoria->getId()//-
            );
            $produto->setId((int) $dados["id_produto"]);
            return $produto;

        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto: " . $e->getMessage());
        }
    }

    public function buscarPorIds(array $ids): array {
    if (empty($ids)) return [];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT * FROM produtos WHERE id_produto IN ($placeholders)";
    $stmt = $this->conexao->prepare($sql);
    $stmt->execute($ids);

    $produtos = [];
    while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoria = $this->categoriaDAO->buscarPorId((int) $dados["categoria_id"]);
        $produto = new Produto(
            $dados["nome"],
            $dados["cor"],
            $dados["imagem"],
            (float) $dados["preco"],
            $dados["descricao"],
            new DateTime($dados["data_cadastro"]),
            (float) $dados["peso"],
            $categoria->getId()
        );
        $produto->setId((int) $dados["id_produto"]);
        $produtos[] = $produto;
    }

    return $produtos;
}
    public function buscarTodos(): array {
        try {
            $sql = "SELECT * FROM produtos";
            $stmt = $this->conexao->query($sql);
            $produtos = [];

            $categoriasCache = [];

            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categoriaId = (int) $linha["categoria_id"];

                if (!isset($categoriasCache[$categoriaId])) {
                    $categoria = $this->categoriaDAO->buscarPorId($categoriaId);
                    if (!$categoria) {
                        throw new Exception("Categoria não encontrada para o Id: $categoriaId");
                    }
                    $categoriasCache[$categoriaId] = $categoria;
                }

                $produto = new Produto(
                    $linha["nome"],
                    $linha["cor"],
                    $linha["imagem"],
                    (float) $linha["preco"],
                    $linha["descricao"],
                    new DateTime($linha["data_cadastro"]),
                    (float) $linha["peso"],
                    $categoriasCache[$categoriaId]->getId()//-
                );
                $produto->setId((int) $linha["id_produto"]);
                $produtos[] = $produto;
            }

            return $produtos;

        } catch (PDOException $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }

    public function atualizar(Produto $produto): void {
        $this->conexao->beginTransaction();

        try {
            $sql = "UPDATE produtos SET
                        nome = :nome,
                        cor = :cor,
                        imagem = :imagem,
                        preco = :preco,
                        descricao = :descricao,
                        data_cadastro = :data_cadastro,
                        peso = :peso,
                        categoria_id = :categoria_id
                    WHERE id_produto = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":nome", $produto->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(":cor", $produto->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(":imagem", $produto->getImagem(), PDO::PARAM_STR);
            $stmt->bindValue(":preco", $produto->getPreco(), PDO::PARAM_STR);
            $stmt->bindValue(":descricao", $produto->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(":data_cadastro", $produto->getDataCadastro()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(":peso", $produto->getPeso(), PDO::PARAM_STR);

            $stmt->bindValue(":categoria_id", $produto->getCategoriaId(), PDO::PARAM_INT);

            $stmt->bindValue(":id", $produto->getId(), PDO::PARAM_INT);
            $stmt->execute();

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    public function excluir(int $id): void {
        $this->conexao->beginTransaction();

        try {
            $sql = "DELETE FROM produtos WHERE id_produto = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao excluir produto: " . $e->getMessage());
        }
    }
}
?>