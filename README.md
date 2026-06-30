# MS Academy — Instruções de Instalação

## Requisitos
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache com `mod_rewrite` habilitado
- XAMPP / LAMP / InfinityFree

---

## 1. Banco de Dados

1. Abra o phpMyAdmin (ou cliente MySQL)
2. Execute o arquivo `bd-msacademy.sql` para criar o banco e as tabelas
3. O banco será criado com o nome `msacademy`

---

## 2. Configurar a Conexão

Edite o arquivo `includes/conexao.php`:

```php
$host     = "localhost";
$user     = "root";       // seu usuário MySQL
$password = "";           // sua senha MySQL
$database = "msacademy";
```

---

## 3. Copiar para o Servidor

Coloque a pasta `ms-academy/` dentro de:
- **XAMPP:** `C:/xampp/htdocs/ms-academy/`
- **LAMP:** `/var/www/html/ms-academy/`
- **InfinityFree:** raiz do `htdocs/`

---

## 4. Criar o Primeiro Administrador

1. Edite `criar_admin.php` e defina nome, e-mail e senha
2. Acesse: `http://localhost/ms-academy/criar_admin.php`
3. **APAGUE** o arquivo `criar_admin.php` imediatamente após!

---

## 5. Acessar o Sistema

| URL | Descrição |
|-----|-----------|
| `http://localhost/ms-academy/` | Site público |
| `http://localhost/ms-academy/admin/` | Painel admin |
| `http://localhost/ms-academy/admin/login.php` | Login admin |

---

## Estrutura de Diretórios

```
ms-academy/
├── index.php               ← Home pública
├── disciplina.php          ← Página da disciplina
├── conteudo.php            ← Página do conteúdo
├── quiz.php                ← Quiz interativo
├── resultado.php           ← Resultado do quiz
├── criar_admin.php         ← Script único (apagar após uso!)
├── .htaccess
│
├── admin/
│   ├── index.php           ← Dashboard
│   ├── login.php
│   ├── logout.php
│   ├── disciplina/         ← CRUD disciplinas
│   ├── conteudo/           ← CRUD conteúdos + editor WYSIWYG
│   ├── video/              ← CRUD vídeos YouTube
│   ├── pergunta/           ← CRUD perguntas do quiz
│   └── alternativa/        ← CRUD alternativas
│
├── includes/
│   ├── conexao.php         ← Conexão com o banco
│   ├── funcoes.php         ← Funções reutilizáveis
│   ├── auth.php            ← Controle de sessão
│   ├── header.php          ← Cabeçalho público
│   ├── footer.php          ← Rodapé público
│   └── menu.php            ← Navegação pública
│
├── assets/
│   ├── css/style.css       ← Estilos globais
│   ├── js/
│   └── img/
│
├── editor/
│   └── editor.js           ← Editor WYSIWYG
│
└── uploads/                ← Imagens enviadas (futuro)
```

---

## Fluxo do Quiz (RN04)

As respostas do quiz **não são salvas no banco de dados**. O resultado é calculado em JavaScript no navegador e armazenado temporariamente em `sessionStorage`, sendo exibido na página de resultado. Ao fechar o navegador os dados são descartados automaticamente.

---

## Segurança

- Todas as queries usam **Prepared Statements** (proteção contra SQL Injection)
- Dados exibidos passam por `htmlspecialchars()` via função `h()`
- Área admin protegida por **sessão PHP**
- `.htaccess` bloqueia listagem de diretórios e acesso às pastas internas
