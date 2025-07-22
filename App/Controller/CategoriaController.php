<?php
namespace App\Controller;

require_once __DIR__ . '/../DAO/CategoriaDAO.php';
require_once __DIR__ . '/../Model/Categoria.php';

use App\DAO\CategoriaDAO;
use App\Model\Categoria;
use PDO;

class CategoriaController {
    private CategoriaDAO $categoriaDAO;

    public function __construct(PDO $conexao) {
        $this->categoriaDAO = new CategoriaDAO($conexao);
    }

    public function criar(array $dados): void {
        if (empty($dados["nome"])) {
            http_response_code(400);
            echo json_encode(["erro" => "Os campos 'nome' e 'descricao' são obrigatórios"]);
            return;
        }

        try {
            $categoria = new Categoria(
                $dados["nome"],
                $dados["descricao"] ?? null
            );

            $this->categoriaDAO->salvar($categoria);

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Categoria criada com sucesso",
                "id" => $categoria->getId(),
                "dados" => [
                    "nome" => $categoria->getNome(),
                    "descricao" => $categoria->getDescricao()
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao criar categoria: " . $e->getMessage()]);
        }
    }

    public function listarTodos(): void {
        try {
            $categorias = $this->categoriaDAO->buscarTodos();
            $retorno = [];

            foreach ($categorias as $categoria) {
                $retorno[] = [
                    "id" => $categoria->getId(),
                    "nome" => $categoria->getNome(),
                    "descricao" => $categoria->getDescricao()
                ];
            }

            echo json_encode($retorno);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao listar categorias: " . $e->getMessage()]);
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $categoria = $this->categoriaDAO->buscarPorId($id);

            if (!$categoria) {
                http_response_code(404);
                echo json_encode(["erro" => "Categoria não encontrada"]);
                return;
            }

            echo json_encode([
                "id" => $categoria->getId(),
                "nome" => $categoria->getNome(),
                "descricao" => $categoria->getDescricao()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao buscar categoria: " . $e->getMessage()]);
        }
    }

    public function atualizar(int $id, array $dados): void {
        try {
            $categoria = $this->categoriaDAO->buscarPorId($id);

            if (!$categoria) {
                http_response_code(404);
                echo json_encode(["erro" => "Categoria não encontrada"]);
                return;
            }

            if (isset($dados["nome"]) && empty($dados["nome"])) {
                http_response_code(400);
                echo json_encode(["erro" => "O campo 'nome' não pode ser vazio"]);
                return;
            }

            $categoria->setNome($dados["nome"] ?? $categoria->getNome());
            $categoria->setDescricao($dados["descricao"] ?? $categoria->getDescricao());

            $this->categoriaDAO->atualizar($categoria);

            echo json_encode([
                "mensagem" => "Categoria atualizada com sucesso",
                "dados" => [
                    "id" => $categoria->getId(),
                    "nome" => $categoria->getNome(),
                    "descricao" => $categoria->getDescricao()
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao atualizar categoria: " . $e->getMessage()]);
        }
    }

    public function excluir(int $id): void {
        try {
            $categoria = $this->categoriaDAO->buscarPorId($id);

            if (!$categoria) {
                http_response_code(404);
                echo json_encode(["erro" => "Categoria não encontrada"]);
                return;
            }

            $this->categoriaDAO->excluir($id);

            echo json_encode([
                "mensagem" => "Categoria excluída com sucesso",
                "id" => $id
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao excluir categoria: " . $e->getMessage()]);
        }
    }
}
?>