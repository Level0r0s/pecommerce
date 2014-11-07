<?php
/**
 * UsuarioForm Registration
 * @author  Alexandre E. SOuza
 */
class UsuarioForm extends TPage
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
        $this->form = new TForm('form_Usuario');
        $this->form->class = 'tform'; // CSS class
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Usuario') )->colspan = 2;
        


        // create the form fields
        $id                             = new TEntry('id');
        $login                          = new TEntry('login');
        $senha                          = new TPassword('senha');


        // define the sizes
        $id->setSize(100);
        $login->setSize(200);
        $senha->setSize(200);



        // add one row for each form field
        $table->addRowSet( new TLabel('id:'), $id );
        $table->addRowSet( new TLabel('login:'), $login );
        $table->addRowSet( new TLabel('senha:'), $senha );

        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('ico_save.png');

 
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('New'));
        $new_button->setImage('ico_new.png');

        $this->form->setFields(array($id,$login,$senha,$save_button,$new_button));

        
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
            
            // get the form data into an active record Usuario
            $object = $this->form->getData('Usuario');
            
            $this->form->validate(); // form validation
            $object->senha = md5($object->senha);
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
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'sample'
                TTransaction::open('sample');
                
                // instantiates object Usuario
                $object = new Usuario($key);
                
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
}
?>