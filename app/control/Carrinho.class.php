<?php


class Carrinho extends TPage{

private $form;
private $table;
private $cart;
private $janela;

public function __construct(){

parent::__construct();

$this->cart = new PCart(); // intancia a class PCart da PWD

$this->form = new TForm();  // cria um form

$this->table = new TTable(); // cria a table 
  
        $this->form->add($this->table); //adciona a table no form
        
        parent::add($this->form); // adciona o form na pagina
}

// metodo onde mostramos o detalhe do item 
// para o usuario inserir a quantidade

public function detalhes($param){


try{
TTransaction::open('sample');

$produtos = new Produtos($param['key']);



$row = $this->table->addRow();
$row->addCell(new PLabel($produtos->nome,'primary'))->colspan = 2;

$row = $this->table->addRow();
$row->addCell(new TImage('uploads/'.$produtos->imagem));
$row->addCell($produtos->descricao);
$row = $this->table->addRow();
$row->addCell(new PLabel('R$ '.$produtos->preco,'success'));

$row = $this->table->addRow();
$row->addCell('Qtd');
$qtd = new TEntry('qtd');

if(isset($param['qtd'])){
$qtd->setValue($param['qtd']);
}
$row->addCell($qtd);

/**
Criamos um btn para inserir os itens no nosso carrinho */
$row = $this->table->addRow();
$btn = new PButton('add','success');
$btn->setLabel('add+');

$action = new TAction(array($this,'addItem'));
$action->setParameter('id',$param['key']);
$btn->setAction($action,'add+');
$row->addCell($btn);

$this->form->setFields(array($btn,$qtd));
TTransaction::close();

}catch(Exception $e){
new TMessage('info',$e->getMessage());
}
}

/**
* Metodo para alterar a quantidade do item 
*/

public function updateItem($param){

// pega o item a ser alterado
$produtos = $this->cart->getIten($param['key']);


try{
TTransaction::open('sample');

$produto_old = new Produtos($param['key']); // pega o item antigo ara podermos usar a imagem do banco

/**
* nessa parte usamos os get da class PProduto para 
mostrar os dados, lembrando que todo produto inserido no carinho 
é do tipo PProduto da PWD */


$row = $this->table->addRow();
$row->addCell(new PLabel($produtos->getNome(),'primary'))->colspan = 2;

$row = $this->table->addRow();
$row->addCell(new TImage('uploads/'.$produto_old->imagem));
$row->addCell($produtos->getDescricao());
$row = $this->table->addRow();
$row->addCell(new PLabel('R$ '.$produtos->getPreco(),'success'));

$row = $this->table->addRow();
$row->addCell('Qtd');
$qtd = new TEntry('qtd');


$qtd->setValue($produtos->getQtd());

$row->addCell($qtd);

$row = $this->table->addRow();
$btn = new PButton('add','success');
$btn->setLabel('Update');

$action = new TAction(array($this,'addItem'));
$action->setParameter('id',$param['key']);
$action->setParameters(array('id'=>$param['key'],'qtd'=>$qtd->getValue()));
$btn->setAction($action,'add+');
$row->addCell($btn);

$this->form->setFields(array($btn,$qtd)); // inserimos os campos no form
TTransaction::close();

}catch(Exception $e){
new TMessage('info',$e->getMessage());
}
}


/** metodo para inserir ou atualizar um item 
*/


public function addItem($param){
try{
TTransaction::open('sample');

// pegamos o item a ser passado como parametro
$produtos = new Produtos($param['id']);

// intanciamos o PProduto a ser usado en nosso carrinho
$produto = new PProduto();
// setamos os valores do PProduto de acordo com os Produtos
$produto->setNome($produtos->nome);
$produto->setDescricao($produtos->descricao);
$produto->setQtd($param['qtd']);
$produto->setPreco($produtos->preco);
$produto->setId($produtos->id);

//inserimos os produtos
$this->cart->addItem($produto);

new TMessage('info','Item inserido com sucesso');

TTransaction::close();
}catch(Exception $e){

new TMessage('info',$e->getMessage());

}
}


}

?>