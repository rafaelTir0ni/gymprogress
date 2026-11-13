# GymProgress — Arquitetura

> **Status: DIRETRIZ TÉCNICA PRELIMINAR — NÃO HOMOLOGADA COMO ARQUITETURA FINAL.**

## Stack planejada

PHP 8, MySQL/MariaDB, HTML/CSS/JavaScript, Chart.js, Laragon em desenvolvimento,
e PWA progressiva (MVP online → instalável → offline com sincronização).

## Organização MVC simplificada

- `public/index.php`: ponto de entrada HTTP; o roteamento ainda precisa ser implementado.
- `app/controllers/`: recebem requisições e coordenam casos de uso.
- `app/models/`: representam as entidades e o acesso aos dados.
- `app/views/`: renderizam páginas e componentes.
- `app/services/`: regras transversais complexas de histórico, indicadores e metas.
- `app/helpers/`: autenticação, validações e proteção CSRF.
- `config/`: configuração de banco e serviços.
- `database/`: esquema e dados de exemplo, **ainda não implementados**.
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
preparado. Service worker e offline exigem implementação específica posterior.

**Referências normativas:** `regras-negocio.md` (189 RN + 49 CT aprovados)
e `requisitos.md` (46 RF + 25 RNF aprovados; aditivo de RF/RNF de Metas ainda pendente).
