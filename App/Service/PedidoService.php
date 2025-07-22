<?php
namespace Services;

use App\Model\Pedido;
use App\Model\ItensPedido;
use Services\VariacaoService;
use Enums\FormaDePagamento;
use Exception;

class PedidoService {
    private VariacaoService $variacaoService;

    public function __construct(VariacaoService $variacaoService) {
        $this->variacaoService = $variacaoService;
    }

    public function adicionarItemAoPedido(Pedido $pedido, ItensPedido $item): void {
        if ($item->getQuantidade() <= 0) {
            throw new Exception("Quantidade deve ser maior que zero");
        }
        $pedido->adicionarItem($item);
    }

    public function calcularValorTotal(Pedido $pedido): float {
        if (empty($pedido->getItens())) {
            throw new Exception("Pedido deve conter pelo menos um item");
        }

        $totalItens = array_reduce($pedido->getItens(),
            fn($carry, $item) => $carry + $item->getSubtotal(),
            0
        );

        $pedido->setValorTotal($totalItens);
        return $totalItens;
    }

    public function calcularTotalFinal(Pedido $pedido): float {
        $valorItens = $this->calcularValorTotal($pedido);
        $frete = $pedido->getValorFrete();

        if ($pedido->getFormaPagamento() === FormaDePagamento::PIX) {
            $desconto = ($valorItens + $frete) * 0.10;
            $pedido->setDesconto($desconto);
        }

        return ($valorItens + $frete) - $pedido->getDesconto();
    }

    public function finalizarPedido(Pedido $pedido): void {
        try {
            $valorFinal = $this->calcularTotalFinal($pedido);
            $pedido->setValorTotal($valorFinal);
            $this->variacaoService->atualizarEstoqueAPartirDoPedido($pedido);
        } catch (Exception $e) {
            throw new Exception("Erro ao finalizar pedido: " . $e->getMessage());
        }
    }
}
?>