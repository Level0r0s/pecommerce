<?php
/**
 * CategoriaForm Registration
 * @author  Alexandre E. Souza
 */
class CategoriaForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        Usuario::checkLogin();
        
        // creates the form
        $this->form = new TQuickForm('form_Categoria');
        $this->form->class = 'tform'; // CSS class
        
        // define the form title
        $this->form->setFormTitle('Categoria');
        
        // defines the database
        parent::setDatabase('sample');
        
        // defines the active record
        parent::setActiveRecord('Categoria');
        


        // create the form fields
        $id                             = new THidden('id');
        $nome                           = new TEntry('nome');
        $descricao                      = new THtmlEditor('descricao');


        // add the fields
        $this->form->addQuickField('', $id,  100);
        $this->form->addQuickField('nome', $nome,  200);
        $this->form->addQuickField('descricao', $descricao,  200);


        $descricao->setSize(400, 300);


        // add a form action
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');

        // add a form action
        $this->form->addQuickAction(_t('New'), new TAction(array($this, 'onEdit')), 'ico_new.png');

        
        // add the form to the page
        parent::add($this->form);
    }
}
?>