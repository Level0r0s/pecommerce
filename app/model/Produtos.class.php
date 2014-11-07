<?php
/**
 * Produtos Active Record
 * @author  Alexandre E. Souza
 */
class Produtos extends TRecord
{
    const TABLENAME = 'produtos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $categoria;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('preco');
        parent::addAttribute('imagem');
        parent::addAttribute('categoria_id');
    }

    
    /**
     * Method set_categoria
     * Sample of usage: $produtos->categoria = $object;
     * @param $object Instance of Categoria
     */
    public function set_categoria(Categoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }
    
    /**
     * Method get_categoria
     * Sample of usage: $produtos->categoria->attribute;
     * @returns Categoria instance
     */
    public function get_categoria()
    {
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new Categoria($this->categoria_id);
    
        // returns the associated object
        return $this->categoria;
    }
    


}
?>