<?php
/**
 * ProdutosForm Registration
 * @author  Alexandre E. Souza
 */
class ProdutosForm extends TStandardForm
{
    protected $form; 
    
   
    function __construct()
    {
        parent::__construct();
         Usuario::checkLogin();
        // cria o formulario
        $this->form = new TQuickForm('form_Produtos');
        $this->form->class = 'tform'; // class css do framework
        
        // titulo do formulario
        $this->form->setFormTitle('Produtos');
        
        // banco de dados em uso
        parent::setDatabase('sample');
        
        // model em uso
        parent::setActiveRecord('Produtos');
        


        // cria os campos do formulario
        $id                             = new THidden('id');
        $nome                           = new TEntry('nome');
        //eleciona a categoria
           $categoria                      = new TDBCombo('categoria_id','sample','Categoria','id','nome','nome');
        $descricao                      = new THtmlEditor('descricao');
        $preco                          = new TEntry('preco');
        $imagem                         = new PFile('imagem');
        $imagem->setFolder('uploads');

$preco->addValidation('preco',new TNumericValidator); // somente numeros
$preco->setNumericMask(2,'.',''); // seta a mascara para o mesmo padrao do mysql

        // adiciona os campos label,campo,tamanho
        $this->form->addQuickField('', $id,  100);
        $this->form->addQuickField('nome', $nome,  200);
        $this->form->addQuickField('preco', $preco,  200);
        $this->form->addQuickField('categoria', $categoria,  200);
          $this->form->addQuickField('imagem', $imagem,  200);
        $this->form->addQuickField('descricao', $descricao,  200);

// para alterar o tamanho de componentes em sua altura e largura
//deve coloca-las apos adicionar o campos no form

        $descricao->setSize(400,300);
     


        // adciona actions no form
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');

      
        $this->form->addQuickAction(_t('New'), new TAction(array($this, 'onEdit')), 'ico_new.png');

        
        // adciona o form na pagina
        parent::add($this->form);
    }
}
?>