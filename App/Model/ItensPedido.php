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
    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    public function getPrecoVenda(): float {
        return $this->precoVenda;
    }
    public function setPrecoVenda(float $precoVenda){
        $this->precoVenda = $precoVenda;
        return $this;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }
    public function setQuantidade(int $quantidade){
        $this->quantidade = $quantidade;
        return $this;
    }

    public function getPedidoId(){
        return $this->pedidoId;
    }
    public function setPedidoId($pedidoId){
        $this->pedidoId = $pedidoId;
        return $this;
    }

    public function getVariacaoId(){
        return $this->variacaoId;
    }
    public function setVariacaoId($variacaoId){
        $this->variacaoId = $variacaoId;
        return $this;
    }

    public function getSubtotal(): float {
        return $this->precoVenda * $this->quantidade;
    }
}
?>