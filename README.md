# Desafio UpperSoft
Desafio de ingressão para vaga de estágio na UpperSoft.

## Introdução
<p>
    O desafio trata-se de criar uma API de CRUD de usuários (não especificadamente autenticáveis).
</p>

<p>
    De um usuário devem ser informados: nome, cpf, data de nascimento, email, telefone e endereço. De um endereço devem ser informados: uf, cidade, bairro, logradouro, número e complemento.
</p>

<p>
    O desafio possui como critérios de avaliação:
</p>

- Qualidade de código;
- Código limpo;
- Simplicidade;
- Lógica de programação;
- Conceitos de orientação a objetos;
- Otimização do código implementado;
- Organização e padrão de Commits;
- Tratamento de erros.

<p>
    Como tentativa de atingir tais critérios e demonstrar conhecimento na área, algumas decisões de projeto foram tomadas, como por exemplo a criação de diferentes entidades relacionadas entre si (Usuario, Endereco e Estado) ao invés de uma simples classe/tabela contendo todas as informações requisitadas, assim aumentando a complexidade da solução, mas também a qualidade e integridade da mesma.
</p>

<p>
    <b>Obs:</b> As definições criadas por padrão pelo Laravel, referentes ao middleware de autenticação e filas de tarefas agendadas, foram mantidas para evitar eventuais problemas de dependência, podendo ser completamente ignoradas.
</p>

### Diagrama de classes
![Class Diagram Image](https://github.com/lrmendess/uppersoft_challenge/blob/master/docs/ClassDiagram.png?raw=true)

### Diagrama ER
![ER Diagram Image](https://github.com/lrmendess/uppersoft_challenge/blob/master/docs/ERDiagram.png?raw=true)

## Especificação da máquina de testes
- OS: Windows 10 Professional (build 19041);
- CPU: i7-3612QM 3.1GHz
- RAM: 12GB 1600MHz
- HDD: 500GB Sata2 

## Tecnologias utilizadas
- PHP 7.4.13
- Composer 2.0.8
- Laravel 8.20.1
- MySQL 5.7.24
- Apache 2.4.35
- Laragon 4.0.16
- Visual Studio Code
- Postman 7.36.1
- MySQL Workbench 8.0 CE
- Astah UML 8.2.0

## Bibliotecas extras
- [`laravel/installer`](https://github.com/laravel/installer)
- [`validator-docs`](https://github.com/geekcom/validator-docs)
- [`Laravel-lang`](https://github.com/Laravel-Lang/lang)

## Deploy
Instalar as dependências do projeto:
```bash
composer install
```

Gerar um `.env` válido:
```bash
cp .env.example .env
php artisan key:generate
```

Configurar o arquivo `.env` para atender as configurações do banco de dados.
```yml
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uppersoft_challenge
DB_USERNAME=root
DB_PASSWORD=
```
Criar um *schema* de nome <DB_DATABASE> (caso não exista) num banco de dados com engine MySQL:
```
create database <DB_DATABASE>;
```

Efetivar as migrações dos modelos de domínio utilizando a flag `--seed` para inserir os **Estados** no banco de dados:
```bash
php artisan migrate --seed 
```

Executar o servidor:
```ash
php artisan serve
```

## Endpoints
|Verb|URI|Action|
|-----|---|------|
|GET|`https://<HOST>/usuarios`|UsuariosController@index|
|GET|`https://<HOST>/usuarios/{id}`|UsuariosController@show|
|POST|`https://<HOST>/usuarios`|UsuariosController@store|
|PUT|`https://<HOST>/usuarios/{id}`|UsuariosController@update|
|DELETE|`https://<HOST>/usuarios/{id}`|UsuariosController@delete|

## Formato do corpo de respostas
Os campos `id` de **Usuario** e **Endereco** (devem) são sempre iguais devido ao relacionamento 1:1 existente entre suas chaves primárias.

Amostra de https://www.4devs.com.br/gerador_de_pessoas
```json
{
    "id": 2,
    "nome": "Lorena Silvana Mendes",
    "cpf": "84364556815",
    "data_nascimento": "24/07/1942",
    "email": "lorenasilvanamendes-79@engineer.com",
    "telefone": "6226749404",
    "endereco": {
        "id": 2,
        "complemento": "Próximo ao Bar do Dinai",
        "numero": 76,
        "logradouro": "Rua Genoveva Martins",
        "bairro": "Vila Santa Maria de Nazareth",
        "cidade": "Anápolis",
        "uf": "GO"
    }
}
```

## Formato do corpo de requisições
Não há necessidade de especificar os `id`'s no corpo da mensagem, mas sendo o caso, estes serão inclusos no processo de validação para evitar problemas de integridade no banco de dados.

Os campos `cpf` e `telefone` funcionam com e sem pontuação, sendo armazenados apenas seus dígitos, ou seja, armazenados sem máscara.

Como decisão de projeto, o campo `data_nascimento` deve obrigatoriamente estar no padrão nacional `d/m/Y`.

O campo `endereco.uf` deve conter apenas a sigla de um **Estado** válido e cadastrado no banco de dados, caso contrário, não passará pela etapa de validação de integridade.

Amostra de https://www.4devs.com.br/gerador_de_pessoas
```json
{
    "nome": "Alexandre Luiz Silva",
    "cpf": "117.329.653-07",
    "data_nascimento": "04/06/1968",
    "email": "aalexandreluizsilva@hotmail.com.br",
    "telefone": "(86) 99486-7889",
    "endereco": {
        "complemento": "",
        "numero": 187,
        "logradouro": "Rua Ludimar Carvalho",
        "bairro": "Angelim",
        "cidade": "Teresina",
        "uf": "PI"
    }
}
```

## Testes
No diretório `~/docs/` existe um arquivo chamado `uppersoft_challenge.postman_collection.json`, que pode ser importado e utilizado no Postman para testar os endpoints. Este contém alguns exemplos para utilização de todos os endpoints da API.
