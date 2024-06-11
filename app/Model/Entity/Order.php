<?php

namespace App\Model\Entity;

use App\Db\Database;

class Order {
    /**
     * ID do venda
     * @var integer
     */
    public $id;

    /**
     * valor bruto do venda
     * @var float
     */
    public $valor_venda;

    /**
     * valor imposto do venda
     * @var float
     */
    public $valor_venda_imposto;

    /**
     * data de cadastro de produto
     * @var string
     */
    public $data_cadastro;

    /**
     * descricao do venda
     * @var string
     */
    public $descricao;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //Define a data
        $this->data_cadastro = date('Y-m-d H:i:s');

        //insere a venda no banco de dados
        $this->id = (new Database('pedidos'))->insert([
            'valor_venda'         => $this->valor_venda,
            'valor_venda_imposto' => $this->valor_venda_imposto,
            'descricao'           => $this->descricao,
            'data_cadastro'       => $this->data_cadastro
        ]);

        return true;
    }
    /**
     * Método responsável por retornar uma venda com base no id
     * @param integer $id
     * @return Product
     */
    public static function getOrderById($id){
        return self::getOrders('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar vendas
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getOrders($where = null, $order = null, $limit = null, $offset = null, $fields = '*'){
        return (new Database('pedidos'))->select($where,$order,$limit,$offset,$fields);
    }
}