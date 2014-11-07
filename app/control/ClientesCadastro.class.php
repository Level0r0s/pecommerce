<?php
/**
 * ClientesCadastro Registration
 * @author  Alexandre E. Souza
 */
class ClientesCadastro extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('form_Clientes');
        $this->form->class = 'tform'; // CSS class
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Clientes') )->colspan = 2;
        


        // create the form fields
        $id                             = new TEntry('id');
        $nome                           = new TEntry('nome');
        $sobrenome                      = new TEntry('sobrenome');
        $cep                            = new TEntry('cep');
        $logradouro                     = new TEntry('logradouro');
        $bairro                         = new TEntry('bairro');
        $cidade                         = new TEntry('cidade');
        $email                          = new TEntry('email');
        $dd                             = new TEntry('dd');
        $telefone                       = new TEntry('telefone');
        $senha                          = new TPassword('senha');

     $buscaCep = new TAction(array($this, 'onSearch'));
        $cep->setExitAction($buscaCep);
        
        // mascaras
        
        
     
        $cep->setMask('99999-999'); 
        

        // define the sizes
        $id->setSize(100);
        $nome->setSize(200);
        $sobrenome->setSize(200);
        $cep->setSize(200);
        $logradouro->setSize(200);
        $bairro->setSize(200);
        $cidade->setSize(200);
        $email->setSize(200);
        $dd->setSize(200);
        $telefone->setSize(200);
        $senha->setSize(200);



        // add one row for each form field
        $table->addRowSet( new TLabel('id:'), $id );
        $table->addRowSet( new TLabel('nome:'), $nome );
        $table->addRowSet( new TLabel('sobrenome:'), $sobrenome );
        $table->addRowSet( new TLabel('cep:'), $cep );
        $table->addRowSet( new TLabel('logradouro:'), $logradouro );
        $table->addRowSet( new TLabel('bairro:'), $bairro );
        $table->addRowSet( new TLabel('cidade:'), $cidade );
        $table->addRowSet( new TLabel('email:'), $email );
        $table->addRowSet( new TLabel('dd:'), $dd );
        $table->addRowSet( new TLabel('telefone:'), $telefone );
        $table->addRowSet( new TLabel('senha:'), $senha );

        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('ico_save.png');

 
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('New'));
        $new_button->setImage('ico_new.png');

        $this->form->setFields(array($id,$nome,$sobrenome,$cep,$logradouro,$bairro,$cidade,$email,$dd,$telefone,$senha,$save_button,$new_button));

        
        $buttons_box = new THBox;
        $buttons_box->add($save_button);
        $buttons_box->add($new_button);
        
        // add a row for the form action
        $row = $table->addRow();
        $row->class = 'tformaction'; // CSS class
        $row->addCell($buttons_box)->colspan = 2;
        
        parent::add($this->form);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'sample'
            TTransaction::open('sample');
            
            // get the form data into an active record Clientes
            $object = $this->form->getData('Clientes');
            
            $this->form->validate(); // form validation
            $object->store(); // stores the object
            $this->form->setData($object); // keep form data
            
            TTransaction::close(); // close the transaction
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            $this->form->setData( $this->form->getData() ); // keep form data
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit()
    {
        try
        {
            if (TSession::getValue('cliente'))
            {
                // get the parameter $key
                $key = TSession::getValue('cliente');
                
               foreach($key as $c):
               $id = $c->id;
               endforeach;
               
                // open a transaction with database 'sample'
                TTransaction::open('sample');
                
                // instantiates object Clientes
                $object = new Clientes($id);
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    public static function onSearch($param){
    
      $obj = new StdClass;
       $data  = new PCepProgs($param['cep']);
        
        $obj->logradouro        = $data->getRua();
        $obj->cidade     = $data->getCidade();
        $obj->bairro     = $data->getBairro();;
        $obj->uf         = $data->getUf();; 
        TForm::sendData('form_Clientes', $obj);
        }  
        
}
?>