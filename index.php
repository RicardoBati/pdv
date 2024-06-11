<?php

require __DIR__.'/includes/app.php';

use App\Http\Router;

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Inicia o router
$obRouter = new Router(URL);


//Inclui as rotas de página
include __DIR__.'/routes/pages.php';


//Imprime o response da rota
$obRouter->run()->sendResponse();

