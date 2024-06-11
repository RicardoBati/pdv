<?php

namespace App\Http;

class Request{

    /**
     * Instância do Router
     * @var Router
     */
    private $router;

    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Construtor da classe
     */
    public function __construct($router)
    {
        $this->router       = $router;
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
    }

    /**
     * Método responsável por definir a URI
     */
    private function setUri(){
        // URI completa (com GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //Remove GETS da URI
        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];
    }
    /**
     * Método responsável por retornar a instancia de Router
     * @return Router
     */

    public function getRouter (){
        return $this->router;
    }

    /**
     * Get método HTTP da requisição
     *
     * @return  string
     */ 
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Get uRI da página
     *
     * @return  string
     */ 
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get parâmetros da URL ($_GET)
     *
     * @return  array
     */ 
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Get variáveis recebidas no POST da página ($_POST)
     *
     * @return  array
     */ 
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * Get cabeçalho da requisição
     *
     * @return  array
     */ 
    public function getHeaders()
    {
        return $this->headers;
    }
}