# VárzeaCup

## Sobre o Projeto

A aplicação VárzeaCup é uma plataforma desenvolvida para gerenciar o campeonato interno de futebol da empresa ACME Inc. Ela permite o cadastro, edição, exclusão e listagem de times e partidas, além de exibir a classificação atual do campeonato de forma pública.

## Tecnologias Utilizadas
- **PHP 8.3**
- **Laravel 11**
- **Vue.js**
- **Banco de Dados Relacional** (PostgreSQL)
- **HTML e CSS**

## Requisitos do Sistema
1. PHP 8.3 ou superior
2. Composer
3. Node.js (com npm instalado)
4. Banco de Dados PostgreSQL

## Como Instalar o Projeto

### Passo 1: Clone o repositório
```bash
git clone https://github.com/jrsudbrack/varzeacup.git
cd varzeacup
```

### Passo 2: Instale as dependências do Laravel
```bash
composer install
```

### Passo 3: Instale as dependências do frontend
```bash
npm install
```

### Passo 4: Configure o arquivo `.env`
1. Copie o arquivo de exemplo:
    ```bash
    cp .env.example .env
    ```
2. Configure as variáveis de ambiente para conectar ao seu banco de dados:
    ```env
    DB_CONNECTION=pgsql
	DB_HOST=127.0.0.1
	DB_PORT=5432
	DB_DATABASE=varzeacup
	DB_USERNAME=postgres
	DB_PASSWORD=postgres
    ```

### Passo 5: Gere a chave da aplicação
```bash
php artisan key:generate
```

## Como Rodar o Projeto

### Etapa 1: Abrir três terminais

#### Terminal 1
Execute o seguinte comando para iniciar o servidor Laravel na porta 5057:
```bash
php artisan serve --port=5057
```

#### Terminal 2
Execute o seguinte comando para iniciar o servidor Laravel na porta 80:
```bash
php artisan serve --host=0.0.0.0 --port=80
```

#### Terminal 3
Inicie o servidor de desenvolvimento do Vue.js:
```bash
npm run dev
```

A aplicação estará acessível nos seguinte endereço:
- [http://localhost:5057](http://localhost:5057)

## Funcionalidades da Aplicação
- **Cadastro de Times e Partidas**: Inclui a possibilidade de adicionar, editar e excluir.
- **Classificação Atual**: Exibe a pontuação e a posição dos times, acessível tanto para usuários logados quanto públicos.
- **Autenticação**: Apenas usuários autenticados podem acessar as demais rotas.
- **Histórico de Campeonatos**: Registra todas as partidas e campeonatos anteriores.

## Diferenciais Implementados
- API RESTful para interação com o frontend.
- Interface dinâmica utilizando Vue.js para uma melhor experiência do usuário.

## Observações
- Layout minimalista desenvolvido sem frameworks CSS.

Para dúvidas ou suporte, entre em contato com o desenvolvedor. Obrigado por conferir o VárzeaCup!
