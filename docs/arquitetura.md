# GymProgress — Arquitetura

> **Status: DIRETRIZ TÉCNICA PRELIMINAR — NÃO HOMOLOGADA COMO ARQUITETURA FINAL.**

## Stack planejada

PHP 8, MySQL/MariaDB, HTML/CSS/JavaScript, Chart.js, Laragon em desenvolvimento,
e PWA progressiva (MVP online → instalável → offline com sincronização).

## Organização MVC simplificada

- `public/index.php`: ponto de entrada HTTP com roteamento implementado para os módulos atuais.
- `app/controllers/`: recebem requisições e coordenam casos de uso.
- `app/models/`: representam as entidades e o acesso aos dados.
- `app/views/`: renderizam páginas e componentes.
- `app/services/`: regras transversais complexas de histórico, indicadores e metas.
- `app/helpers/`: autenticação, validações e proteção CSRF.
- `config/`: configuração de banco e serviços.
- `database/`: esquema das 12 tabelas implementado, com migrações complementares quando necessárias.
- `public/assets/`: CSS, JS e imagens publicamente servidos.
- `docs/`: especificação e decisões.
- `tests/`: cenários e testes automatizados a elaborar.

## Navegação mobile oficial

Barra inferior: **Início | Fichas | Treinar | Biblioteca | Perfil**.

O Dashboard não terá bloco redundante de “Acesso rápido”; pode mostrar aviso
discreto de nova conquista, consultável em **Perfil → Metas**.

## Segurança e deploy

Configurar servidor Apache/Laragon com `public/` como raiz pública; preservar
`app/`, `config/`, `database/`, `storage/` e `.env` fora do alcance HTTP.
Não publicar credenciais. Planejar autorização em cada operação, CSRF e SQL
preparado. O Service Worker e o PWA instalável já foram implementados; sincronização offline de registros permanece pendente.

**Referências normativas em 18/11/2026:** `regras-negocio.md` (197 RN + 53 CT aprovados)
e `requisitos.md` (58 RF + 32 RNF aprovados, incluindo os aditivos de Foto de Perfil e Metas e Conquistas).
