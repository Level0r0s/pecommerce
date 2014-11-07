<?php
// define the autoloader
include_once 'lib/adianti/util/TAdiantiLoader.class.php';
spl_autoload_register(array('TAdiantiLoader', 'autoload_web'));

// read configurations
$ini  = parse_ini_file('application.ini');
date_default_timezone_set($ini['timezone']);
TAdiantiCoreTranslator::setLanguage( $ini['language'] );
TApplicationTranslator::setLanguage( $ini['language'] );

// define constants
define('APPLICATION_NAME', $ini['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
$uri = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



new TSession;
ob_start();
$menu = TMenuBar::newFromXML('menu.xml');
$menu->show();
$menu_string = ob_get_contents();
ob_end_clean();

// verifica se o usuario esta logado para redirecionar 
// para o layout e login
if(TSession::getValue('logado')){
$template = 'theme1';
}else{
$template = 'loja';

}


$content  = file_get_contents("app/templates/{$template}/layout.html");
$content  = TApplicationTranslator::translateTemplate($content);
$content  = str_replace('{LIBRARIES}', file_get_contents("app/templates/{$template}/libraries.html"), $content);
$content  = str_replace('{URI}', $uri, $content);
$content  = str_replace('{class}', isset($_REQUEST['class']) ? $_REQUEST['class'] : '', $content);
$content  = str_replace('{template}', $template, $content);
$content  = str_replace('{MENU}', $menu_string, $content);
$css      = TPage::getLoadedCSS();
$js       = TPage::getLoadedJS();
$content  = str_replace('{HEAD}', $css.$js, $content);

if (isset($_REQUEST['class']))
{
    $url = http_build_query($_REQUEST);
    $content = str_replace('//#javascript_placeholder#', "__adianti_load_page('engine.php?{$url}');", $content);
}
echo $content;
?>