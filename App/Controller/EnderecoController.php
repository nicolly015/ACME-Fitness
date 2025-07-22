<?php
namespace App\Controller;

require_once __DIR__ . '/../DAO/EnderecoDAO.php';
require_once __DIR__ . '/../DAO/ClienteDAO.php';
require_once __DIR__ . '/../Model/Endereco.php';

use App\DAO\EnderecoDAO;
use App\DAO\ClienteDAO;
use App\Model\Endereco;
use PDO;

class EnderecoController {
    private EnderecoDAO $enderecoDAO;
    private ClienteDAO $clienteDAO;

    public function __construct(PDO $conexao) {
        $this->enderecoDAO = new EnderecoDAO($conexao);
        $this->clienteDAO = new ClienteDAO($conexao);
    }

    public function criar(array $dados){
        try {
            $camposObrigatorios = ["logradouro", "cidade", "bairro", "numero", "cep", "cliente_id"];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "O campo '$campo' é obrigatório"]);
                    return;
                }
            }

            if (!$this->clienteDAO->buscarPorId((int)$dados["cliente_id"])) {
                http_response_code(400);
                echo json_encode(["erro" => "Cliente não encontrado"]);
                return;
            }

            $endereco = new Endereco(
                $dados["logradouro"],
                $dados["cidade"],
                $dados["bairro"],
                $dados["numero"],
                $dados["cep"],
                $dados["complemento"] ?? '',
                (int)$dados["cliente_id"]
            );

            $this->enderecoDAO->salvar($endereco);

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Endereço criado com sucesso",
                "id" => $endereco->getId(),
                "dados" => $this->formatarEnderecoResposta($endereco)
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao criar endereço: " . $e->getMessage()]);
        }
    }

    public function listarTodos(): void {
    try {
        $enderecos = $this->enderecoDAO->buscarTodos();

        if (empty($enderecos)) {
            http_response_code(200);
            echo json_encode([]);
            return;
        }

        $retorno = array_map(
            fn($endereco) => $this->formatarEnderecoResposta($endereco),
            $enderecos
        );

        http_response_code(200);
        echo json_encode($retorno);

    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "erro" => "Erro ao listar endereços",
            "detalhes" => $e->getMessage()
        ]);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode([
            "erro" => "Erro inesperado ao listar endereços",
            "detalhes" => $e->getMessage()
        ]);
    }
}

    public function buscarPorId(int $id): void {
        try {
            $endereco = $this->enderecoDAO->buscarPorId($id);

            if (!$endereco) {
                http_response_code(404);
                echo json_encode(["erro" => "Endereço não encontrado"]);
                return;
            }

            echo json_encode($this->formatarEnderecoResposta($endereco));

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao buscar endereço: " . $e->getMessage()]);
        }
    }

    public function atualizar(int $id, array $dados): void {
        try {
            $endereco = $this->enderecoDAO->buscarPorId($id);

            if (!$endereco) {
                http_response_code(404);
                echo json_encode(["erro" => "Endereço não encontrado"]);
                return;
            }

            if (isset($dados["cliente_id"])) {
                if (!$this->clienteDAO->buscarPorId((int)$dados["cliente_id"])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Cliente não encontrado"]);
                    return;
                }
                $endereco->setCliente((int)$dados["cliente_id"]);
            }

            if (isset($dados["logradouro"])) $endereco->setLogradouro($dados["logradouro"]);
            if (isset($dados["cidade"])) $endereco->setCidade($dados["cidade"]);
            if (isset($dados["bairro"])) $endereco->setBairro($dados["bairro"]);
            if (isset($dados["numero"])) $endereco->setNumero($dados["numero"]);
            if (isset($dados["cep"])) $endereco->setCep($dados["cep"]);
            if (isset($dados["complemento"])) $endereco->setComplemento($dados["complemento"]);

            $this->enderecoDAO->atualizar($endereco);

            echo json_encode([
                "mensagem" => "Endereço atualizado com sucesso",
                "dados" => $this->formatarEnderecoResposta($endereco)
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao atualizar endereço: " . $e->getMessage()]);
        }
    }

    public function excluir(int $id): void {
        try {
            $endereco = $this->enderecoDAO->buscarPorId($id);

            if (!$endereco) {
                http_response_code(404);
                echo json_encode(["erro" => "Endereço não encontrado"]);
                return;
            }

            $this->enderecoDAO->excluir($id);

            echo json_encode([
                "mensagem" => "Endereço excluído com sucesso",
                "id" => $id
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao excluir endereço: " . $e->getMessage()]);
        }
    }

    public function buscarPorCliente(int $clienteId): void {
        try {
            if (!$this->clienteDAO->buscarPorId($clienteId)) {
                http_response_code(404);
                echo json_encode(["erro" => "Cliente não encontrado"]);
                return;
            }

            $enderecos = $this->enderecoDAO->buscarPorCliente($clienteId);
            $retorno = [];

            foreach ($enderecos as $endereco) {
                $retorno[] = $this->formatarEnderecoResposta($endereco);
            }

            echo json_encode($retorno);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao buscar endereços do cliente: " . $e->getMessage()]);
        }
    }

    private function formatarEnderecoResposta(Endereco $endereco): array {
        return [
            "id" => $endereco->getId(),
            "logradouro" => $endereco->getLogradouro(),
            "cidade" => $endereco->getCidade(),
            "bairro" => $endereco->getBairro(),
            "numero" => $endereco->getNumero(),
            "cep" => $endereco->getCep(),
            "complemento" => $endereco->getComplemento(),
            "cliente_id" => $endereco->getClienteId()
        ];
    }
}
?>