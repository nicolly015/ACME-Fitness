<?php
namespace App\Controller;

require_once __DIR__ . '/../DAO/ClienteDAO.php';
require_once __DIR__ . '/../Model/Cliente.php';

use App\DAO\ClienteDAO;
use App\Model\Cliente;
use PDO;
use RuntimeException;
use InvalidArgumentException;
use DateTime;

class ClienteController {
    private ClienteDAO $clienteDAO;

    public function __construct(PDO $conexao) {
        $this->clienteDAO = new ClienteDAO($conexao);
    }

    private function enviarRespostaJSON($dados, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }

    public function criar(array $dados): void {
        try {
            if (!isset($dados["nome_completo"], $dados["cpf"], $dados["data_nascimento"])) {
                throw new InvalidArgumentException("Campos obrigatórios faltando");
            }

            $dataNascimento = DateTime::createFromFormat('Y-m-d', $dados["data_nascimento"]);
            if (!$dataNascimento) {
                throw new InvalidArgumentException("Formato de data inválido");
            }

            $cliente = new Cliente(
                $dados["nome_completo"],
                $dados["cpf"],
                $dataNascimento
            );

            $this->clienteDAO->salvar($cliente);
            $this->enviarRespostaJSON([
                "mensagem" => "Cliente criado com sucesso",
                "id" => $cliente->getId()
            ], 201);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function listarTodos(): void {
        try {
            $clientes = $this->clienteDAO->buscarTodos();
            $retorno = [];

            foreach ($clientes as $c) {
                $retorno[] = [
                    "id" => $c->getId(),
                    "nome_completo" => $c->getNomeCompleto(),
                    "cpf" => $c->getCpf(),
                    "data_nascimento" => $c->getDataNascimento()->format('Y-m-d')
                ];
            }

            $this->enviarRespostaJSON($retorno);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $cliente = $this->clienteDAO->buscarPorId($id);
            if (!$cliente) {
                throw new RuntimeException("Cliente não encontrado", 404);
            }

            $this->enviarRespostaJSON([
                "id" => $cliente->getId(),
                "nome_completo" => $cliente->getNomeCompleto(),
                "cpf" => $cliente->getCpf(),
                "data_nascimento" => $cliente->getDataNascimento()->format('Y-m-d')
            ]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function atualizar(int $id, array $dados): void {
        try {
            $cliente = $this->clienteDAO->buscarPorId($id);
            if (!$cliente) {
                throw new RuntimeException("Cliente não encontrado", 404);
            }

            if (isset($dados["nome_completo"])) {
                $cliente->setNomeCompleto($dados["nome_completo"]);
            }

            if (isset($dados["cpf"])) {
                $cliente->setCpf($dados["cpf"]);
            }

            if (isset($dados["data_nascimento"])) {
                $dataNascimento = DateTime::createFromFormat('Y-m-d', $dados["data_nascimento"]);
                if (!$dataNascimento) {
                    throw new InvalidArgumentException("Formato de data inválido");
                }
                $cliente->setDataNascimento($dataNascimento);
            }

            $this->clienteDAO->atualizar($cliente);
            $this->enviarRespostaJSON(["mensagem" => "Cliente atualizado com sucesso"]);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function excluir(int $id): void {
        try {
            $cliente = $this->clienteDAO->buscarPorId($id);
            if (!$cliente) {
                throw new RuntimeException("Cliente não encontrado", 404);
            }

            $this->clienteDAO->excluir($id);
            $this->enviarRespostaJSON(["mensagem" => "Cliente excluído com sucesso"]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
?>