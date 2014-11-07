<?php


class ListarCarrinho extends TWindow{

private $grid;
private $pagseguro;


function __construct(){
parent::__construct();

Clientes::checkCliente();

$this->pagseguro = new PPagSeguro('progs');
$this->setSize(550,400);
$this->setTitle('Lista de Produtos');

$this->grid = new TQuickGrid();

$this->grid->addQuickColumn('id', 'id', 'right', 100);
$this->grid->addQuickColumn('nome', 'nome', 'right', 200);
$this->grid->addQuickColumn('qtd', 'qtd', 'right', 100);
$this->grid->addQuickColumn('preco', 'preco', 'right', 100);

$action = new TDataGridAction(array('Carrinho', 'updateItem'));
$this->grid->addQuickAction('UpdateItem',$action,'id', 'ico_edit.png');

$form = new TQuickForm('frm_finalizar');

$action2 = new TAction(array($this,'finalizar'));

$form->addQuickAction('finalizar',$action2);

$this->grid->createModel();

$produtos = PCart::getItens();

if($produtos){

foreach($produtos as $p):

$item = new stdClass;
$item->id = $p->getId();
$item->nome = $p->getNome();
$item->qtd = $p->getQtd();
$item->preco = $p->getPreco();

$this->grid->addItem($item);

endforeach;

$box = new TVBox();
$box->add($this->grid);
$box->add($form);

parent::add($box);


}

}

public function finalizar(){

try{
TTransaction::open('sample');
$produtos = PCart::getItens();

// pegamos os dados do cliente
$cliente = new PCliente();
$cliente_compra = new Clientes(TSession::getValue('id'));

$cliente->setNome($cliente_compra->nome.' '.$cliente_compra->sobrenome);
$cliente->setCep($cliente_compra->cep);
$cliente->setLogradouro($cliente_compra->logradouro);
$cliente->setBairro($cliente_compra->bairro);
$cliente->setCidade($cliente_compra->cidade);
$cliente->setUf($cliente_compra->uf);
$cliente->setDD($cliente_compra->dd);
$cliente->setTelefone($cliente_compra->telefone);

$this->pagseguro->addCliente($cliente);

// cria o pedido
$pedidos = new Pedidos();
$pedidos->clientes_id = TSession::getValue('id');//seta o id do cliente
$pedidos->dataP = date('Y-m-d'); // seta a data
$pedidos->status = 1; // estatus 1 é de aguardando pagamento
$pedidos->store(); // salva o pedido

if($produtos){


foreach($produtos as $p):
$this->pagseguro->addItem($p); // add os itens no pagseguro

$pedido_produto = new PedidosProdutos(); // cria os itens do pedido
$pedido_produto->pedidos_id = $pedidos->id; //seta o id do pedido
$pedido_produto->produtos_id = $p->getId(); // seta o id do item
$pedido_produto->qtd = $p->getQtd(); // seta a qtd do iten
$pedido_produto->store(); //salva o item
endforeach;
}


$this->pagseguro->addCodVenda($pedidos->id); // seta o code do pedido no pagseguro

$link = new PLink('Finalizar');
$link->setLink($this->pagseguro->getButton());
$link->show();
PCart::clean(); // limpra o carrinho

TTransaction::close();
exit;

}catch(Exception $e){

new TMessage('error',$e->getMessage());
}
}

}

?>