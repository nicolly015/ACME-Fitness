<?php
namespace App\Model;

class Variacoes {
    private ?int $id = null;
    private string $tamanho;
    private int $estoque;
    private int $produtoId;

    public function __construct(string $tamanho, int $estoque, int $produtoId) {
        $this->tamanho = $tamanho;
        $this->estoque = $estoque;
        $this->produtoId = $produtoId;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTamanho(): string {
        return $this->tamanho;
    }
    public function setTamanho(string $tamanho): void {
        $this->tamanho = $tamanho;
    }

    public function getEstoque(): int {
        return $this->estoque;
    }
    public function setEstoque(int $estoque): void {
        $this->estoque = $estoque;
    }

    public function getProdutoId(){
        return $this->produtoId;
    }
    public function setProdutoId($produtoId): void {
        $this->produtoId = $produtoId;
    }

    public function baixarEstoque(int $quantidade): void {
        if ($quantidade > $this->estoque) {
            throw new \Exception("Estoque insuficiente");
        }
        $this->estoque -= $quantidade;
    }

    public function reporEstoque(int $quantidade): void {
        $this->estoque += $quantidade;
    }

    public function estaDisponivel(): bool {
        return $this->estoque > 0;
    }
}
?>
