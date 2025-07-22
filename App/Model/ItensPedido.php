<?php
namespace App\Model;

class ItensPedido {
    private ?int $id = null;
    private float $precoVenda;
    private int $quantidade;
    private int $pedidoId;
    private int $variacaoId;

    public function __construct(float $precoVenda, int $quantidade, int $pedidoId, int $variacaoId) {
        $this->precoVenda = $precoVenda;
        $this->quantidade = $quantidade;
        $this->pedidoId = $pedidoId;
        $this->variacaoId = $variacaoId;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getPrecoVenda(): float {
        return $this->precoVenda;
    }
    public function setPrecoVenda(float $precoVenda): void {
        $this->precoVenda = $precoVenda;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }
    public function setQuantidade(int $quantidade): void {
        $this->quantidade = $quantidade;
    }

    public function getPedidoId(){
        return $this->pedidoId;
    }
    public function setPedido($pedidoId): void {
        $this->pedidoId = $pedidoId;
    }

    public function getVariacaoId(){
        return $this->variacaoId;
    }
    public function setVariacaoId($variacaoId): void {
        $this->variacaoId = $variacaoId;
    }

    public function getSubtotal(): float {
        return $this->precoVenda * $this->quantidade;
    }
}
?>