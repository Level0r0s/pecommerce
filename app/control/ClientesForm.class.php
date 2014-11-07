<?php
/**
 * ClientesForm Registration
 * @author  Alexandre E. Souza
 */
class ClientesForm extends TStandardForm
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
        $this->form = new TQuickForm('form_Clientes');
        $this->form->class = 'tform'; // CSS class
        
        // define the form title
        $this->form->setFormTitle('Clientes');
        
        // defines the database
        parent::setDatabase('sample');
        
        // defines the active record
        parent::setActiveRecord('Clientes');
        


        // create the form fields
        $id                             = new THidden('id');
        $nome                           = new TEntry('nome');
        $sobrenome                      = new TEntry('sobrenome');
        $cep                            = new TEntry('cep');
        $logradouro                     = new TEntry('logradouro');
        $bairro                         = new TEntry('bairro');
        $cidade                         = new TEntry('cidade');
        $email                          = new TEntry('email');
        $dd                             = new TEntry('dd');
        $telefone                       = new TEntry('telefone');
        
        // mascaras nos campos usa-se o 9 para numero e o # para letra
        $telefone->setMask('9999-9999');
        $dd->setMask('99');
        $cep->setMask('99999-999');
        
        // valida email
        $email->addValidation('E-mail',new TEmailValidator);
        
//  new TRequiredValidator validador que faz com que o campo seja obrigatorio
        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('nome', $nome,  200, new TRequiredValidator );
        $this->form->addQuickField('sobrenome', $sobrenome,  200, new TRequiredValidator );
        $this->form->addQuickField('cep', $cep,  200, new TRequiredValidator );
        $this->form->addQuickField('logradouro', $logradouro,  200, new TRequiredValidator );
        $this->form->addQuickField('bairro', $bairro,  200, new TRequiredValidator );
        $this->form->addQuickField('cidade', $cidade,  200, new TRequiredValidator );
        $this->form->addQuickField('email', $email,  200, new TRequiredValidator );
        $this->form->addQuickField('dd', $dd,  200, new TRequiredValidator );
        $this->form->addQuickField('telefone', $telefone,  200, new TRequiredValidator );




        // add a form action
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');

        // add a form action
        $this->form->addQuickAction(_t('New'), new TAction(array($this, 'onEdit')), 'ico_new.png');

        
        // add the form to the page
        parent::add($this->form);
    }
}
?>