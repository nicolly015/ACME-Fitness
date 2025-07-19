<?php
    namespace Models;


    class Cliente {
        private int $id;
        private string $nomeCompleto;
        private string $cpf;
        private string $dataNascimento;

        public function __construct($nomeCompleto, $cpf, $dataNascimento)
        {
            $this->nomeCompleto = $nomeCompleto;
            $this->cpf = $cpf;
            $this->dataNascimento = $dataNascimento;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getNomeCompleto()
        {
            return $this->nomeCompleto;
        }

        public function setNomeCompleto($nomeCompleto)
        {
            $this->nomeCompleto = $nomeCompleto;
        }

        public function getCpf()
        {
            return $this->cpf;
        }

        public function setCpf($cpf)
        {
            $this->cpf = $cpf;
        }

        public function getDataNascimento()
        {
            return $this->dataNascimento;
        }
                public function setDataNascimento($dataNascimento)
        {
            $this->dataNascimento = $dataNascimento;
        }


    }
?>