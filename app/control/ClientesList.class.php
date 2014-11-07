<?php
/**
 * ClientesList Listing
 * @author  Alexandre E. Souza
 */
class ClientesList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
         Usuario::checkLogin();
        parent::setDatabase('sample');            // defines the database
        parent::setActiveRecord('Clientes');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::setFilterField('nome');           // defines the filter field
        
        // creates the form, with a table inside
        $this->form = new TForm('form_search_Clientes');
        $table = new TTable;
        $this->form->add($table);
        
        // create the form fields
        $filter = new TEntry('nome');
        $filter->setValue(TSession::getValue('Clientes_nome'));
        
        // add a row for the filter field
        $table->addRowSet( new TLabel('nome:'), $filter);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
        $new_button  = new TButton('new');
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
        $new_button->setAction(new TAction(array('ClientesForm', 'onEdit')), _t('New'));
        $find_button->setImage('ico_find.png');
        $new_button->setImage('ico_new.png');
        
        // add a row for the form actions
        $table->addRowSet($find_button, $new_button);
        
        // define wich are the form fields
        $this->form->setFields(array($filter, $find_button, $new_button));
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $nome = $this->datagrid->addQuickColumn('nome', 'nome', 'left', 200);
        $sobrenome = $this->datagrid->addQuickColumn('sobrenome', 'sobrenome', 'left', 200);
        $cep = $this->datagrid->addQuickColumn('cep', 'cep', 'left', 200);
        $logradouro = $this->datagrid->addQuickColumn('logradouro', 'logradouro', 'left', 200);
        $bairro = $this->datagrid->addQuickColumn('bairro', 'bairro', 'left', 200);
        $cidade = $this->datagrid->addQuickColumn('cidade', 'cidade', 'left', 200);
        $email = $this->datagrid->addQuickColumn('email', 'email', 'left', 200);
        $dd = $this->datagrid->addQuickColumn('dd', 'dd', 'left', 200);
        $telefone = $this->datagrid->addQuickColumn('telefone', 'telefone', 'left', 200);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('ClientesForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), $edit_action, 'id', 'ico_edit.png');
        $this->datagrid->addQuickAction(_t('Delete'), $delete_action, 'id', 'ico_delete.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = new TVBox;
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        parent::add($container);
    }
}
?>