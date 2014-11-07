<?php
/**
 * PedidosList Listing
 * @author  <your name here>
 */
class PedidosList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
         Usuario::checkLogin();
        parent::setDatabase('sample');            // defines the database
        parent::setActiveRecord('Pedidos');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::setFilterField('id');           // defines the filter field
        
        // creates the form, with a table inside
        $this->form = new TForm('form_search_Pedidos');
        $table = new TTable;
        $this->form->add($table);
        
        // create the form fields
        $filter = new TEntry('id');
        $filter->setValue(TSession::getValue('Pedidos_id'));
        
        // add a row for the filter field
        $table->addRowSet( new TLabel('id:'), $filter);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
    
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
    
        $find_button->setImage('ico_find.png');
      
        // add a row for the form actions
        $table->addRowSet($find_button);
        
        // define wich are the form fields
        $this->form->setFields(array($filter, $find_button));
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $dataP = $this->datagrid->addQuickColumn('dataP', 'dataP', 'left', 100);
        $clientes_id = $this->datagrid->addQuickColumn('cliente', 'clientes->nome', 'right', 100);
        $status = $this->datagrid->addQuickColumn('status', 'status', 'right', 100);
        

        
        // create the datagrid actions
        $itens_action   = new TDataGridAction(array($this, 'showItens'));
       
        // add the actions to the datagrid
        $this->datagrid->addQuickAction("Produtos", $itens_action, 'id', 'ico_find.png');
       
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
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
    
    public function showItens($param){
    
    $table = new PTableWriteHTML(); // usa a tabela do PWD para mostrar os itens do pedido
          $table->addRowTitle(); // cria linha de titulo
          //adciona as colunas
           $table->addCellTitle("Id",'center'); 
            $table->addCellTitle("Nome",'center');
             $table->addCellTitle("Preco",'center');
             
             $janela = new PWindows();
            
         
    try{
    TTransaction::open('sample');
    
    // cria as regras para consultar os itens do pediso
    $criteria = new TCriteria();
    $filtro = new TFilter('pedidos_id','=',$param['key']);
    $criteria->add($filtro);
 
 // carega os itens do pedido
    $obj = PedidosProdutos::getObjects($criteria);
    
   
    
   $total = 0;
    
    // adiona os itens
    foreach($obj as $itens):
    
   
   $table->addRow();
     
         $table->addCell($itens->id,'center','danger');
                   $table->addCell($itens->produto->nome,'center'); // pega o nome do produto usango o metodo get_produto
           $table->addCell($itens->qtd,'center');
           $table->addCell($itens->produto->preco,'center');
           
           $total += $itens->produto->preco; // adiciona os preços ao total 
           
    endforeach;
    $table->addRow();
    $table->addCell("Total : ".$total,'center');
  
TTransaction::close();
   $janela = new PWindows();
$janela->setSize(800,300);
 $janela->addContent( $table);
$janela->show();
  
       }catch(Exeption $e){
       
       echo $e->getMessage();
       }
    
    
    }
}
?>