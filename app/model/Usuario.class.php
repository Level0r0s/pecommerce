<?php
/**
 * Usuario Active Record
 * @author  Alexandre
 */
class Usuario extends TRecord
{
    const TABLENAME = 'usuario';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('login');
        parent::addAttribute('senha');
    }


public static function  logar($user,$senha){

try{
TTransaction::open('sample');

$criteria = new TCriteria();
$filter  = new TFilter('login','=',$user);
$filter2  = new TFilter('senha','=',$senha);

$criteria->add($filter);
$criteria->add($filter2);


$user = Usuario::getObjects($criteria);

if($user){
TSession::setValue('logado',true); // cria a sessão para mostrar que o usuario esta no sistema
TCoreApplication::executeMethod('CategoriaList');
}
else{
new TMessage('error','Usuario ou Senha invalidos');

}

TTransaction::close();

}catch(Exception $e){

echo $e->getMessage();
}
 }
 
 public static function checkLogin(){
 
 if(!TSession::getValue('logado')){
 
 new TMessage('error','Você não esta logado');
 TCoreApplication::executeMethod('FormLogar');
 }
  }
  
  public static function  logout(){
  
  TSession::freeSession();
   TCoreApplication::executeMethod('FormLogar');
   }
 }
   
?>