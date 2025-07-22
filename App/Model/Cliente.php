<?php
namespace App\Model;

use DateTime;
use InvalidArgumentException;
class Cliente {
    private ?int $id = null;
    private string $nomeCompleto;
    private string $cpf;
    private DateTime $dataNascimento;

    public function __construct(string $nomeCompleto, string $cpf, DateTime $dataNascimento) {
        if (!self::validarCPF($cpf)) {
            throw new InvalidArgumentException("CPF inválido");
        }

        $this->nomeCompleto = $nomeCompleto;
        $this->cpf = $cpf;
        $this->dataNascimento = $dataNascimento;
    }

    private static function validarCPF(string $cpf): bool {
        return strlen($cpf) === 11;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getNomeCompleto(): string {
        return $this->nomeCompleto;
    }

    public function setNomeCompleto(string $nomeCompleto){
        $this->nomeCompleto = $nomeCompleto;
        return $this;
    }

    public function getCpf(): string {
        return $this->cpf;
    }

    public function setCpf(string $cpf){
        $this->cpf = $cpf;
        return $this;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento){
        $this->dataNascimento = $dataNascimento;
        return $this;
    }
}
?>