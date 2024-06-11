<?php

namespace App\Utils;

class Validate {
    // Função para validar campos
    function validarCampos($campos) {
        $camposValidados = [];

        foreach ($campos as $campo => $valor) {
            // Verifica se o valor não é nulo
            if ($valor !== null) {
                // Remover caracteres especiais, permitindo letras acentuadas e cedilha
                $valor = preg_replace('/[^a-zA-Z0-9,\.\-\sçáàâãäéèêëíìîïóòôõöúùûüÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜ]/u', '', $valor);

                // Se for um valor numérico, substituir vírgula por ponto e garantir que só haja um ponto decimal
                if ((str_replace(',', '.', $valor))) {

                    // Remover separadores de milhar (.)
                    $valor = str_replace('.', '', $valor);
                    // Substituir vírgulas por pontos
                    $valor = str_replace(',', '.', $valor);
                }
            }

            // Adicionar o campo validado ao array de campos validados
            $camposValidados[$campo] = $valor;
        }

        return $camposValidados;
    }
    // Função para validar  order
    public function validarOrder($array) {
        $arrayValidado = [];

        foreach ($array as $key => $value) {
            // Verifica se o valor não é nulo
            if ($value !== null) {
                if ($key === 'products') {
                    // Decodifica a string JSON dos produtos
                    $productsArray = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($productsArray)) {
                        // Valida cada produto na lista
                        $productsValidados = array_map([$this, 'validarCampos'], $productsArray);
                        // Adiciona a lista de produtos validada ao array validado
                        $arrayValidado[$key] = $productsValidados;
                    } else {
                        // Se a decodificação falhar, mantém o valor original
                        $arrayValidado[$key] = $value;
                    }
                } else {
                    // Valida o campo individualmente
                    $arrayValidado[$key] = $this->validarCampos([$key => $value])[$key];
                }
            } else {
                $arrayValidado[$key] = $value;
            }
        }

        return $arrayValidado;
    }
}