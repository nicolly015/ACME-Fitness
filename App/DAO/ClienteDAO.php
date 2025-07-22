<?php
namespace App\DAO;

require_once __DIR__ . '/../Model/Cliente.php';

use App\Model\Cliente;
use PDO;
use PDOException;
use RuntimeException;

class ClienteDAO {
    private PDO $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    public function salvar(Cliente $cliente): void {
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO cliente (nome_completo, cpf, data_nascimento)
                    VALUES (:nome_completo, :cpf, :data_nascimento)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome_completo' => $cliente->getNomeCompleto(),
                ':cpf' => $this->formatarCPF($cliente->getCpf()),
                ':data_nascimento' => $cliente->getDataNascimento()->format('Y-m-d')
            ]);

            $cliente->setId((int)$this->conexao->lastInsertId());
            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new RuntimeException("Erro ao salvar cliente: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Cliente {
        try {
            $sql = "SELECT * FROM cliente WHERE id_cliente = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id' => $id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados) return null;

            return new Cliente(
                $dados['nome_completo'],
                $dados['cpf'],
                new \DateTime($dados['data_nascimento'])
            )->setId((int)$dados['id_cliente']);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao buscar cliente: " . $e->getMessage());
        }
    }

    public function buscarTodos(): array {
        try {
            $stmt = $this->conexao->query("SELECT * FROM cliente");
            return array_map(
                function ($dados) {
                    return (new Cliente(
                        $dados['nome_completo'],
                        $dados['cpf'],
                        new \DateTime($dados['data_nascimento'])
                    ))->setId((int)$dados['id_cliente']);
                },
                $stmt->fetchAll(PDO::FETCH_ASSOC)
            );

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao listar clientes: " . $e->getMessage());
        }
    }

    public function atualizar(Cliente $cliente): void {
        try {
            $sql = "UPDATE cliente SET
                    nome_completo = :nome,
                    cpf = :cpf,
                    data_nascimento = :nasc
                    WHERE id_cliente = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome' => $cliente->getNomeCompleto(),
                ':cpf' => $this->formatarCPF($cliente->getCpf()),
                ':nasc' => $cliente->getDataNascimento()->format('Y-m-d'),
                ':id' => $cliente->getId()
            ]);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao atualizar cliente: " . $e->getMessage());
        }
    }

    public function excluir(int $id): void {
        try {
            $sql = "DELETE FROM cliente WHERE id_cliente = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id' => $id]);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao excluir cliente: " . $e->getMessage());
        }
    }

    private function formatarCPF(string $cpf): string {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            throw new \InvalidArgumentException("CPF deve conter 11 dígitos");
        }

        return $cpf;
    }
}
?>