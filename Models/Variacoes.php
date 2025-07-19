<?php
namespace Models;

use Models\Produto;

class Variacoes{
    private int $id;
    private string $tamanho;
    private int $estoque;
    private Produto $produto;

    public function __construct($tamanho, $estoque, Produto $produto) {
        $this->tamanho = $tamanho;
        $this->estoque = $estoque;
        $this->produto = $produto;
    }

    public function getId() {
        return $this->id;
    }

    public function getTamanho() {
        return $this->tamanho;
    }

    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }

    public function getEstoque() {
        return $this->estoque;
    }

    public function setEstoque($estoque) {
        $this->estoque = $estoque;
    }

    public function getProduto(): Produto {
        return $this->produto;
    }

    public function setProduto(Produto $produto) {
        $this->produto = $produto;
    }

    public function baixarEstoque($quantidade) {
        if ($quantidade > $this->estoque) {
            throw new \Exception("Estoque insuficiente");
        }
        $this->estoque -= $quantidade;
    }

    public function reporEstoque($quantidade) {
        $this->estoque += $quantidade;
    }

    public function estaDisponivel() {
        return $this->estoque > 0;
    }
}