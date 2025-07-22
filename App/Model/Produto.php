<?php
namespace App\Model;

use DateTime;
class Produto {
    private ?int $id = null;
    private string $nome;
    private ?string $cor = null;
    private ?string $imagem = null;
    private float $preco;
    private ?string $descricao = null;
    private DateTime $dataCadastro;
    private ?float $peso = null;
    private int $categoriaId;

    public function __construct(string $nome, ?string $cor, ?string $imagem, float $preco, ?string $descricao, DateTime $dataCadastro, ?float $peso, int $categoriaId) {
        $this->nome = $nome;
        $this->cor = $cor;
        $this->imagem = $imagem;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->dataCadastro = $dataCadastro;
        $this->peso = $peso;
        $this->categoriaId = $categoriaId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor)
    {
        $this->cor = $cor;
        return $this;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;
        return $this;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
        return $this;
    }

    public function getCategoriaId()
    {
        return $this->categoriaId;
    }

    public function setCategoria($categoriaId)
    {
        $this->categoriaId = $categoriaId;
        return $this;
    }
}
?>