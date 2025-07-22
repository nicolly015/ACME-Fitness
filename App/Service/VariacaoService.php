<?php
namespace Service;

require_once __DIR__ . '/../DAO/VariacaoDAO.php';
require_once __DIR__ . '/../Model/Pedido.php';
require_once __DIR__ . '/../Model/ItensPedido.php';


use App\DAO\VariacaoDAO;
use App\Model\Pedido;
use App\Model\ItensPedido;
use Exception;

class VariacaoService {
    private VariacaoDAO $variacaoDAO;

    public function __construct(VariacaoDAO $variacaoDAO) {
        $this->variacaoDAO = $variacaoDAO;
    }

    public function atualizarEstoqueAPartirDoPedido(Pedido $pedido): void {
        foreach ($pedido->getItens() as $item) {
            if (!($item instanceof ItensPedido)) {
                throw new Exception("Item do pedido inválido");
            }

            $variacao = $this->variacaoDAO->buscarPorId($item->getVariacaoId());
            if (!$variacao) {
                throw new Exception("Variação não encontrada para ID: " . $item->getVariacaoId());
            }

            $estoqueAtual = $variacao->getEstoque();
            $quantidadeVendida = $item->getQuantidade();

            if ($quantidadeVendida > $estoqueAtual) {
                throw new Exception(
                    "Estoque insuficiente para a variação ID {$variacao->getId()}. " .
                    "Disponível: {$estoqueAtual}, Solicitado: {$quantidadeVendida}"
                );
            }

            $variacao->setEstoque($estoqueAtual - $quantidadeVendida);
            $this->variacaoDAO->atualizarEstoque($variacao);
        }
    }
}
?>