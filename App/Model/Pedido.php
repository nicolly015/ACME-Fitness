<?php
namespace App\Model;

require_once __DIR__ . '/../Enums/FormaPagamentoEnum.php';

use DateTime;
use Enums\FormaDePagamento;

class Pedido {
    private ?int $id = null;
    private float $valorTotal;
    private float $valorFrete = 10.00;
    private ?float $desconto = 0;
    private FormaDePagamento $formaPagamento;
    private DateTime $dataPedido;
    private int $enderecoId;
    private int $clienteId;
    private array $itens = [];

    public function __construct(float $valorTotal, float $valorFrete, ?float $desconto, FormaDePagamento $formaPagamento, int $enderecoId, int $clienteId) {
        $this->valorTotal = $valorTotal;
        $this->valorFrete = $valorFrete;
        $this->desconto = $desconto;
        $this->formaPagamento = $formaPagamento;
        $this->enderecoId = $enderecoId;
        $this->clienteId = $clienteId;
        $this->dataPedido = new DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getValorTotal(): float {
        return $this->valorTotal;
    }
    public function setValorTotal(float $valorTotal) {
        $this->valorTotal = $valorTotal;
        return $this;
    }

    public function getValorFrete(): float {
        return $this->valorFrete;
    }
    public function setValorFrete(float $valorFrete){
        $this->valorFrete = $valorFrete;
        return $this;
    }

    public function getDesconto(): float {
        return $this->desconto;
    }
    public function setDesconto(float $desconto){
        $this->desconto = $desconto;
        return $this;
    }

    public function getFormaPagamento(): FormaDePagamento {
        return $this->formaPagamento;
    }
    public function setFormaPagamento(FormaDePagamento $formaPagamento) {
        $this->formaPagamento = $formaPagamento;
        return $this;
    }

    public function getDataPedido(): DateTime {
        return $this->dataPedido;
    }
    public function setDataPedido(DateTime $dataPedido) {
        $this->dataPedido = $dataPedido;
        return $this;
    }

    public function getEnderecoId(){
        return $this->enderecoId;
    }
    public function setEndereco($enderecoId) {
        $this->enderecoId = $enderecoId;
        return $this;
    }

    public function getClienteId(){
        return $this->clienteId;
    }
    public function setCliente($clienteId) {
        $this->clienteId = $clienteId;
        return $this;
    }

    public function getItens(): array {
        return $this->itens;
    }
    public function setItens(array $itens) {
        $this->itens = $itens;
        return $this;
    }

    public function adicionarItem(ItensPedido $item): void {
        $this->itens[] = $item;
    }
}
?>