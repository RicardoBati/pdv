<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Db\Pagination;
use App\Model\Entity\Product;
use App\Model\Entity\TypeProduct as EntityTypeProduct;
use App\Utils\Validate;

class TypeProduct extends Page{

    /**
     * Método responsável por obter a renderização dos itens de tipo de produto para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTypeProductItems($request,&$obPagination) {
        //tipos de produto
        $items = '';

        //Quantidade total de registro
        $quantityTotal = EntityTypeProduct::getTypeProducts(null,null,null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantityTotal,$currentPage,4);

        //Resultados da página
        $results = EntityTypeProduct::getTypeProducts(null, 'id DESC', $obPagination->getLimit(), $obPagination->getOffSet());
        
        //Renderiza o item
        while ($obTypeProduct = $results->fetchObject(EntityTypeProduct::class)) {
            $items .=  View::render('pages/typeProduct/item', [
                'id' => $obTypeProduct->id,
                'tipo' => $obTypeProduct->tipo,
                'valor_imposto' => $obTypeProduct->valor_imposto
            ]);
        }

        //Retorna os tipos de produto
        return $items;
    }

    /**
     * Método responsável por retornar o conteúdo (view) de tipos de produto
     * @param Request $request
     * @return string
     */
    public static function getTypeProducts($request) {
        //View de tipos de produto
        $content =  View::render('pages/typeproducts', [
            'itens' => self::getTypeProductItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);

        //Retorna a View da Página
        return parent::getPage('Tipo Produtos', $content);
    }

    /**
     * Método responsável por retornar a página de cadastro tipos de produto
     * @param Request $request
     * @return string
     */
    public static function getNewTypeProduct($request) {
        $content =  View::render('pages/typeProduct/form', [
            'title'          => 'Cadastrar Tipo Produto',
            'id'   => '',
            'tipo_produto'   => '',
            'valor_imposto'  => ''
        ]);

        //Retorna a View da Página
        return parent::getPage('Tipo Produto', $content);
    }

    /**
     * Método responsável por retornar a página de edição tipos de produto
     * @param Request $request
     * @return string
     */
    public static function getEditTypeProducts($request,$id) {
        //Obtem tipo produto do banco de dados
        $obTypeProduct = EntityTypeProduct::getTypeProductsById($id);

        $content =  View::render('pages/typeProduct/form', [
            'title'             => 'Editar Tipo Produto',
            'id'                => $obTypeProduct->id,
            'tipo_produto'      => $obTypeProduct->tipo,
            'valor_imposto'     => $obTypeProduct->valor_imposto,
        ]);

        //Retorna a View da Página
        return parent::getPage('Tipo Produto', $content);
    }

    /**
     * Método responsável por editar um tipo de produto
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditTypeProducts($request, $id) {
        //Dados do post
        $postVars = $request->getPostVars();

        //valida campos vindos do post
        $validate = new Validate;
        $camposValidados = $validate->validarCampos($postVars);

        //Nova instancia de tipo de produto
        $obTypeProduct = EntityTypeProduct::getTypeProductsById($id);
        $obTypeProduct->tipo           = $camposValidados['tipo_produto'];
        $obTypeProduct->valor_imposto  = $camposValidados['valor_imposto'];

        try {
            //Atualiza tipo produto
            $obTypeProduct->atualizar();

            return json_encode([
                'status'    => true,
                'mensagem'  => 'Tipo de Produto Editado com Sucesso!'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Editar Tipo de Produto'
            ]);
        }
    }

    /**
     * Método responsável por cadastrar um tipo de produto
     * @param Request $request
     * @return string
     */
    public static function insertTypeProduct($request) {
        //Dados do post
        $postVars = $request->getPostVars();
        
        //Valida campos vindos do post
        $validate = new Validate;
        $camposValidados = $validate->validarCampos($postVars);

        //Nova instancia de typeProduct
        $obTypeProduct = new EntityTypeProduct;
        $obTypeProduct->tipo           = $camposValidados['tipo_produto'];
        $obTypeProduct->valor_imposto  = $camposValidados['valor_imposto'];

        try {
            //Atualiza tipo produto
            $obTypeProduct->cadastrar();

            return json_encode([
                'status'    => true,
                'mensagem'  => 'Tipo de Produto Cadastrado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Cadastrado Tipo de Produto'
            ]);
        }
    }

    /**
     * Método responsável por deletar um tipo de produto
     * @param Request $request
     * @return string
     */
    public static function setDeleteTypeProduct($request) {
        //Dados do post
        $postVars = $request->getPostVars();
        
        //Valida campos vindos do post
        $validate = new Validate;
        $camposValidados = $validate->validarCampos($postVars);

        //Valida se o tipo de produto tem algum produto vinculado
        $product = Product::getProducts('tipo_produto = '.$camposValidados['id'],null,null,null,'Count(*) as qtd')->fetchObject()->qtd;
        if ($product) {
            return json_encode([
                'mensagem' => 'Tipo não pode ser excluido! Produtos vinculados',
                'status'   => false
            ]);
        }

        //Nova instancia de typeProduct
        $obTypeProduct = EntityTypeProduct::getTypeProductsById($camposValidados['id']);
        
        try {
            //Deleta tipo produto
            $obTypeProduct->deletar($postVars['id']);

            return json_encode([
                'mensagem' => 'Tipo de Produto Excluido com Sucesso!',
                'status'   => true
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'status' => false,
                'mensagem' => 'Erro ao Excluir Tipo de Produto'
            ]);
        }
    }
}