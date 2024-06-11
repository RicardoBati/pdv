<?php

namespace App\Controller\Pages;

use App\Model\Entity\Product as EntityProduct;
use App\Utils\View;
use App\Db\Pagination;
use App\Model\Entity\ProductOrder;
use App\Model\Entity\TypeProduct;
use App\Utils\Validate;

class Product extends Page{

    /**
     * Método responsável por obter a renderização dos produtos para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getProductItems($request,&$obPagination) {
        //produtos
        $items = '';

        //Quantidade total de registro
        $quantityTotal = EntityProduct::getProducts(null,null,null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantityTotal,$currentPage,4);

        //Resultados da página
        $results = EntityProduct::getProducts(null, 'id DESC', $obPagination->getLimit(), $obPagination->getOffSet());
        
        //Renderiza o item
        while ($obProduct = $results->fetchObject(EntityProduct::class)) {
            $items .=  View::render('pages/product/item', [
                'id'            => $obProduct->id,
                'nome'          => $obProduct->nome,
                'preco_custo'   => $obProduct->preco_custo,
                'preco_venda'   => $obProduct->preco_venda,
                'quantidade'    => $obProduct->quantidade,
                'tipo_produto'  => TypeProduct::getTypeProducts('id = '.$obProduct->tipo_produto,null,null,null, 'tipo')->fetchObject()->tipo,
                'data'          => date('d/m/Y H:i:s', strtotime($obProduct->data_cadastro))
            ]);
        }

        //Retorna os produtos
        return $items;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de produtos
     * @param Request $request
     * @return string
     */
    public static function getProducts($request) {
        //View de produtos
        $content =  View::render('pages/products', [
            'itens'      => self::getProductItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);
        //Retorna a View da Página
        return parent::getPage('Produtos', $content);
    }

    /**
     * Método responsável por retornar os tipos de produtos cadastrados
     * @param integer $id
     * @return string
     */
    public static function getOptionsTypeProducts($id) {
        //consulta tipos de produtos cadastrados
        $results = TypeProduct::getTypeProducts(null,null,null,null, '*');
        $itemOptions = '';
        
        //renderiza a view com os tipos de produtos
        while ($options = $results->fetchObject(TypeProduct::class)) {
            $itemOptions .=  View::render('pages/product/typeProdutcsOptions', [
                'id'            => $options->id,
                'tipo_produto'  => $options->tipo,
                'selected'      => $id == $options->id ? 'selected' : ''
            ]);
        }
        //retorna tipos cadastradas
        return $itemOptions;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de produtos
     * @param Request $request
     * @return string
     */
    public static function getNewProducts($request) {
        //View de produtos
        $content =  View::render('pages/product/form', [
            'title' => 'Cadastrar produto',
            'id_produto'    => '',
            'nome'          => '',
            'preco_custo'   => '',
            'preco_venda'   => '',
            'quantidade'    => '',
            'descricao'     => '',
            'tipo_produto'  => '',
            'options'       => self::getOptionsTypeProducts(''),

        ]);

        //Retorna a View da Página
        return parent::getPage('Produtos', $content);
    }

    /**
     * Método responsável por cadastrar um produto
     * @param Request $request
     * @return string
     */
    public static function insertProduct($request) {
        //Dados do post
        $postVars = $request->getPostVars();
        $validate = new Validate();

        //Valida campos vindos do post
        $camposValidados = $validate->validarCampos($postVars);

        //Nova instancia de produto
        $obProduct = new EntityProduct;
        $obProduct->nome         = $camposValidados['nome'];
        $obProduct->preco_custo  = $camposValidados['preco_custo'];
        $obProduct->preco_venda  = $camposValidados['preco_venda'];
        $obProduct->quantidade   = $camposValidados['quantidade'];
        $obProduct->descricao    = $camposValidados['descricao'];
        $obProduct->tipo_produto = $camposValidados['tipo_produto'];
        
        try {
            //CadastraProduto
            $obProduct->cadastrar();

            return json_encode([
                'status'    => true,
                'mensagem'  => 'Produto Cadastrado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Cadastrado Produto'
            ]);
        }
    }

    /**
     * Método responsável por retornar página de edição de produto
     * @param Request $request
     * @return string
     */
    public static function getEditProducts($request,$id) {
        //Obtem produto do banco de dados
        $obProduct = EntityProduct::getProductsById($id);
        
        //Renderiza view para editar produto
        $content =  View::render('pages/product/form', [
            'title'         => 'Editar produto',
            'id_produto'    => $obProduct->id,
            'nome'          => $obProduct->nome,
            'preco_custo'   => $obProduct->preco_custo,
            'preco_venda'   => $obProduct->preco_venda,
            'quantidade'    => $obProduct->quantidade,
            'descricao'     => $obProduct->descricao,
            'tipo_produto'  => $obProduct->tipo_produto,
            'options'       => self::getOptionsTypeProducts($obProduct->tipo_produto),
        ]);

        //Retorna a View da Página
        return parent::getPage('Editar Produtos', $content);
    }

    /**
     * Método responsável por editar um produto
     * @param Request $request
     * @return string
     */
    public static function setEditProducts($request, $id) {
        //Dados do post
        $postVars = $request->getPostVars();

        $validate = new Validate();
        
        //valida campos vindo do post
        $camposValidados = $validate->validarCampos($postVars);
        
        //instancia de produto
        $obProduct = EntityProduct::getProductsById($id);
        $obProduct->nome         = $camposValidados['nome'];
        $obProduct->preco_custo  = $camposValidados['preco_custo'];
        $obProduct->preco_venda  = $camposValidados['preco_venda'];
        $obProduct->quantidade   = $camposValidados['quantidade'];
        $obProduct->descricao    = $camposValidados['descricao'];
        $obProduct->tipo_produto = $camposValidados['tipo_produto'];

        try {
            //Atualiza produto
            $obProduct->atualizar();
            
            return json_encode([
                'status' => true,
                'mensagem' => 'Produto Editado com Sucesso'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Editar Produto'
            ]);
        }
    }

    /**
     * Método responsável por deletar um produto
     * @param Request $request
     * @return string
     */
    public static function setDeleteProduct($request) {
        
        $postVars = $request->getPostVars();
        $obProduct = EntityProduct::getProductsById($postVars['id']);

        //Valida se o produto tem alguma venda vinculado
        $product = ProductOrder::getOrderProducts('id_produto = '.$postVars['id'],null,null,null,'Count(*) as qtd')->fetchObject()->qtd;
        if ($product) {
            return json_encode([
                'mensagem' => 'Produto não pode ser excluido! Venda vincula',
                'status'   => false
            ]);
        }

        try {
            //deleta produto
            $obProduct->deletar($postVars['id']);
            
            return json_encode([
                'status' => true,
                'mensagem' => 'Produto Deletado com Sucesso'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Deletar Produto'
            ]);
        }
    }

    /**
     * Método responsável por buscar produtos para o select da venda
     * @param Request $request
     * @return string
     */
    public static function getProductSearch($request) {
        
        $queryParams = $request->getQueryParams();
        $searchTerm = '%' . $queryParams['search'] . '%';

        $results = EntityProduct::getProductsSeach("nome LIKE "."'".$searchTerm."'", null, null, null,'*');
        $products = [];
        while ($obProduct = $results->fetchObject(EntityProduct::class)) {
            $valor_imposto = TypeProduct::getTypeProducts('id = '.$obProduct->tipo_produto,null,null,null,'valor_imposto')->fetchObject()->valor_imposto;
            $arrayObjectProduct = get_object_vars($obProduct);
            $arrayObjectProduct['valor_imposto'] = $valor_imposto;
            $products[] =  $arrayObjectProduct;
        }
        echo json_encode(['products' => $products]);
    }

    
}