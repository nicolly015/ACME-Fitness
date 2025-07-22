<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../Controller/ClienteController.php';
require_once __DIR__ . '/../Controller/EnderecoController.php';
require_once __DIR__ . '/../Controller/ProdutoController.php';
require_once __DIR__ . '/../Controller/CategoriaController.php';
require_once __DIR__ . '/../Controller/VariacaoController.php';
require_once __DIR__ . '/../Controller/PedidoController.php';
require_once __DIR__ . '/../Model/Cliente.php';
require_once __DIR__ . '/../Model/Endereco.php';
require_once __DIR__ . '/../Model/Produto.php';
require_once __DIR__ . '/../Model/Variacao.php';
require_once __DIR__ . '/../Model/Categoria.php';
require_once __DIR__ . '/../Model/Pedido.php';


use App\Controller\ClienteController;
use App\Controller\EnderecoController;
use App\Controller\ProdutoController;
use App\Controller\CategoriaController;
use App\Controller\VariacaoController;
use App\Controller\PedidoController;

require_once __DIR__ . '/../../Config/Database.php';

use Config\Conexao;

$pdo = Conexao::getConexao();

$clienteController = new ClienteController($pdo);
$enderecoController = new EnderecoController($pdo);
$produtoController = new ProdutoController($pdo);
$categoriaController = new CategoriaController($pdo);
$variacaoController = new VariacaoController($pdo);
$pedidoController = new PedidoController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); // ou api.php
$uri = str_replace($basePath, '', $uri);


switch ("$method $uri") {
    case 'GET /clientes':
        $clienteController->listarTodos();
        break;
    case 'POST /clientes':
        $dados = json_decode(file_get_contents('php://input'), true);
        $clienteController->criar($dados);
        break;
    case 'GET /clientes/' . basename($uri):
        $id = (int)basename($uri);
        $clienteController->buscarPorId($id);
        break;
    case 'PUT /clientes/' . basename($uri):
        $id = (int)basename($uri);
        $dados = json_decode(file_get_contents('php://input'), true);
        $clienteController->atualizar($id, $dados);
        break;
    case 'DELETE /clientes' . basename($uri):
        $id = (int)basename($uri);
        $clienteController->excluir($id);
        break;

    case 'GET /enderecos':
        $enderecoController->listarTodos();
        break;
    case 'POST /enderecos':
        $dados = json_decode(file_get_contents('php://input'), true);
        $enderecoController->criar($dados);
        break;
    case 'GET /enderecos/' . basename($uri):
        $id = (int)basename($uri);
        $enderecoController->buscarPorId($id);
        break;
    case 'PUT /enderecos/' . basename($uri):
        $id = (int)basename($uri);
        $dados = json_decode(file_get_contents('php://input'), true);
        $enderecoController->atualizar($id, $dados);
        break;
    case 'DELETE /enderecos/' . basename($uri):
        $id = (int)basename($uri);
        $enderecoController->excluir($id);
        break;
    case 'GET /clientes/' . dirname($uri, 2) . '/enderecos':
        $clienteId = (int)basename(dirname($uri));
        $enderecoController->buscarPorCliente($clienteId);
        break;

    case 'GET /produtos':
        $produtoController->listarTodos();
        break;
    case 'POST /produtos':
        $dados = $_POST;
        $arquivo = $_FILES['imagem'] ?? [];
        $produtoController->criar($dados, $arquivo);
        break;
    case 'GET /produtos/' . basename($uri):
        $id = (int)basename($uri);
        $produtoController->buscarPorId($id);
        break;
    case 'PUT /produtos/' . basename($uri):
        $id = (int)basename($uri);
        $dados = $_POST;
        $arquivo = $_FILES['imagem'] ?? [];
        $produtoController->atualizar($id, $dados, $arquivo);
        break;
    case 'DELETE /produtos/' . basename($uri):
        $id = (int)basename($uri);
        $produtoController->excluir($id);
        break;

    case 'GET /categorias':
        $categoriaController->listarTodos();
        break;
    case 'POST /categorias':
        $dados = json_decode(file_get_contents('php://input'), true);
        $categoriaController->criar($dados);
        break;
    case 'GET /categorias/' . basename($uri):
        $id = (int)basename($uri);
        $categoriaController->buscarPorId($id);
        break;
    case 'PUT /categorias/' . basename($uri):
        $id = (int)basename($uri);
        $dados = json_decode(file_get_contents('php://input'), true);
        $categoriaController->atualizar($id, $dados);
        break;
    case 'DELETE /categorias/' . basename($uri):
        $id = (int)basename($uri);
        $categoriaController->excluir($id);
        break;

    case 'GET /variacoes':
        $variacaoController->listarTodos();
        break;
    case 'POST /variacoes':
        $dados = json_decode(file_get_contents('php://input'), true);
        $variacaoController->criar($dados);
        break;
    case 'GET /variacoes/' . basename($uri):
        $id = (int)basename($uri);
        $variacaoController->buscarPorId($id);
        break;
    case 'PUT /variacoes/' . basename($uri):
        $id = (int)basename($uri);
        $dados = json_decode(file_get_contents('php://input'), true);
        $variacaoController->atualizar($id, $dados);
        break;
    case 'DELETE /variacoes/' . basename($uri):
        $id = (int)basename($uri);
        $variacaoController->excluir($id);
        break;

    case 'POST /pedidos':
        $dados = json_decode(file_get_contents('php://input'), true);
        $pedidoController->criar($dados);
        break;
    case 'GET /relatorios/produtos-mais-vendidos':
        $pedidoController->produtosMaisVendidos();
        break;
    case 'GET /relatorios/produtos-mais-vendidos/' . basename($uri):
        $limit = (int)basename($uri);
        $pedidoController->produtosMaisVendidos($limit);
        break;

    default:
        http_response_code(404);
        echo json_encode(['erro' => 'Endpoint não encontrado']);
        break;
}
?>