# 🎓 MS Academy

> Plataforma web gratuita para disponibilização de conteúdos do Ensino Médio, com área administrativa para gerenciamento de disciplinas, conteúdos, vídeos e quizzes.

---

## 📌 Sobre o projeto

O **MS Academy** é uma aplicação web desenvolvida em PHP e MySQL com o objetivo de disponibilizar gratuitamente conteúdos didáticos para estudantes do Ensino Médio.

O sistema é dividido em duas áreas independentes:

- 🌐 Área pública destinada aos estudantes;
- 🔐 Painel administrativo para gerenciamento do conteúdo.

O projeto foi desenvolvido como forma de consolidar conhecimentos em desenvolvimento web, banco de dados, autenticação, CRUD e deploy.

---

## ✨ Funcionalidades

### Área Pública

- 📚 Visualização das disciplinas cadastradas
- 📄 Acesso aos conteúdos
- 🎥 Vídeos complementares
- 🔍 Pesquisa de conteúdos
- ❓ Quiz interativo
- 📊 Resultado imediato do quiz

### Área Administrativa

- Login protegido por sessão
- Dashboard administrativo
- CRUD de Disciplinas
- CRUD de Conteúdos
- CRUD de Vídeos
- CRUD de Perguntas
- CRUD de Alternativas

---

## 🛠 Tecnologias utilizadas

### Backend

- PHP 8
- mysqli
- Composer
- Dotenv

### Frontend

- HTML5
- CSS3
- JavaScript

### Banco de Dados

- MySQL

### Hospedagem

- InfinityFree

---

## 📂 Estrutura do Projeto

```text
ms-academy/
│
├── admin/
├── assets/
├── editor/
├── includes/
├── vendor/
├── index.php
├── disciplina.php
├── conteudo.php
├── quiz.php
├── resultado.php
└── config.php
```

---

## 🚀 Instalação

### 1. Clone o projeto

```bash
git clone https://github.com/SEU-USUARIO/ms-academy.git
```

---

### 2. Instale as dependências

```bash
composer install
```

---

### 3. Configure o ambiente

Crie um arquivo `.env` na raiz:

```env
DB_HOST=localhost
DB_NAME=msacademy
DB_USER=root
DB_PASS=sua_senha
```

---

### 4. Importe o banco

Execute o arquivo

```
msacademy.sql
```

no phpMyAdmin ou MySQL Workbench.

---

### 5. Execute

XAMPP

```
http://localhost/ms-academy/
```

ou

LAMP

```
http://localhost/ms-academy/
```

---

## 🔒 Segurança

- Prepared Statements (`mysqli`)
- Proteção contra SQL Injection
- Escape de HTML (`htmlspecialchars`)
- Autenticação por sessão
- Variáveis sensíveis armazenadas em `.env`

---

## 📸 Capturas de Tela

### Página Inicial

> *(adicione um print)*

### Dashboard

> *(adicione um print)*

### CRUD de Conteúdos

> *(adicione um print)*

---

## 📈 Melhorias Futuras (v2.0)

- Upload de imagens
- URLs amigáveis
- Editor de texto aprimorado
- Paginação
- Dashboard com estatísticas
- Melhor responsividade
- Área do aluno

---

## 👨‍💻 Autor

Desenvolvido por **Miguel** como projeto de estudo e portfólio.
