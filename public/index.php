<?php
namespace PHPMVC;

use PHPMVC\lib\Authentication;
use PHPMVC\lib\CSRFSecHandler;
use PHPMVC\lib\Messenger;
use PHPMVC\Lib\Registry;
use PHPMVC\LIB\FrontController;
use PHPMVC\LIB\Language;
use PHPMVC\LIB\SessionManager;
use PHPMVC\Lib\Startup;
use PHPMVC\LIB\Template\Template;

if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once '..' . DS . 'app' . DS . 'config' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';

$session = new SessionManager();
$session->start();

if(!isset($session->lang)) {
    $session->lang = APP_DEFAULT_LANGUAGE;
}

$template_parts = require_once '..' . DS . 'app' . DS . 'config' . DS . 'templateconfig.php';

$template = new Template($template_parts);

$language = new Language();

$csrfToken = CSRFSecHandler::getInstance();
$csrfToken->setupToken();

$messenger = Messenger::getInstance($session);

$authentication = Authentication::getInstance($session);

$startup = new Startup($session, $authentication);

$registry = Registry::getInstance();
$registry->session = $session;
$registry->language = $language;
$registry->messenger = $messenger;
$registry->startup = $startup;

$frontController = new FrontController($template, $registry, $authentication);
$frontController->dispatch();
