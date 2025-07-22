<?php
namespace App\Controller;

require_once __DIR__ . '/../DAO/CategoriaDAO.php';
require_once __DIR__ . '/../DAO/ProdutoDAO.php';
require_once __DIR__ . '/../Model/Produto.php';

use App\DAO\CategoriaDAO;
use App\DAO\ProdutoDAO;
use App\Model\Produto;
use PDO;
use RuntimeException;
use InvalidArgumentException;
use DateTime;

class ProdutoController {
    private ProdutoDAO $produtoDAO;
    private CategoriaDAO $categoriaDAO;

    public function __construct(PDO $conexao) {
        $this->categoriaDAO = new CategoriaDAO($conexao);
        $this->produtoDAO = new ProdutoDAO($conexao, $this->categoriaDAO);
    }

    private function enviarRespostaJSON($dados, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }

    public function criar(array $dados, array $arquivoImagem): void {
        try {
            $camposObrigatorios = ["nome", "cor", "preco", "descricao", "peso", "categoria_id"];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    throw new InvalidArgumentException("Campo obrigatório faltando: $campo");
                }
            }

            $caminhoImagem = null;
            if (!empty($arquivoImagem['imagem']['tmp_name'])) {
                $extensao = pathinfo($arquivoImagem['imagem']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'produto_' . uniqid() . '.' . $extensao;
                $diretorio = 'public/uploads/';

                if (!is_dir($diretorio)) {
                    mkdir($diretorio, 0755, true);
                }

                $caminhoImagem = $diretorio . $nomeArquivo;

                if (!move_uploaded_file($arquivoImagem['imagem']['tmp_name'], $caminhoImagem)) {
                    throw new RuntimeException("Falha ao salvar imagem");

                }
            }

            $categoria = $this->categoriaDAO->buscarPorId((int)$dados["categoria_id"]);
            if (!$categoria) {
                throw new InvalidArgumentException("Categoria não encontrada");
            }

            $dataCadastro = isset($dados["data_cadastro"])
                ? DateTime::createFromFormat('Y-m-d H:i:s', $dados["data_cadastro"])
                : new DateTime();

            if (!$dataCadastro) {
                throw new InvalidArgumentException("Formato de data inválido");
            }

            $produto = new Produto(
                $dados["nome"],
                $dados["cor"],
                $caminhoImagem ?? '',
                (float)$dados["preco"],
                $dados["descricao"],
                $dataCadastro,
                (float)$dados["peso"],
                $categoria->getId()
            );

            $this->produtoDAO->salvar($produto);

            $this->enviarRespostaJSON([
                "mensagem" => "Produto criado com sucesso",
                "id" => $produto->getId()
            ], 201);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function listarTodos(): void {
        try {
            $produtos = $this->produtoDAO->buscarTodos();
            $retorno = [];

            foreach ($produtos as $produto) {
                $categoria = $this->categoriaDAO->buscarPorId($produto->getCategoriaId());

                $retorno[] = [
                    "id" => $produto->getId(),
                    "nome" => $produto->getNome(),
                    "cor" => $produto->getCor(),
                    "imagem" => $produto->getImagem(),
                    "preco" => $produto->getPreco(),
                    "descricao" => $produto->getDescricao(),
                    "data_cadastro" => $produto->getDataCadastro()->format('Y-m-d H:i:s'),
                    "peso" => $produto->getPeso(),
                    "categoria" => [
                        "id" => $categoria->getId(),
                        "nome" => $categoria->getNome(),
                        "descricao" => $categoria->getDescricao()
                    ]
                ];
            }

            $this->enviarRespostaJSON($retorno);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 500);
        }
    }

    public function buscarPorId(int $id): void {
        try {
            $produto = $this->produtoDAO->buscarPorId($id);
            if (!$produto) {
                throw new RuntimeException("Produto não encontrado", 404);
            }

            $categoria = $this->categoriaDAO->buscarPorId($produto->getCategoriaId());

            $retorno = [
                "id" => $produto->getId(),
                "nome" => $produto->getNome(),
                "cor" => $produto->getCor(),
                "imagem" => $produto->getImagem(),
                "preco" => $produto->getPreco(),
                "descricao" => $produto->getDescricao(),
                "data_cadastro" => $produto->getDataCadastro()->format('Y-m-d H:i:s'),
                "peso" => $produto->getPeso(),
                "categoria" => [
                    "id" => $categoria->getId(),
                    "nome" => $categoria->getNome(),
                    "descricao" => $categoria->getDescricao()
                ]
            ];

            $this->enviarRespostaJSON($retorno);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function atualizar(int $id, array $dados, array $arquivoImagem): void {
        try {
            $produto = $this->produtoDAO->buscarPorId($id);
            if (!$produto) {
                throw new RuntimeException("Produto não encontrado", 404);
            }

            if (!empty($arquivoImagem['imagem']['tmp_name'])) {
                $extensao = pathinfo($arquivoImagem['imagem']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'produto_' . uniqid() . '.' . $extensao;
                $caminho = 'public/uploads/' . $nomeArquivo;

                if (!move_uploaded_file($arquivoImagem['imagem']['tmp_name'], $caminho)) {
                    throw new RuntimeException("Falha ao salvar imagem");
                }

                if ($produto->getImagem() && file_exists($produto->getImagem())) {
                    unlink($produto->getImagem());
                }

                $produto->setImagem($caminho);
            }

            if (isset($dados["categoria_id"])) {
                $categoria = $this->categoriaDAO->buscarPorId((int)$dados["categoria_id"]);
                if (!$categoria) {
                    throw new InvalidArgumentException("Categoria não encontrada");
                }
                $produto->setCategoria($categoria->getId());
            }

            if (isset($dados["nome"])) $produto->setNome($dados["nome"]);
            if (isset($dados["cor"])) $produto->setCor($dados["cor"]);
            if (isset($dados["preco"])) $produto->setPreco((float)$dados["preco"]);
            if (isset($dados["descricao"])) $produto->setDescricao($dados["descricao"]);
            if (isset($dados["peso"])) $produto->setPeso((float)$dados["peso"]);

            if (isset($dados["data_cadastro"])) {
                $dataCadastro = DateTime::createFromFormat('Y-m-d H:i:s', $dados["data_cadastro"]);
                if (!$dataCadastro) {
                    throw new InvalidArgumentException("Formato de data inválido");
                }
                $produto->setDataCadastro($dataCadastro);
            }

            $this->produtoDAO->atualizar($produto);
            $this->enviarRespostaJSON(["mensagem" => "Produto atualizado com sucesso"]);

        } catch (InvalidArgumentException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function excluir(int $id): void {
        try {
            $produto = $this->produtoDAO->buscarPorId($id);
            if (!$produto) {
                throw new RuntimeException("Produto não encontrado", 404);
            }

            if ($produto->getImagem() && file_exists($produto->getImagem())) {
                unlink($produto->getImagem());
            }

            $this->produtoDAO->excluir($id);
            $this->enviarRespostaJSON(["mensagem" => "Produto excluído com sucesso"]);

        } catch (RuntimeException $e) {
            $this->enviarRespostaJSON(["erro" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
?>