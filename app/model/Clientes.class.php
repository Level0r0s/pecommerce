<?php
/**
 * Clientes Active Record
 * @author  Alexandre E. Souza
 */
class Clientes extends TRecord
{
    const TABLENAME = 'clientes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sobrenome');
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('uf');
        parent::addAttribute('email');
        parent::addAttribute('dd');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('senha');
    }

    
    /**
     * Method getPedidoss
     */
    public function getPedidoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('clientes_id', '=', $this->id));
        return Pedidos::getObjects( $criteria );
    }
    

public static function logar($email,$senha){

try{
TTransaction::open('sample');

$criteria = new TCriteria();
$filter  = new TFilter('email','=',$email);
$filter2  = new TFilter('senha','=',$senha);

$criteria->add($filter);
$criteria->add($filter2);


$user = Clientes::getObjects($criteria);

if($user){
TSession::setValue('cliente_logado',true); // cria a sessão para mostrar que o usuario esta no sistema
TSession::setValue('cliente',$user); // guarda os dados do cliente
foreach($user as $c):
TSession::setValue('id',$c->id); // guarda os dados do cliente
endforeach;

TCoreApplication::executeMethod('Home');
}
else{
new TMessage('error','Usuario ou Senha invalidos');

}

TTransaction::close();

}catch(Exception $e){

echo $e->getMessage();
}
}

public static function checkCliente(){

if(!TSession::getValue('cliente_logado')){

new TMessage('info','Você não esta logado');

TCoreApplication::executeMethod('ClientesLogin');

  }
 }
}
?>