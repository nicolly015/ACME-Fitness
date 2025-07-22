<?php
namespace App\Controller;

require_once __DIR__ . '/../DAO/CategoriaDAO.php';
require_once __DIR__ . '/../DAO/ProdutoDAO.php';
require_once __DIR__ . '/../DAO/VariacaoDAO.php';
require_once __DIR__ . '/../Model/Variacao.php';

use App\DAO\CategoriaDAO;
use App\DAO\ProdutoDAO;
use App\DAO\VariacaoDAO;
use App\Model\Variacao;
use PDO;
use RuntimeException;
use InvalidArgumentException;

class VariacaoController {
    private VariacaoDAO $variacaoDAO;

    public function __construct(PDO $conexao) {
        $categoriaDAO = new CategoriaDAO($conexao);
        $produtoDAO = new ProdutoDAO($conexao, $categoriaDAO);
        $this->variacaoDAO = new VariacaoDAO($conexao, $produtoDAO);
    }

    private function enviarRespostaJSON($dados, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }

    public function criar(array $dados): void {
        try {
            if (!isset($dados["tamanho"], $dados["estoque"], $dados["produto_id"])) {
                throw new InvalidArgumentException("Campos obrigatórios não preenchidos");
            }

            $variacao = new Variacao(
                $dados["tamanho"],
                (int) $dados["estoque"],
                (int) $dados["produto_id"]
            );

            $this->variacaoDAO->salvar($variacao);

            $this->enviarRespostaJSON([
                "mensagem" => "Variação criada com sucesso",
                "id" => $variacao->getId()
            ], 201);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function listarTodos(): void {
        try {
            $variacoes = $this->variacaoDAO->buscarTodos();
            $resultado = [];

            foreach ($variacoes as $v) {
                $resultado[] = [
                    "id" => $v->getId(),
                    "tamanho" => $v->getTamanho(),
                    "estoque" => $v->getEstoque(),
                    "produto_id" => $v->getProdutoId()
                ];
            }

            $this->enviarRespostaJSON($resultado);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $variacao = $this->variacaoDAO->buscarPorId($id);
            if (!$variacao) {
                throw new RuntimeException("Variação não encontrada", 404);
            }

            $this->enviarRespostaJSON([
                "id" => $variacao->getId(),
                "tamanho" => $variacao->getTamanho(),
                "estoque" => $variacao->getEstoque(),
                "produto_id" => $variacao->getProdutoId()
            ]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function atualizar(int $id, array $dados): void {
        try {
            $variacao = $this->variacaoDAO->buscarPorId($id);
            if (!$variacao) {
                throw new RuntimeException("Variação não encontrada", 404);
            }

            if (isset($dados["tamanho"])) {
                $variacao->setTamanho($dados["tamanho"]);
            }

            if (isset($dados["estoque"])) {
                $variacao->setEstoque((int) $dados["estoque"]);
            }

            if (isset($dados["produto_id"])) {
                $variacao->setProdutoId((int) $dados["produto_id"]);
            }

            $this->variacaoDAO->atualizar($variacao);
            $this->enviarRespostaJSON(["mensagem" => "Variação atualizada com sucesso"]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        }
    }

    public function excluir(int $id): void {
        try {
            $variacao = $this->variacaoDAO->buscarPorId($id);
            if (!$variacao) {
                throw new RuntimeException("Variação não encontrada", 404);
            }

            $this->variacaoDAO->excluir($id);
            $this->enviarRespostaJSON(["mensagem" => "Variação excluída com sucesso"]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
?>