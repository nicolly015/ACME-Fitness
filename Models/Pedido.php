<?php
namespace Models;

use DateTime;
use Models\Endereco;
use Models\Cliente;
use Models\ItensPedido;

class Pedido {
    private int $id;
    private float $valorTotal;
    private float $valorFrete;
    private float $desconto;
    private string $formaPagamento;
    private DateTime $dataPedido;
    private Endereco $endereco;
    private Cliente $cliente;
    private array $itens = [];

    public function __construct($valorTotal, $valorFrete, $desconto, $formaPagamento, Endereco $endereco, Cliente $cliente) {
        $this->valorTotal = $valorTotal;
        $this->valorFrete = $valorFrete;
        $this->desconto = $desconto;
        $this->formaPagamento = $formaPagamento;
        $this->endereco = $endereco;
        $this->cliente = $cliente;
        $this->dataPedido = new DateTime();
    }

    public function getId() {
        return $this->id;
    }

    public function getValorTotal() {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal) {
        $this->valorTotal = $valorTotal;
    }

    public function getValorFrete() {
        return $this->valorFrete;
    }

    public function setValorFrete($valorFrete) {
        $this->valorFrete = $valorFrete;
    }

    public function getDesconto() {
        return $this->desconto;
    }

    public function setDesconto($desconto) {
        $this->desconto = $desconto;
    }

    public function getFormaPagamento() {
        return $this->formaPagamento;
    }

    public function setFormaPagamento($formaPagamento) {
        $this->formaPagamento = $formaPagamento;
    }

    public function getDataPedido() {
        return $this->dataPedido;
    }

    public function setDataPedido(DateTime $dataPedido) {
        $this->dataPedido = $dataPedido;
    }

    public function getEndereco(): Endereco {
        return $this->endereco;
    }

    public function setEndereco(Endereco $endereco) {
        $this->endereco = $endereco;
    }

    public function getCliente(): Cliente {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function getItens(): array {
        return $this->itens;
    }

    public function setItens(array $itens) {
        $this->itens = $itens;
    }

    public function adicionarItem(ItensPedido $itens){
        $this->itens[] = $itens;
    }
}

?>