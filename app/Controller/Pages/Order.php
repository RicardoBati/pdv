<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Order as EntityOrder;
use App\Db\Pagination;
use App\Model\Entity\ProductOrder;
use App\Utils\Validate;

class Order extends Page{
    /**
     * Método responsável por obter a renderização dos itens de venda para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getOrderItems($request,&$obPagination) {
        //vendas
        $items = '';

        //Quantidade total de registro
        $quantityTotal = EntityOrder::getOrders(null,null,null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantityTotal,$currentPage,4);

        //Resultados da página
        $results = EntityOrder::getOrders(null, 'id DESC', $obPagination->getLimit(), $obPagination->getOffSet());
        //Renderiza o item
        while ($obOrder = $results->fetchObject(EntityOrder::class)) {
            $items .=  View::render('pages/order/item', [
                'id' => $obOrder->id,
                'valor_venda' => $obOrder->valor_venda,
                'valor_venda_imposto' => $obOrder->valor_venda_imposto,
                'descricao' => $obOrder->descricao,
                'data'  => date('d/m/Y H:i:s', strtotime($obOrder->data_cadastro))
            ]);
        }
        //Retorna as vendas
        return $items;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de venas
     * @param Request $request
     * @return string
     */
    public static function getOrders($request) {
        //View de vendas
        $content =  View::render('pages/orders', [
            'itens' => self::getOrderItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);

        //Retorna a View da Página
        return parent::getPage('Vendas', $content);
    }

    /**
     * Método responsável por retornar a página de cadastro de vendas
     * @param Request $request
     * @return string
     */
    public static function getNewOrders($request) {
        $content =  View::render('pages/order/form', [
                'title' => 'Cadastrar Pedido',
                'produtos'          => '',
                'valor_bruto'   => '',
                'valor_liquido'   => '',
                'valor_imposto'   => '',
                'descricao'   => '',
        ]);

        //Retorna a View da Página
        return parent::getPage('Vendas', $content);
    }

    /**
     * Método responsável por renderizar os produtos que estão em uma venda
     * @param Request $request
     * @return string
     */
    public static function getOptionsOrderProducts($id) {
        $results = ProductOrder::getOrderProducts("id_pedido = ". $id,null,null,null, '*');
        $items = '';

        while ($options = $results->fetchObject(ProductOrder::class)) {
            $items .=  View::render('pages/order/visualizarItem', [
                'id' => $options->id_produto,
                'nome' => $options->nome_produto,
                'valor_unitario' => $options->valor_unitario,
                'valor_total' => $options->valor_total,
                'quantidade' => $options->quantidade,
            ]);
        }
        return $items;
    }

    /**
     * Método responsável por renderizar a visualizacao de uma venda
     * @param Request $request
     * @return string
     */
    public static function getVisualizeOrder($request,$id) {
        //Obtem venda do banco de dados
        $obOrder = EntityOrder::getOrderById($id);

        $content =  View::render('pages/order/visualizar', [
            'id'                    => $obOrder->id,
            'valor_venda'           => $obOrder->valor_venda,
            'valor_venda_imposto'   => $obOrder->valor_venda_imposto,
            'descricao'             => $obOrder->descricao,
            'data_cadastro'         => $obOrder->data_cadastro,
            'itens_produtos'        => self::getOptionsOrderProducts($obOrder->id),
        ]);

        //Retorna a View da Página
        return parent::getPage('Vendas', $content);
    }

    /**
     * Método responsável por cadastrar uma venda
     * @param Request $request
     * @return string
     */
    public static function insertOrder($request) {
        //Dados do post
        $postVars = $request->getPostVars();
        
        //Nova instancia de order
        $obOrder = new EntityOrder;
        $obOrder->valor_venda           = $postVars['valor_bruto'];
        $obOrder->valor_venda_imposto   = $postVars['valor_imposto'];
        $obOrder->descricao             = $postVars['descricao'];
        
        try {
            // Cadastra a venda
            $obOrder->cadastrar();

            // cadastra os produtos da venda
            $obProductOrder = new ProductOrder;
            foreach (json_decode($postVars['products'],true) as $key => $value) {
                $obProductOrder->id_pedido = $obOrder->id;
                $obProductOrder->id_produto = $value['id'];
                $obProductOrder->quantidade = $value['quantidade'];
                $obProductOrder->valor_unitario = $value['preco_venda'];
                $obProductOrder->valor_total = $value['quantidade'] * $value['preco_venda'];
                $obProductOrder->nome_produto = $value['label'];
                $obProductOrder->cadastrar();
            }

            return json_encode([
                'status' => true,
                'mensagem' => 'Venda Cadastrada com sucesso'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Cadastrar Venda'
            ]);
        }
    }
}