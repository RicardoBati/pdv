# PDV

Projeto de um ponto de venda online - PDV

## Executar o projeto

Depois de clonar o prejeto entre na pasta raiz (pdv) realize o passo a passo

#### Variaveis de ambiente

Configure as variáveis de ambiente no .env do projeto

```
URL=http://localhost
DB =pgsql
DB_HOST=localhost
DB_NAME=database
DB_USER=root
DB_PASS=root
DB_PORT=5435
```
#### Banco de dados

O DUMP do banco de dados se encontra na raiz do projeto (PGSQL)

#### Composer
Para executar esta aplicação é necessário rodar o Composer para que sejam criados os arquivos responsáveis pelo autoload das classes.

Caso não tenha o Composer instalado, baixe pelo site oficial: [https://getcomposer.org/download](https://getcomposer.org/download/)

Para rodar o composer, basta acessar a pasta do projeto e executar o comando abaixo em seu terminal:
```shell
 composer install
```

Após essa execução uma pasta com o nome `vendor` será criada na raiz do projeto e você já poderá acessar pelo seu navegador.

#### Rodar o servidor local do PHP.

Agora é só abrir um servidor do php localmente
```shell
 php -S localhost:8000
```

## Requirements

PHP 7 ou superior.
Composer
