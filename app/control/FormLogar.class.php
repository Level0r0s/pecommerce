<?php


class FormLogar extends TWindow{

private $form;

public function __construct(){

TSession::freeSession();


parent::__construct();
$this->setTitle("Login");
$this->setSize(300,200);

$this->form = new TQuickForm('login');

$login =  new TEntry('user');
$senha = new TPassword('senha');

$this->form->addQuickField('Login',$login,200,new TRequiredValidator);
$this->form->addQuickField('Senha',$senha,200,new TRequiredValidator);

$this->form->addQuickAction('Logar',new TAction(array($this,'logar')));

parent::add($this->form);
  }

public function logar(){

$data = $this->form->getData();

Usuario::logar($data->user,md5($data->senha));

}



}

?>