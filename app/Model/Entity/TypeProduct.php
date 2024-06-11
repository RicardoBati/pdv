<?php

namespace App\Model\Entity;

use App\Db\Database;

class TypeProduct {

    /**
     * ID do tipo
     * @var integer
     */
    public $id;

    /**
     * Tipo do produto
     * @var string
     */
    public $tipo;

    /**
     * Valor do imposto
     * @var float
     */
    public $valor_imposto;

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

        //insere o tipo de produto no banco de dados
        $this->id = (new Database('tipo_produto'))->insert([
            'tipo'  => $this->tipo,
            'valor_imposto'  => $this->valor_imposto,
            'data_cadastro' => $this->data_cadastro
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar a instancia atual no banco de dados
     * @return PDOStatement
     */
    public function atualizar(){        
        return (new Database('tipo_produto'))->update('id = '.$this->id, [
            'tipo'            => $this->tipo,
            'valor_imposto'   => $this->valor_imposto,
        ]);
    }

    /**
     * Método responsável por retornar tipos de produtos com base no id
     * @param integer $id
     * @return TypeProduct
     */
    public static function getTypeProductsById($id){
        return self::getTypeProducts('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar tipos de produtos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getTypeProducts($where = null, $order = null, $limit = null, $offset = null, $fields = '*'){
        return (new Database('tipo_produto'))->select($where,$order,$limit,$offset,$fields);
    }

    /**
     * Método responsável por deletar um tipo de produto
     * @param integer $id
     */
    public static function deletar($id){
        return (new Database('tipo_produto'))->delete('id = ' . $id);
    }
}