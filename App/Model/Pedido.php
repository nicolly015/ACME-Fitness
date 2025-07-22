<?php
namespace App\Model;

use DateTime;
use Enums\FormaDePagamento;

class Pedido {
    private ?int $id = null;
    private float $valorTotal;
    private float $valorFrete = 10.00;
    private float $desconto = 0;
    private FormaDePagamento $formaPagamento;
    private DateTime $dataPedido;
    private int $enderecoId;
    private int $clienteId;
    private array $itens = [];

    public function __construct(float $valorTotal, float $valorFrete, float $desconto, FormaDePagamento $formaPagamento, int $enderecoId, int $clienteId) {
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
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getValorTotal(): float {
        return $this->valorTotal;
    }
    public function setValorTotal(float $valorTotal): void {
        $this->valorTotal = $valorTotal;
    }

    public function getValorFrete(): float {
        return $this->valorFrete;
    }
    public function setValorFrete(float $valorFrete): void {
        $this->valorFrete = $valorFrete;
    }

    public function getDesconto(): float {
        return $this->desconto;
    }
    public function setDesconto(float $desconto): void {
        $this->desconto = $desconto;
    }

    public function getFormaPagamento(): FormaDePagamento {
        return $this->formaPagamento;
    }
    public function setFormaPagamento(FormaDePagamento $formaPagamento): void {
        $this->formaPagamento = $formaPagamento;
    }

    public function getDataPedido(): DateTime {
        return $this->dataPedido;
    }
    public function setDataPedido(DateTime $dataPedido): void {
        $this->dataPedido = $dataPedido;
    }

    public function getEnderecoId(){
        return $this->enderecoId;
    }
    public function setEndereco($enderecoId): void {
        $this->enderecoId = $enderecoId;
    }

    public function getClienteId(){
        return $this->clienteId;
    }
    public function setCliente($clienteId): void {
        $this->clienteId = $clienteId;
    }

    public function getItens(): array {
        return $this->itens;
    }
    public function setItens(array $itens): void {
        $this->itens = $itens;
    }

    public function adicionarItem(ItensPedido $item): void {
        $this->itens[] = $item;
    }
}
?>