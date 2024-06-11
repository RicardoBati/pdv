<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;

class Router{

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por inicar a classe
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url     = $url;
        $this->setPrefix();
    }      

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix() {
        //Informações da URL atual
        $parseUrl = parse_url($this->url);

        //Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []){
        //Validacao dos parametros
        foreach($params as $key=>$value){
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //Variaveis da rota
        $params['variables'] = [];

        //Padrão de validação das variaveis das rotas
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable,$route,$matches)) {
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }
        
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method]= $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $param
     */
    public function get($route,$params = []){
        return $this->addRoute('GET',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $param
     */
    public function post($route,$params = []){
        return $this->addRoute('POST',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $param
     */
    public function put($route,$params = []){
        return $this->addRoute('PUT',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $param
     */
    public function delete($route,$params = []){
        return $this->addRoute('DELETE',$route,$params);
    }

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        //URI da request
        $uri = $this->request->getUri();

        //Dividir a URI com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];
        
        //Retorna a URI sem prefixo
        return end($xUri);
    }
    /**
     * Método responsável por retornar os dados da rota atual
     */
    private function getRoute() {
        //URI
        $uri = $this->getUri();

        //Method
        $httpMethod = $this->request->getHttpMethod();

        //Valida as rotas
        foreach ($this->routes as $patternRoute => $methods) {
            //Verifica se a rota bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                //Verifica o método
                if (isset($methods[$httpMethod])) {
                    //Remove a primeira posição
                    unset($matches[0]);

                    //Variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                    //Retorno dos parâmetros da rota
                    return $methods[$httpMethod];
                }

                //Método não permitido/definido
                throw new Exception("Método não permitido", 405);
            }
        }
        //URL não encontrada
        throw new Exception("URL não encontrada", 404);

    }

    /** 
     * Método responsável por executar a rota atual
     * @return Response
     */ 
    public function run(){
        try {
            //Obtém a rota atual
            $route = $this->getRoute();
            
            //Verifica o controlador
            if (!isset($route['controller'])) {
                throw new Exception("URL não pôde ser processada", 500);
            }

            //Argumentos da função
            $args = [];

            //Reflection
            $refletion = new ReflectionFunction($route['controller']);
            foreach ($refletion->getParameters() as $key => $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }


            //Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(),$e->getMessage());
        }
    }
    
    /**
     * Método responsável por retornar a url atual
     * @return string
     */
    public function getCurrentUrl() {
        return $this->url.$this->getUri();
    }
}