<?php

namespace App\Model\Entity;

use App\Db\Database;

class ProductOrder {
    /**
     * ID do pedido
     * @var integer
     */
    public $id;

    /**
     * Referencia id da tabela produto
     * @var integer
     */
    public $id_produto;

    /**
     * Referencia id da tabela pedido
     * @var integer
     */
    public $id_pedido;

    /**
     * Quantidade de produtos
     * @var integer
     */
    public $quantidade;

    /**
     * Quantidade de produtos
     * @var float
     */
    public $valor_unitario;

    /**
     * valor total dos produtos
     * @var float
     */
    public $valor_total;

    /**
     * nome do produtos
     * @var string
     */
    public $nome_produto;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //insere o produto no banco de dados
        $this->id = (new Database('produtos_pedidos'))->insert([
            'id_produto'      => $this->id_produto,
            'id_pedido'       => $this->id_pedido,
            'quantidade'      => $this->quantidade,
            'valor_unitario'  => $this->valor_unitario,
            'valor_total'     => $this->valor_total,
            'nome_produto'    => $this->nome_produto
        ]);

        return true;
    }

    /**
     * Método responsável por retornar produtos_pedidos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getOrderProducts($where = null, $order = null, $limit = null, $offset = null, $fields = '*'){
        return (new Database('produtos_pedidos'))->select($where,$order,$limit,$offset,$fields);
    }
}