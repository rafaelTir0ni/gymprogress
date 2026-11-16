# Estrutura adicionada e tarefas pendentes

Esta pasta contém o **repositório original do usuário**, acrescido de arquivos
e pastas necessários para representar os módulos aprovados. **A autenticação básica já possui implementação inicial** (cadastro, login, logout
e proteção do Dashboard); precisa ser testada no Laragon. Os módulos Fichas, Biblioteca e Treinar já foram implementados e testados no Laragon; Histórico v1 foi testado e aprovado no Laragon; Dashboard v1 foi implementado e aprovado nos testes do usuário. Outros módulos ainda contêm placeholders TODO.
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
3. Autenticação básica testada no Laragon; completar posteriormente recuperação de senha e proteção avançada de tentativas (não prioritárias para o MVP pessoal).
4. Fichas, Biblioteca e Treinar implementados e testados; validar Histórico v1.
5. Histórico editável com auditoria aprovado no Laragon; Dashboard v1 aprovado nos testes; Perfil v1 implementado e aguardando testes; estatísticas completas e metas permanecem pendentes.
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
- `database/schema.sql` **cria e seleciona `gymprogress_teste` automaticamente** com `CREATE DATABASE IF NOT EXISTS` e `USE`, para facilitar novas instalações.
- Na primeira instalação, importar o schema num servidor de desenvolvimento; ele cria/seleciona `gymprogress_teste`. Se as tabelas já existem, **não reimportar** o schema.
- Quando todos os testes acabarem, criar o banco definitivo e migrar apenas dados que devam ser preservados, com backup e verificação.
- O projeto é inicialmente de **uso pessoal no celular**, com interface mobile-first.


## Atualização do schema.sql

A partir desta revisão, `database/schema.sql` cria automaticamente o banco `gymprogress_teste` e executa `USE gymprogress_teste`, facilitando a configuração do projeto em dispositivos diferentes durante a fase de desenvolvimento e testes.


## Histórico v1 (implementado em código; aguardando testes no Laragon)

- Listagem filtrada por datas UTC e ficha; somente sessões finalizadas do usuário autenticado.
- Detalhes completos, snapshots, duração, volume, exercícios e séries.
- Edição de sessões finalizadas com nome, início, fim, observações, exercícios, séries, cargas, repetições e estados; confirmação CORRIGIR.
- Auditoria em `historico_alteracoes` com JSON de estado anterior e posterior, na mesma transação.
- A sessão deve manter ao menos uma série concluída. Não há rota de exclusão integral de sessão finalizada.
- Gráficos de carga do Treinar já calculam diretamente sobre dados concluídos e finalizados, refletindo as correções. Metas/conquistas ainda não implementadas.
- **Não executar `schema.sql` novamente** em banco populado; não há migração para este módulo.
- O arquivo `schema.sql` **já possui** `CREATE DATABASE IF NOT EXISTS gymprogress_teste` e `USE gymprogress_teste`, conforme decisão mais recente. A instrução antiga que diz o contrário está revogada.


## Dashboard v1 (implementado; aprovado nos testes informados pelo usuário)

- Primeira página após login, substituindo o painel provisório.
- Boas-vindas com o primeiro nome do usuário autenticado.
- Botão "Iniciar treino" abre a aba Treinar; sessão ativa oferece "Continuar treino".
- Indicadores de treinos finalizados na semana e no mês, sem contabilizar sessões em andamento nem descartadas.
- Semana inicia na segunda-feira; períodos usam o fuso salvo em `usuarios.timezone` (padrão America/Sao_Paulo), com timestamps armazenados em UTC.
- Última sessão finalizada com duração e séries concluídas, acessível pelo Histórico.
- Barra inferior com cinco posições; Perfil desativado enquanto o módulo não estiver implementado.
- Sem seção redundante de "Acesso rápido" nem migrações de banco de dados.
- Alertas de conquistas/metas dependem da futura implementação desse módulo.


## Perfil v1 (implementado; aguardando testes no Laragon)

- Consultar e atualizar nome/e-mail, alterar senha com verificação da senha atual e término da sessão atual.
- Preferências de fuso horário; acesso ao Histórico, saída da conta e aviso de Metas ainda pendente.
- Navbar única com ícones em Dashboard, Fichas, Biblioteca, Treinar, Histórico e Perfil.
- Atualização de dados protegida com autenticação, CSRF e validação no servidor.
- Não foi necessário alterar o `schema.sql`; continua sendo usado apenas `gymprogress_teste`.
- A revogação de sessões abertas em outros dispositivos ainda não está implementada.


## Metas e Conquistas v1 (implementado em código; testes no Laragon pendentes)

- Metas personalizadas, estados e prorrogação; conquistas automáticas e avisos no Dashboard.
- Recalcula ao finalizar treinos, corrigir histórico e acessar Dashboard/Metas.
- Usa as 12 tabelas do esquema existente e somente `gymprogress_teste`.
- Consulte `docs/metas-implementacao.md` para limites e testes.
