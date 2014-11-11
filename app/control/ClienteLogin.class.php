<?php


class ClienteLogin extends TWindow{

private $form;

public function __construct(){

TSession::freeSession(); // impa todas as sessoes


parent::__construct();

PCart::clean();

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

Clientes::logar($data->user,$data->senha);

}

public function logout(){
TSession::freeSession();
TCoreApplication::executeMethod('Home');

}
}
?>