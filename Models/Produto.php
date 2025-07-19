<?php
namespace Models;

use Models\Categoria;
class Produto{
    private int $id;
    private string $nome;
    private string $cor;
    private string $imagem;
    private float $preco;
    private string $descricao;
    private string $dataCadastro;
    private float $peso;
    private Categoria $categoria;

    public function __construct($nome, $cor, $imagem, $preco, $descricao, $dataCadastro, $peso, Categoria $categoria){
        $this->nome = $nome;
        $this->cor = $cor;
        $this->imagem = $imagem;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->dataCadastro = $dataCadastro;
        $this->peso = $peso;
        $this->categoria = $categoria;
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

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor)
    {
        $this->cor = $cor;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }
}

?>