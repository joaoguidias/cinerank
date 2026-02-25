# 🎬 CineRank

Plataforma para criar e gerenciar seu ranking pessoal dos top 100 filmes favoritos.

## 🚀 Tecnologias
- HTML5, CSS3, JavaScript
- Bootstrap 5
- PHP 8 com PDO
- MySQL
- API The Movie Database (TMDB)

## ⚙️ Funcionalidades
- Busca de filmes em tempo real via API do TMDB
- Adicionar filmes ao ranking pessoal
- Remover filmes do ranking
- Reordenar filmes por drag-and-drop

## 🛠️ Como rodar localmente
1. Clone o repositório
2. Crie o banco de dados MySQL com o arquivo `db/schema.sql`
3. Copie o `config.example.php` e renomeie para `config.php`
4. Preencha suas credenciais no `config.php`
5. Suba o projeto no XAMPP em `htdocs/cinerank`
6. Acesse `localhost/cinerank`

## 📝 Configuração
Crie um arquivo `config.php` baseado no `config.example.php` e preencha:
- Sua chave da API do TMDB
- Credenciais do banco de dados