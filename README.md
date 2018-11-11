# Desafio Symfony - CRUD de Empresas e Sócios
<img src="http://blog.4linux.com.br/wp-content/uploads/2018/04/symfony-4.jpg" width="55%">

Neste código foi feito um webservice REST com operações para as entidades Empresa e Sócio, onde uma empresa pode ter vários sócios.

### Especificações principais

* [Symfony](https://symfony.com/4) - v4.1.0 - Framework PHP MVC
* [Composer](https://getcomposer.org/) - v1.7.2 - Gerenciador de Pacotes
* [Doctrine](https://www.doctrine-project.org/) - v1.8.0 - ORM
* [PHP](https://secure.php.net/) - v7.1.0 - Linguagem
* [PostgreSQL](https://www.postgresql.org/) - v9.6.10 - SGBD

### Instalação

Tendo instalado e configurado as dependências acima, execute as seguintes etapas:

Abra o arquivo 'desafio-vox/.env'.
Ajuste a variavel DATABASE_URL as configurações já feitas.
Exemplo: 
DATABASE_URL=pgsql://postgres:SENHADOBANCO@127.0.0.1:5432/NOMEDOBANCO

Logo em seguida, entre no diretório do projeto e rode os comandos abaixo para instalar as demais dependências e criar as tabelas no banco de dados:
```sh
$ cd desafio-vox
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:diff
$ php bin/console doctrine:migrations:migrate
```

E para rodar o projeto:
```sh
$ php bin/console server:run
```

### Rotas
Estas são as rodas para uso deste webservice:
#### Empresas:
| Função | Rota | Parametro | Tipo |
| ------ | ------ | ------ | ------ |
| Empresa - Listar Todas | / | Nenhum | GET
| Empresa - Cadastrar | /empresa/new | JSON de Empresa (Nome e Telefone) | POST/GET
| Empresa - Editar | /empresa/edit/{id} | ID da Empresa | POST/GET
| Empresa - Remover | /empresa/new | JSON de Empresa | POST/GET
| Empresa - Exibir | /empresa/show/{id} | ID da Empresa | GET

#### Empresas:
| Função | Rota | Parametro | Tipo |
| ------ | ------ | ------ | ------ |
| Sócio - Listar Todas | /socios | Nenhum | GET
| Sócio - Cadastrar | /socio/new | JSON de Sócio (Nome, Telefone e ID de Empresa) | POST/GET
| Sócio - Editar | /socio/edit/{id} | ID do Sócio | POST/GET
| Sócio - Remover | /socio/new | JSON do Sócio | POST/GET
| Sócio - Exibir | /socio/show/{id} | ID do Sócio | GET
