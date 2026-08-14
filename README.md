# Desafio Symfony - CRUD de Empresas e Sócios
<img src="https://winw.com.br/wp-content/uploads/2024/02/Nouveaute_s_Symfony_7-1024x683.webp" width="55%">

Webservice REST moderno com operações para as entidades **Empresa** e **Sócio**, onde uma empresa pode ter vários sócios vinculados.

---

### Especificações Principais (Atualizadas)

* **[Symfony](https://symfony.com/)** - `v7.1+`
* **[PHP](https://www.php.net/)** - `^8.3`
* **[Doctrine ORM](https://www.doctrine-project.org/)** - `v3.x`
* **[Composer](https://getcomposer.org/)** - `v2.x`
* **[PostgreSQL](https://www.postgresql.org/)** - `v16+`

---

### Instalação e Execução

1. **Configuração do Ambiente**:
   Abra ou crie o arquivo `.env` (ou `.env.local`) na raiz do projeto e configure a variável `DATABASE_URL`:
   ```env
   # PostgreSQL:
   DATABASE_URL="postgresql://postgres:SENHADOBANCO@127.0.0.1:5432/desafio_symfony?serverVersion=16&charset=utf8"

   # SQLite (opcional para testes rápidos):
   # DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
   ```

2. **Instalação das Dependências**:
   ```bash
   composer install
   ```

3. **Criação do Banco de Dados e Execução das Migrations**:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

4. **Execução do Servidor de Desenvolvimento**:
   Utilizando o Symfony CLI:
   ```bash
   symfony server:start
   ```
   Ou utilizando o servidor embutido do PHP:
   ```bash
   php -S 127.0.0.1:8000 -t public/
   ```

---

### Rotas e Endpoints da API

#### Empresas (`/` e `/empresa`)

| Função | Método | Rota | Payload (JSON) | Retorno (Status) |
| :--- | :---: | :--- | :--- | :---: |
| **Listar Todas** | `GET` | `/` | Nenhum | `200 OK` (Array de Empresas) |
| **Cadastrar** | `POST` | `/empresa/new` | `{"nome": "Nome", "telefone": "1199999999"}` | `201 Created` (Empresa) |
| **Exibir** | `GET` | `/empresa/{id}` | Nenhum | `200 OK` (Empresa) |
| **Editar** | `POST` / `PUT` | `/empresa/edit/{id}` | `{"nome": "Novo Nome", "telefone": "1188888888"}` | `200 OK` (Empresa) |
| **Remover** | `DELETE` / `POST` | `/empresa/delete/{id}` | Nenhum | `200 OK` / `204 No Content` |

#### Sócios (`/socios` e `/socio`)

| Função | Método | Rota | Payload (JSON) | Retorno (Status) |
| :--- | :---: | :--- | :--- | :---: |
| **Listar Todos** | `GET` | `/socios` | Nenhum | `200 OK` (Array de Sócios) |
| **Cadastrar** | `POST` | `/socio/new` | `{"nome": "Nome", "telefone": "1199999999", "empresa": 1}` | `201 Created` (Sócio) |
| **Exibir** | `GET` | `/socio/{id}` | Nenhum | `200 OK` (Sócio) |
| **Editar** | `POST` / `PUT` | `/socio/edit/{id}` | `{"nome": "Novo Nome", "telefone": "1188888888", "empresa": 1}` | `200 OK` (Sócio) |
| **Remover** | `DELETE` / `POST` | `/socio/delete/{id}` | Nenhum | `200 OK` / `204 No Content` |

---

### Exemplo de Uso com `curl`

```bash
# Cadastrar uma Empresa
curl -X POST http://127.0.0.1:8000/empresa/new \
  -H "Content-Type: application/json" \
  -d '{"nome": "Acme Corp", "telefone": "11999999999"}'

# Cadastrar um Sócio vinculado à Empresa (ID 1)
curl -X POST http://127.0.0.1:8000/socio/new \
  -H "Content-Type: application/json" \
  -d '{"nome": "John Doe", "telefone": "11988888888", "empresa": 1}'

# Listar Empresas
curl -X GET http://127.0.0.1:8000/
```
