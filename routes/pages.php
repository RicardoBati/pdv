<?php


use App\Http\Response;
use \App\Controller\Pages;



//Rota / direcionada para a página de produtos
$obRouter->get('/',[
    function ($request) {
        return new Response(200,Pages\Product::getProducts($request));
    }
]);


// ----------- ROTAS DE PRODUTOS ---------------

//Rota consulta produtos
$obRouter->get('/produtos',[
    function ($request) {
        return new Response(200,Pages\Product::getProducts($request));
    }
]);

//Rota página de cadastrar produto
$obRouter->get('/novoProduto',[
    function ($request,$id) {
        return new Response(200,Pages\Product::getNewProducts($request));
    }
]);

//Rota inserir novo produtos
$obRouter->post('/novoProduto',[
    function ($request) {
        return new Response(200,Pages\Product::insertProduct($request));
    }
]);

//Rota página de editar produto
$obRouter->get('/produto/{id}/edit',[
    function ($request,$id) {
        return new Response(200,Pages\Product::getEditProducts($request,$id));
    }
]);

//Rota editar produto
$obRouter->post('/produto/{id}/edit',[
    function ($request,$id) {
        return new Response(200,Pages\Product::setEditProducts($request,$id));
    }
]);

//Rota deletar produto
$obRouter->post('/produto/deletar',[
    function ($request) {
        return new Response(200,Pages\Product::setDeleteProduct($request));
    }
]);

//Rota buscar produtos select venda
$obRouter->get('/produtos/buscar',[
    function ($request) {
        return new Response(200,Pages\Product::getProductSearch($request));
    }
]);

// ----------- FIM ROTAS DE PRODUTOS ---------------


// ----------- ROTAS TIPO DE PRODUTO ---------------

//Rota consulta tipos de Produto
$obRouter->get('/tipoProduto',[
    function ($request) {
        return new Response(200,Pages\TypeProduct::getTypeProducts($request));
    }
]);

//Rota página de cadastrar tipo de produto
$obRouter->get('/novoTipoProduto',[
    function ($request,$id) {
        return new Response(200,Pages\TypeProduct::getNewTypeProduct($request));
    }
]);

//Rota cadastrar tipo de produto
$obRouter->post('/novoTipoProduto',[
    function ($request) {
        return new Response(200,Pages\TypeProduct::insertTypeProduct($request));
    }
]);

//Rota página editar tipo de produto
$obRouter->get('/tipoProduto/{id}/edit',[
    function ($request,$id) {
        return new Response(200,Pages\TypeProduct::getEditTypeProducts($request,$id));
    }
]);

//Rota editar Tipo produto
$obRouter->post('/tipoProduto/{id}/edit',[
    function ($request,$id) {
        return new Response(200,Pages\TypeProduct::setEditTypeProducts($request,$id));
    }
]);

//Rota deletar tipo de produto
$obRouter->post('/tipoProduto/delete',[
    function ($request) {
        return new Response(200,Pages\TypeProduct::setDeleteTypeProduct($request));
    }
]);

// ----------- FIM ROTAS TIPO DE PRODUTO ---------------

// ----------- ROTAS VENDAS  ---------------

//Rota consulta vendas
$obRouter->get('/vendas',[
    function ($request) {
        return new Response(200,Pages\Order::getOrders($request));
    }
]);

//Rota visualizar uma venda
$obRouter->get('/vendas/{id}/visualizar',[
    function ($request,$id) {
        return new Response(200,Pages\Order::getVisualizeOrder($request,$id));
    }
]);

//Rota página de cadastrar nova venda
$obRouter->get('/NovaVenda',[
    function ($request,$id) {
        return new Response(200,Pages\Order::getNewOrders($request));
    }
]);

//Rota cadastrar nova venda
$obRouter->post('/venda',[
    function ($request) {
        return new Response(200,Pages\Order::insertOrder($request));
    }
]);

// ----------- FIM ROTAS VENDAS  ---------------
