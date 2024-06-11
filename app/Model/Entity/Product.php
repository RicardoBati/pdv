<?php

namespace App\Model\Entity;

use App\Db\Database;

class Product {

    /**
     * ID do produto
     * @var integer
     */
    public $id;

    /**
     * Nome do produto
     * @var string
     */
    public $nome;

    /**
     * Preço de custo do produto
     * @var float
     */
    public $preco_custo;

    /**
     * Preço de venda do produto
     * @var float
     */
    public $preco_venda;

    /**
     * Quantidade do produto
     * @var integer
     */
    public $quantidade;

    /**
     * descricao do produto
     * @var string
     */
    public $descricao;

    /**
     * tipo do produto
     * @var integer
     */
    public $tipo_produto;

    /**
     * data de cadastro de produto
     * @var string
     */
    public $data_cadastro;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //Define a data
        $this->data_cadastro = date('Y-m-d H:i:s');

        //insere o produto no banco de dados
        $this->id = (new Database('produtos'))->insert([
            'nome'          => $this->nome,
            'preco_custo'   => $this->preco_custo,
            'preco_venda'   => $this->preco_venda,
            'quantidade'    => $this->quantidade,
            'descricao'     => $this->descricao,
            'tipo_produto'  => $this->tipo_produto,
            'data_cadastro' => $this->data_cadastro
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar a instancia atual no banco de dados
     * @return PDOStatement
     */
    public function atualizar(){
        //insere o produto no banco de dados
        return (new Database('produtos'))->update('id = '.$this->id, [
            'nome'          => $this->nome,
            'preco_custo'   => $this->preco_custo,
            'preco_venda'   => $this->preco_venda,
            'quantidade'    => $this->quantidade,
            'descricao'     => $this->descricao,
            'tipo_produto'  => $this->tipo_produto
        ]);
    }

    /**
     * Método responsável por retornar um produto com base no id
     * @param integer $id
     * @return Product
     */
    public static function getProductsById($id){
        return self::getProducts('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar produtos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getProducts($where = null, $order = null, $limit = null, $offset = null, $fields = '*'){
        return (new Database('produtos'))->select($where,$order,$limit,$offset,$fields);
    }


    /**
     * Método responsável por deletar um produto
     * @param integer $id
     * @return PDOStatement
     */
    public static function deletar($id){
        return (new Database('produtos'))->delete('id = ' . $id);
    }

    /**
     * Método responsável por buscar produtos atráves do nome
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getProductsSeach($where,$order,$limit,$offset,$fields){
        return (new Database('produtos'))->select($where,$order,$limit,$offset,$fields);
    }
}