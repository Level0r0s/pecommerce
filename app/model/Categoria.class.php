<?php
/**
 * Categoria Active Record
 * @author  Alexandre E. Souza
 */
class Categoria extends TRecord
{
    const TABLENAME = 'categoria';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
    }

    
    /**
     * Method getProdutoss
     */
    public function getProdutoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('categoria_id', '=', $this->id));
        return Produtos::getObjects( $criteria );
    }
    


}
?>