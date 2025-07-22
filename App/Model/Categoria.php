<?php
namespace App\Model;

class Categoria {
    private ?int $id = null;
    private string $nome;
    private ?string $descricao;

    public function __construct(string $nome, ?string $descricao = null) {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void {
        $this->descricao = $descricao;
    }
}
?>