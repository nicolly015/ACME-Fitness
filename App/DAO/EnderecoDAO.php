<?php
namespace App\DAO;

use App\Model\Endereco;
use PDO;
use PDOException;
use RuntimeException;

class EnderecoDAO {
    private PDO $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    public function salvar(Endereco $endereco): void {
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO enderecos
                    (logradouro, cidade, bairro, numero, cep, complemento, cliente_id)
                    VALUES
                    (:logradouro, :cidade, :bairro, :numero, :cep, :complemento, :cliente_id)";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':logradouro' => $endereco->getLogradouro(),
                ':cidade' => $endereco->getCidade(),
                ':bairro' => $endereco->getBairro(),
                ':numero' => $endereco->getNumero(),
                ':cep' => $this->formatarCep($endereco->getCep()),
                ':complemento' => $endereco->getComplemento(),
                ':cliente_id' => $endereco->getClienteId()
            ]);

            $endereco->setId((int)$this->conexao->lastInsertId());
            $this->conexao->commit();

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            throw new RuntimeException("Erro ao salvar endereço: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Endereco {
        try {
            $sql = "SELECT * FROM enderecos WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id' => $id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados) return null;

            return $this->mapearParaObjeto($dados);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao buscar endereço: " . $e->getMessage());
        }
    }

    public function buscarTodos(): array {
    try {
        $sql = "SELECT * FROM enderecos";
        $stmt = $this->conexao->query($sql);

        $enderecos = [];
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $enderecos[] = $this->mapearParaObjeto($dados);
        }

        return $enderecos;
    } catch (PDOException $e) {
        throw new RuntimeException("Erro ao buscar todos os endereços: " . $e->getMessage());
    }
}

    public function buscarPorCliente(int $clienteId): array {
        try {
            $sql = "SELECT * FROM enderecos WHERE cliente_id = :cliente_id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':cliente_id' => $clienteId]);

            return array_map(
                fn($dados) => $this->mapearParaObjeto($dados),
                $stmt->fetchAll(PDO::FETCH_ASSOC)
            );

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao buscar endereços: " . $e->getMessage());
        }
    }

    public function atualizar(Endereco $endereco): void {
        try {
            $sql = "UPDATE enderecos SET
                    logradouro = :logradouro,
                    cidade = :cidade,
                    bairro = :bairro,
                    numero = :numero,
                    cep = :cep,
                    complemento = :complemento
                    WHERE id = :id";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':logradouro' => $endereco->getLogradouro(),
                ':cidade' => $endereco->getCidade(),
                ':bairro' => $endereco->getBairro(),
                ':numero' => $endereco->getNumero(),
                ':cep' => $this->formatarCep($endereco->getCep()),
                ':complemento' => $endereco->getComplemento(),
                ':id' => $endereco->getId()
            ]);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao atualizar endereço: " . $e->getMessage());
        }
    }

    public function excluir(int $id): void {
        try {
            $sql = "DELETE FROM enderecos WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id' => $id]);

        } catch (PDOException $e) {
            throw new RuntimeException("Erro ao excluir endereço: " . $e->getMessage());
        }
    }

    private function mapearParaObjeto(array $dados): Endereco {
    $endereco = new Endereco(
        $dados['logradouro'],
        $dados['cidade'],
        $dados['bairro'],
        $dados['numero'],
        $dados['cep'],
        $dados['complemento'],
        (int)$dados['cliente_id']
    );

    $endereco->setId((int)$dados['id']);
    return $endereco;
}

    private function formatarCep(string $cep): string {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            throw new \InvalidArgumentException("CEP deve conter 8 dígitos");
        }

        return $cep;
    }
}
?>