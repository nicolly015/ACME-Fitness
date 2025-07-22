<?php
namespace App\Model;

class Endereco {
    private ?int $id = null;
    private ?string $logradouro = null;
    private string $cidade;
    private ?string $bairro = null;
    private ?string $numero = null;
    private ?string $cep = null;
    private ?string $complemento = null;
    private int $clienteId;

    public function __construct(?string $logradouro, string $cidade, ?string $bairro, ?string $numero, ?string $cep, ?string $complemento, int $clienteId) {
        $this->logradouro = $logradouro;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->numero = $numero;
        $this->cep = $cep;
        $this->complemento = $complemento;
        $this->clienteId = $clienteId;
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getLogradouro()
    {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
        return $this;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function setCliente($clienteId)
    {
        $this->clienteId = $clienteId;
        return $this;
    }
}
?>