<?php


class Home extends TPage{

private $menu;
private $produtos;


public function __construct(){
parent::__construct();


 // pega o html que sera alterado
        $this->menu = new THtmlRenderer('app/resources/menu.html');
        $this->produtos = new THtmlRenderer('app/resources/produtos.html');
        
        // ativa a sessão principal
        $replace = array();
        $this->menu->enableSection('main', $replace);
         $this->produtos->enableSection('main', $replace);
           try{
        TTransaction::open('sample');
     
     $criteria = new TCriteria();
     $criteria->add(new TFilter('id','>',0));
     
            $categorias = Categoria::getObjects($criteria);
           
          
   
         TTransaction::close();
           //cria um array vario
            $replace_detail = array();
            
            if ($categorias)
            {
                // iterate products
                foreach ($categorias as $c)
                {
             
                           // adicio os itens no array
                           // a função toArray(), transforma o objeto em um array
                           // passando assim para a $variavel
                    $replace_detail[] = $c->toArray();
                }
            }
            
        
           
            // ativa a sessão e substitui as variaveis
            //o parametro true quer dizer que é um loop
            $this->menu->enableSection('manager', $replace_detail,TRUE);
            
            
            $box = new THBox();
            $box->add($this->menu);
             $box->add($this->produtos);
        parent::add($box);
        
        }catch(Exception $e){
        
        new TMessage('error',$e->getMessage());
        
        }
        
        
        parent::add($this->html);
      
        }
        
        public function listar($param){
        
        
        
         try{
        TTransaction::open('sample');
     
     $criteria = new TCriteria();
     $criteria->add(new TFilter('categoria_id','=',$param['id']));
     
            $produtos = Produtos::getObjects($criteria);
           
          
   
         TTransaction::close();
           //cria um array vario
            $replace_detail = array();
            
            if ($produtos)
            {
                // iterate products
                foreach ($produtos as $p)
                {
             
                           // adicio os itens no array
                           // a função toArray(), transforma o objeto em um array
                           // passando assim para a $variavel
                    $replace_detail[] = $p->toArray();
                }
            }
            
        
           
            // ativa a sessão e substitui as variaveis
            //o parametro true quer dizer que é um loop
            $this->produtos->enableSection('produtos', $replace_detail,TRUE);
            
            
     
        
        }catch(Exception $e){
        
        new Message('error',$e->getMessage());
        
        }
        
     
        
        }
        
   
     
        
}

?>