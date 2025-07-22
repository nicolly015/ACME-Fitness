<?php
namespace App\Controller;

use App\DAO\PedidoDAO;
use App\DAO\ClienteDAO;
use App\DAO\EnderecoDAO;
use App\DAO\VariacaoDAO;
use App\DAO\CategoriaDAO;
use App\DAO\ProdutoDAO;
use App\Model\Pedido;
use App\Model\ItensPedido;
use Services\PedidoService;
use Services\VariacaoService;
use Enums\FormaDePagamento;
use PDO;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class PedidoController {
    private PedidoDAO $pedidoDAO;
    private ClienteDAO $clienteDAO;
    private EnderecoDAO $enderecoDAO;
    private VariacaoDAO $variacaoDAO;
    private PedidoService $pedidoService;

    public function __construct(PDO $pdo) {
        $this->clienteDAO = new ClienteDAO($pdo);
        $this->enderecoDAO = new EnderecoDAO($pdo);

        $categoriaDAO = new CategoriaDAO($pdo);
        $produtoDAO = new ProdutoDAO($pdo, $categoriaDAO);
        $this->variacaoDAO = new VariacaoDAO($pdo, $produtoDAO);

        $variacaoService = new VariacaoService($this->variacaoDAO);
        $this->pedidoService = new PedidoService($variacaoService);

        $this->pedidoDAO = new PedidoDAO($pdo);
    }

    private function enviarRespostaJSON($dados, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }

    public function criar(array $dados): void {
        try {
            $camposObrigatorios = ['cliente_id', 'endereco_id', 'forma_pagamento', 'itens'];
            foreach ($camposObrigatorios as $campo) {
                if (!isset($dados[$campo])) {
                    throw new InvalidArgumentException("Campo obrigatório faltando: $campo");
                }
            }

            if (!is_array($dados['itens']) || empty($dados['itens'])) {
                throw new InvalidArgumentException("O pedido deve conter pelo menos um item");
            }

            $clienteId = (int)$dados['cliente_id'];
            $enderecoId = (int)$dados['endereco_id'];

            if (!$this->clienteDAO->buscarPorId($clienteId)) {
                throw new RuntimeException("Cliente não encontrado", 404);
            }

            if (!$this->enderecoDAO->buscarPorId($enderecoId)) {
                throw new RuntimeException("Endereço não encontrado", 404);
            }

            $pedido = new Pedido(
                0,
                (float)($dados['valor_frete'] ?? 10.00),
                0,
                FormaDePagamento::from($dados['forma_pagamento']),
                $enderecoId,
                $clienteId
            );

            foreach ($dados['itens'] as $item) {
                if (!isset($item['variacao_id'], $item['quantidade'], $item['preco_venda'])) {
                    throw new InvalidArgumentException("Item incompleto, campos obrigatórios faltando");
                }

                $variacaoId = (int)$item['variacao_id'];
                $variacao = $this->variacaoDAO->buscarPorId($variacaoId);

                if (!$variacao) {
                    throw new RuntimeException("Variação não encontrada: $variacaoId", 404);
                }

                $itemPedido = new ItensPedido(
                    (float)$item['preco_venda'],
                    (int)$item['quantidade'],
                    0,
                    $variacaoId
                );

                $this->pedidoService->adicionarItemAoPedido($pedido, $itemPedido);
            }

            $this->pedidoService->finalizarPedido($pedido);

            $this->pedidoDAO->criar($pedido);

            $this->enviarRespostaJSON([
                "mensagem" => "Pedido criado com sucesso",
                "pedido_id" => $pedido->getId(),
                "valor_total" => $pedido->getValorTotal()
            ], 201);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode());
        } catch (Exception $e) {
            $this->enviarRespostaJSON(["erro" => "Erro interno: " . $e->getMessage()], 500);
        }
    }

    public function listar(): void {
        try {
            $pedidos = [];
            $this->enviarRespostaJSON($pedidos);

        } catch (Exception $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function buscar(int $id): void {
        try {
            $pedido = null;

            if (!$pedido) {
                throw new RuntimeException("Pedido não encontrado", 404);
            }

            $this->enviarRespostaJSON($pedido);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode());
        } catch (Exception $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function atualizar(int $id, array $dados): void {
        $this->enviarRespostaJSON(["erro" => "Operação não suportada para pedidos"], 405);
    }

    public function excluir(int $id): void {
        $this->enviarRespostaJSON(["erro" => "Operação não suportada para pedidos"], 405);
    }

    public function produtosMaisVendidos(int $limit = 10): void {
        try {
            $produtos = $this->pedidoDAO->buscarProdutosMaisVendidos($limit);
            $this->enviarRespostaJSON($produtos);

        } catch (Exception $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }
}
?>