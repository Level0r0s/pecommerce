<?php
/**
 * Pedidos Active Record
 * @author  Alexandre E. Souza
 */
class Pedidos extends TRecord
{
    const TABLENAME = 'pedidos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $clientes;
    private $produtoss;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dataP');
        parent::addAttribute('status');
        parent::addAttribute('clientes_id');
    }

    
    /**
     * Method set_clientes
     * Sample of usage: $pedidos->clientes = $object;
     * @param $object Instance of Clientes
     */
    public function set_clientes(Clientes $object)
    {
        $this->clientes = $object;
        $this->clientes_id = $object->id;
    }
    
    /**
     * Method get_clientes
     * Sample of usage: $pedidos->clientes->attribute;
     * @returns Clientes instance
     */
    public function get_clientes()
    {
        // loads the associated object
        if (empty($this->clientes))
            $this->clientes = new Clientes($this->clientes_id);
    
        // returns the associated object
        return $this->clientes;
    }
    
    
    /**
     * Method addProdutos
     * Add a Produtos to the Pedidos
     * @param $object Instance of Produtos
     */
    public function addProdutos(Produtos $object)
    {
        $this->produtoss[] = $object;
    }
    
    /**
     * Method getProdutoss
     * Return the Pedidos' Produtos's
     * @return Collection of Produtos
     */
    public function getProdutoss()
    {
        return $this->produtoss;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->produtoss = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->produtoss = parent::loadAggregate('Produtos', 'PedidosProdutos', 'pedidos_id', 'produtos_id', $id);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        parent::saveAggregate('PedidosProdutos', 'pedidos_id', 'produtos_id', $this->id, $this->produtoss);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('PedidosProdutos', 'pedidos_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }


}
?>