<?php
    namespace Models;

    use Models\Pedido;
    use Models\Variacoes;

    class ItensPedido{
        private int $id;
        private float $precoVenda;
        private int $quantidade;
        private Pedido $pedido;
        private Variacoes $variacoes;

        public function __construct($precoVenda, $quantidade, Pedido $pedido, Variacoes $variacoes)
        {
            $this->precoVenda = $precoVenda;
            $this->quantidade = $quantidade;
            $this->pedido = $pedido;
            $this->variacoes = $variacoes;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getPrecoVenda()
        {
            return $this->precoVenda;
        }

        public function setPrecoVenda($precoVenda)
        {
            $this->precoVenda = $precoVenda;
        }

        public function getQuantidade()
        {
            return $this->quantidade;
        }

        public function setQuantidade($quantidade)
        {
            $this->quantidade = $quantidade;
        }

        public function getPedido()
        {
            return $this->pedido;
        }

        public function setPedido($pedido)
        {
            $this->pedido = $pedido;
        }

        public function getVariacoes()
        {
            return $this->variacoes;
        }

        public function setVariacoes($variacoes)
        {
            $this->variacoes = $variacoes;
        }

        public function getSubtotal(): float {
            return $this->precoVenda * $this->quantidade;
        }


    }
?>