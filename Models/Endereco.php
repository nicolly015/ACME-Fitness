<?php
    namespace Models;

    use Models\Cliente;

    class Endereco{
        private int $id;
        private string $logradouro;
        private string $cidade;
        private string $bairro;
        private string $numero;
        private string $cep;
        private string $complemento;
        private Cliente $cliente;

        public function __construct($logradouro, $cidade, $bairro, $numero, $cep, $complemento, Cliente $cliente)
        {
            $this->logradouro = $logradouro;
            $this->cidade = $cidade;
            $this->bairro = $bairro;
            $this->numero = $numero;
            $this->cep = $cep;
            $this->complemento = $complemento;
            $this->cliente = $cliente;
        }

        public function getId()
        {
            return $this->id;
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

        public function getCliente()
        {
            return $this->cliente;
        }

        public function setCliente($cliente)
        {
            $this->cliente = $cliente;
        }
    }
?>