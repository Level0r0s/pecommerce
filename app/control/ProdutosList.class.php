<?php
/**
 * ProdutosList Listing
 * @author  <your name here>
 */
class ProdutosList extends TPage
{
    private $form;     // registration form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
         Usuario::checkLogin();
        // creates the form
        $this->form = new TForm('form_search_Produtos');
        
        // creates a table
        $table = new TTable;
        
        // add the table inside the form
        $this->form->add($table);
        
        // create the form fields
        $filter = new TEntry('nome');
        $filter->setValue(TSession::getValue('Produtos_nome'));
        
        // add a row for the filter field
        $row=$table->addRowSet( new TLabel('nome:'), $filter);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
        $new_button  = new TButton('new');
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
        $new_button->setAction(new TAction(array('ProdutosForm', 'onEdit')), _t('New'));
        $find_button->setImage('ico_find.png');
        $new_button->setImage('ico_new.png');
        
        // add a row for the form actions
        $row=$table->addRowSet( $find_button, $new_button );
        
        // define wich are the form fields
        $this->form->setFields(array($filter, $find_button, $new_button));
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id   = new TDataGridColumn('id', 'id', 'right', 100);
        $nome   = new TDataGridColumn('nome', 'nome', 'left', 200);
        $descricao   = new TDataGridColumn('descricao', 'descricao', 'left', 200);
        $preco   = new TDataGridColumn('preco', 'preco', 'left', 200);
        $imagem   = new TDataGridColumn('imagem', 'imagem', 'left', 200);


        // add the columns to the DataGrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($preco);
        $this->datagrid->addColumn($imagem);

        
        // creates two datagrid actions
        $action1 = new TDataGridAction(array('ProdutosForm', 'onEdit'));
        $action1->setLabel(_t('Edit'));
        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel(_t('Delete'));
        $action2->setImage('ico_delete.png');
        $action2->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
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
    
   
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        TSession::setValue('Produtos_filter',   NULL);
        TSession::setValue('Produtos_nome', '');
        
        // check if the user has filled the form
        if (isset($data->nome) AND ($data->nome))
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('nome', 'like', "%{$data->nome}%");
            
            // stores the filter in the session
            TSession::setValue('Produtos_filter',   $filter);
            TSession::setValue('Produtos_nome', $data->nome);
        }
        else
        {
            TSession::setValue('Produtos_filter',   NULL);
            TSession::setValue('Produtos_nome', '');
        }
        
        // fill the form with data again
        $this->form->setData($data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'sample'
            TTransaction::open('sample');
            
            // creates a repository for Produtos
            $repository = new TRepository('Produtos');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Produtos_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('Produtos_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                $img = new TImage('uploads/'.$object->imagem);
                 $img->width = '100px';
                  $img->heigth = '120px';
                  
                $object->imagem = $img;
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            // get the parameter $key
            $key=$param['key'];
            // open a transaction with database 'sample'
            TTransaction::open('sample');
            
            // instantiates object Produtos
            $object = new Produtos($key);
            
            // deletes the object from the database
            $object->delete();
            
            // close the transaction
            TTransaction::close();
            
            // reload the listing
            $this->onReload( $param );
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted'));
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
?>