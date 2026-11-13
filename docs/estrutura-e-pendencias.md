# Estrutura adicionada e tarefas pendentes

Esta pasta contém o **repositório original do usuário**, acrescido de arquivos
e pastas necessários para representar os módulos aprovados. O código funcional
**não foi implementado**: os PHP/JS/CSS possuem apenas comentários TODO.
O `database/schema.sql` agora contém a estrutura das 12 tabelas validada parcialmente no MySQL 8.4.3.

## Documentos incluídos
- `README.md`: documento principal atualizado com o módulo Metas.
- `docs/regras-negocio.md`: **189 RN e 49 cuidados técnicos aprovados**.
- `docs/requisitos.md`: **46 RF e 25 RNF aprovados**, com aditivo descritivo de Metas ainda sem homologação própria.
- `docs/banco-de-dados.md`: modelagem de 12 tabelas **aprovada para testes**, com relatório dos testes concluídos e limitações.
- `docs/arquitetura.md`: **diretrizes preliminares**, não arquitetura implementada.

## Principais arquivos acrescidos
- Controllers, models, views e assets de **Biblioteca, Histórico e Metas**.
- Views adicionais para **autenticação**, **Perfil** e **finalização**.
- Camada de services, helper CSRF e pastas de teste.
- `manifest.webmanifest` com ícones ainda vazios, `service-worker.js` TODO e `.htaccess` TODO.
- `.env.example`, `.gitignore`, pastas `storage/logs` e imagens.

## Pendências prioritárias
1. Aprovar RF/RNF adicionais de Metas; atualizar requisitos.
2. Ampliar os testes de integridade do SQL e planejar migrações; modelagem inicial aprovada para testes.
3. Implementar autenticação, rotas, sessão e segurança.
4. Implementar fichas, biblioteca e sistema de treinos.
5. Implementar histórico editável com auditoria, estatísticas e metas.
6. Desenvolver interface mobile-first no padrão preto/grafite/verde-esmeralda.
7. Realizar testes manuais de todas as telas e automatizar fluxos críticos.
8. Configurar PWA instalável e somente depois evoluir para offline.

**Atenção:** arquivos antigos `app/views/sessoes/historico.php` e
`app/views/sessoes/detalhes.php` foram preservados por serem parte do ZIP enviado.
No desenvolvimento, decidir se serão eliminados ou redirecionados para
`app/views/historico/` para evitar duplicação de telas.
