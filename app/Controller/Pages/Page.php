<?php

namespace App\Controller\Pages;

use \App\Utils\View;

Class Page{

    /**
     * Método responsável por renderizar o topo da página
     * @return string
     */
    private static function getHeader() {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o rodapé da página
     * @return string
     */
    private static function getFooter() {
        return View::render('pages/footer');
    }

    /**
     * Método responsável por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $obpagination
     * @return string
     */
    public static function getPagination($request,$obpagination) {
        //Páginas
        $pages = $obpagination->getPages();

        //Verifica a quantidade de páginas
        if (count($pages) <= 1) return '';

        //Links
        $links = '';

        //url atual (sem GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GET
        $queryParams = $request->getQueryParams();

        //Renderiza os links

        foreach($pages as $page){
            //Altera página
            $queryParams['page'] = $page['page'];

            //Link
            $link = $url.'?'.http_build_query($queryParams);

            //View
            $links .= View::render('pages/pagination/link', [
                'page'   => $page['page'],
                'link'   => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }
        
        //renderiza box de páginacao
        return View::render('pages/pagination/box', [
            'links' => $links
        ]);
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($title,$content) {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
        ]);
    }
}