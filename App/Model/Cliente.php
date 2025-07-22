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

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNomeCompleto(): string {
        return $this->nomeCompleto;
    }

    public function setNomeCompleto(string $nomeCompleto): void {
        $this->nomeCompleto = $nomeCompleto;
    }

    public function getCpf(): string {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void {
        $this->cpf = $cpf;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento): void {
        $this->dataNascimento = $dataNascimento;
    }
}
?>