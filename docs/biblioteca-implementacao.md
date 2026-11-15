# GymProgress — Implementação da Biblioteca v1

**Status:** implementado no código; pendente de validação funcional no Laragon.  
**Banco:** `gymprogress_teste` (MySQL 8.4.3). **Não há migração necessária.**

## Funcionalidades

- Biblioteca protegida por autenticação (`biblioteca`), com busca textual, filtro muscular, exercícios gerais ou pessoais e visualização de arquivados pessoais.
- Detalhes de exercício (`biblioteca-detalhes`), descrição e links externos opcionais de mídia.
- Cadastro (`biblioteca-criar`) e edição (`biblioteca-editar`) somente de exercícios personalizados, com validação no servidor.
- Arquivamento e reativação (`biblioteca-acao`) sem exclusão física de exercícios e sem apagar vínculos históricos.
- Inclusão em ficha ativa pelo botão "+" (formulário na página de detalhes), exigindo escolha da ficha, séries, repetições e confirmação. Bloqueio de duplicidade pela chave `uq_ficha_exercicio`.
- Todos os POSTs exigem token CSRF. Transações nas alterações de estado e na associação à ficha.

### Rotas

| Rota | Métodos | Função |
|---|---|---|
| `biblioteca` | GET | Listagem, busca e filtros |
| `biblioteca-detalhes&id=...` | GET | Detalhes e associação à ficha |
| `biblioteca-criar` | GET/POST | Cadastro privado |
| `biblioteca-editar&id=...` | GET/POST | Edição privada |
| `biblioteca-acao` | POST | Arquivar, reativar ou adicionar à ficha |

## Testes manuais no Laragon

1. Entrar com sua conta e abrir `/gymprogress/public/index.php?route=biblioteca`.
2. Confirmar listagem vazia ou exercícios existentes e filtros de nome/categoria/tipo.
3. Criar exercício personalizado sem imagem ou vídeo; conferir na listagem.
4. Editar nome, descrição e grupo muscular; confirmar persistência.
5. Arquivar exercício: deve aparecer somente no filtro de arquivados pessoais; reativar e conferir.
6. Abrir exercício e adicionar a uma ficha **ativa** com `3` séries e `8-12` repetições; conferir na ficha.
7. Repetir a inclusão na mesma ficha: deve aparecer aviso de duplicidade, sem criar outra associação.
8. Arquivar a ficha e tentar adicioná-la: ela não deve ser elegível; alteração forçada em POST deve ser bloqueada.
9. Arquivar exercício que já está em uma ficha: a associação deve continuar preservada, sem remoção.
10. Logout e tentar abrir `biblioteca`: deve redirecionar para Login.
11. [Opcional, se houver segundo usuário de teste] Confirmar que um exercício personalizado de outra conta não pode ser lido, editado, arquivado nem inserido na ficha.

**Importante:** o projeto ainda não inclui exercícios gerais pré-cadastrados; `database/seeds.sql` permanece reservado para catálogo futuro com dados e mídias devidamente autorizados. O botão `+` usa a tela de detalhes e não depende de JavaScript. Módulo Treinar não faz parte desta entrega.

## Limites e próximos passos

- Busca limitada a 300 resultados por listagem para evitar consultas grandes; considerar paginação futura se necessário.
- Não houve testes de integração contra o MySQL local do usuário nesta entrega. A validação de sintaxe PHP não substitui testes funcionais.
- Sem alteração das regras e requisitos aprovados (`docs/regras-negocio.md`, `docs/requisitos.md`).

## Commit sugerido

`feat: implement exercise library management`
