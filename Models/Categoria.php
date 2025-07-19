<?php
namespace Models;

class Categoria{
    private int $id;
    private string $nome;
    private ?string $descricao;

    public function __construct($nome, $descricao){
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
?>