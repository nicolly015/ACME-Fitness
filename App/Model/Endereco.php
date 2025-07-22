<?php
namespace App\Model;

class Endereco {
    private ?int $id = null;
    private string $logradouro;
    private string $cidade;
    private string $bairro;
    private string $numero;
    private string $cep;
    private string $complemento;
    private int $clienteId;

    public function __construct(string $logradouro, string $cidade, string $bairro, string $numero, string $cep, string $complemento, int $clienteId) {
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

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getLogradouro()
    {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function setCliente($clienteId)
    {
        $this->clienteId = $clienteId;
    }
}
?>