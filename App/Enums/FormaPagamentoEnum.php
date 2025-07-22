<?php
namespace Enums;

enum FormaDePagamento: string {
    case PIX = 'PIX';
    case BOLETO = 'Boleto';
    case CARTAO = 'Cartão';
}
?>