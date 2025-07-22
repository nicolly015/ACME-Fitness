<?php
namespace App\DAO;

require_once __DIR__ . '/../Model/Categoria.php';

use App\Model\Categoria;
use PDO;
use PDOException;
use RuntimeException;

class CategoriaDAO {
    private PDO $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    public function salvar(Categoria $categoria): void {
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO categorias (nome, descricao) VALUES (:nome, :descricao)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome' => $categoria->getNome(),
                ':descricao' => $categoria->getDescricao()
            ]);

            $categoria->setId((int)$this->conexao->lastInsertId());
            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new RuntimeException("Erro ao salvar categoria: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Categoria {
        $sql = "SELECT * FROM categorias WHERE id_categoria = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return null;

        return $this->mapearParaObjeto($dados);
    }

    public function buscarTodos(): array {
        $stmt = $this->conexao->query("SELECT * FROM categorias");
        return array_map(
            fn($dados) => $this->mapearParaObjeto($dados),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function atualizar(Categoria $categoria): void {
        try {
            $sql = "UPDATE categorias SET nome = :nome, descricao = :descricao WHERE id_categoria = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome' => $categoria->getNome(),
                ':descricao' => $categoria->getDescricao(),
                ':id' => $categoria->getId()
            ]);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao atualizar categoria: " . $e->getMessage());
        }
    }

    public function excluir(int $id): void {
        try {
            $sql = "DELETE FROM categorias WHERE id_categoria = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao excluir categoria: " . $e->getMessage());
        }
    }

    private function mapearParaObjeto(array $dados): Categoria {
        $categoria = new Categoria($dados['nome'], $dados['descricao']);
        $categoria->setId((int)$dados['id_categoria']);
        return $categoria;
    }
}
?>