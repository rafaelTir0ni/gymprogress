# Estrutura adicionada e tarefas pendentes

Esta pasta contém o **repositório original do usuário**, acrescido de arquivos
e pastas necessários para representar os módulos aprovados. **A autenticação básica já possui implementação inicial** (cadastro, login, logout
e proteção do Dashboard); precisa ser testada no Laragon. Os demais
módulos PHP/JS/CSS permanecem principalmente como placeholders TODO.
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
3. Validar a autenticação básica no Laragon e completar posteriormente recuperação de senha e proteção de tentativas; não é prioridade para MVP pessoal.
4. Implementar fichas, biblioteca e sistema de treinos.
5. Implementar histórico editável com auditoria, estatísticas e metas.
6. Desenvolver interface mobile-first no padrão preto/grafite/verde-esmeralda.
7. Realizar testes manuais de todas as telas e automatizar fluxos críticos.
8. Configurar PWA instalável e somente depois evoluir para offline.

**Atenção:** arquivos antigos `app/views/sessoes/historico.php` e
`app/views/sessoes/detalhes.php` foram preservados por serem parte do ZIP enviado.
No desenvolvimento, decidir se serão eliminados ou redirecionados para
`app/views/historico/` para evitar duplicação de telas.

## Banco único durante desenvolvimento (decisão atual)

- Usar **apenas** `gymprogress_teste` no phpMyAdmin e no `.env` durante o desenvolvimento e a validação de todos os módulos.
- **Não criar** `gymprogress` por enquanto.
- `database/schema.sql` permanece **sem** `CREATE DATABASE` ou `USE`, para evitar seleção acidental de outra base.
- Na primeira instalação, selecionar `gymprogress_teste` no phpMyAdmin e importar em banco **vazio**; se as tabelas já existem, **não reimportar** o schema.
- Quando todos os testes acabarem, criar o banco definitivo e migrar apenas dados que devam ser preservados, com backup e verificação.
- O projeto é inicialmente de **uso pessoal no celular**, com interface mobile-first.


## Atualização do schema.sql

A partir desta revisão, `database/schema.sql` cria automaticamente o banco `gymprogress_teste` e executa `USE gymprogress_teste`, facilitando a configuração do projeto em dispositivos diferentes durante a fase de desenvolvimento e testes.
