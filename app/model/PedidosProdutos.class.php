<?php
/**
 * PedidosProdutos Active Record
 * @author  Alexandre E. Souza
 */
class PedidosProdutos extends TRecord
{
    const TABLENAME = 'produtos_pedidos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    protected $produto; // pega o produto 
   protected $pedido; // pega o pedido
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pedidos_id');
        parent::addAttribute('produtos_id');
        parent::addAttribute('qtd');
    }

// metodo usado para pegar produto de um pedido
public function get_produto(){

$this->produto = new Produtos($this->produtos_id);

return $this->produto;
}

// metodo usado para pegar um pedido
public function get_pedido(){

$this->pedido = new Pedidos($this->pedidos_id);

return $this->pedido;
}

}
?>