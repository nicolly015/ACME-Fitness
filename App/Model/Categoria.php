<?php
namespace App\Model;

class Categoria {
    private ?int $id = null;
    private string $nome;
    private ?string $descricao = null;

    public function __construct(string $nome, ?string $descricao = null) {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome){
        $this->nome = $nome;
        return $this;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao) {
        $this->descricao = $descricao;
        return $this;
    }
}
?>