# GymProgress — Modelagem do banco de dados

> **Versão:** 1.3 — **MODELAGEM APROVADA PARA TESTES (MySQL 8.4.3)**  
> **Tecnologia confirmada:** MySQL 8.4.3 (Laragon), PHP com PDO, `utf8mb4`, InnoDB.  
> **Documentos preservados:** 189 regras de negócio e 49 cuidados técnicos aprovados; 46 requisitos funcionais e 25 não funcionais aprovados. Requisitos adicionais de Metas ainda pendentes de homologação.  
> **Esquema SQL executável disponível:** `database/schema.sql`, para utilização apenas em banco novo e vazio após seleção explícita da base no phpMyAdmin.

## 1. Visão geral

| Área | Tabela | Objetivo |
|---|---|---|
| Autenticação | `usuarios` | Contas e preferências básicas |
| Autenticação | `recuperacao_senhas` | Tokens temporários para redefinição de senha |
| Fichas | `fichas` | Planejamento e arquivamento de fichas |
| Biblioteca | `exercicios` | Catálogo público e exercícios pessoais |
| Fichas | `ficha_exercicios` | Ordem, séries e repetições prescritas de cada exercício em uma ficha |
| Treinar | `sessoes` | Identidade, status e duração de cada treino |
| Treinar | `sessao_exercicios` | Snapshot dos exercícios incluídos ao iniciar uma sessão |
| Treinar | `series_realizadas` | Séries planejadas e extras; valores e conclusão real |
| Histórico | `historico_alteracoes` | Auditoria de correções em sessões finalizadas |
| Metas | `metas` | Objetivos particulares, prazos e estados |
| Conquistas | `conquistas` | Definições dos tipos de conquistas automáticas |
| Conquistas | `usuario_conquistas` | Conquistas válidas, ocorrências e avisos por usuário |

Total: **12 tabelas candidatas**. A contagem é preliminar.

## 2. Modelo lógico preliminar

### 2.1 `usuarios`

| Campo sugerido | Tipo lógico | Restrições / descrição |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `nome` | VARCHAR(120) | Obrigatório |
| `email` | VARCHAR(190) | Obrigatório e único; normalizar para comparação sem diferenciar maiúsculas/minúsculas |
| `senha_hash` | VARCHAR(255) | Obrigatório; `password_hash` |
| `timezone` | VARCHAR(64) | Preferência para exibir datas e calcular semanas; padrão sugerido `America/Sao_Paulo` |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

### 2.2 `recuperacao_senhas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `usuario_id` | BIGINT UNSIGNED | PK; FK → usuarios.id |
| `token_hash` | VARCHAR(255) | Armazenar hash do token, nunca token recuperável |
| `expira_em`, `utilizado_em`, `criado_em` | DATETIME | Expiração, uso único e criação |

### 2.3 `fichas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `usuario_id` | BIGINT UNSIGNED | PK; FK → usuarios.id |
| `nome` | VARCHAR(120) | Obrigatório, repetição permitida |
| `descricao` | TEXT NULL | Opcional |
| `grupo_muscular` | VARCHAR(80) NULL | Principal/opcional |
| `status` | ENUM / VARCHAR | `ativa` ou `arquivada` |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

Fichas vazias são permitidas; fichas arquivadas não iniciam novas sessões. Excluir fisicamente apenas sem histórico e sem sessão ativa, após confirmação e verificações no backend.

### 2.4 `exercicios`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `usuario_id` | BIGINT UNSIGNED NULL | FK → usuarios.id; `NULL` significa catálogo geral |
| `nome` | VARCHAR(150) | Obrigatório |
| `grupo_muscular` | VARCHAR(80) | Categoria |
| `descricao` | TEXT NULL | Opcional |
| `imagem_url`, `video_url` | VARCHAR(500) NULL | Mídias opcionais e autorizadas |
| `status` | ENUM / VARCHAR | `ativo` ou `arquivado` para personalizados |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

O usuário só pode editar/arquivar exercícios personalizados próprios; os gerais são somente leitura. Arquivar não apaga relações com sessões passadas.

### 2.5 `ficha_exercicios`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `ficha_id`, `exercicio_id` | BIGINT UNSIGNED | PK; FKs → fichas / exercicios |
| `ordem` | INT UNSIGNED | Ordem visual |
| `series_planejadas` | SMALLINT UNSIGNED | Maior que zero |
| `reps_min`, `reps_max` | SMALLINT UNSIGNED | Ambos positivos; para repetições fixas, iguais |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

**UNIQUE (`ficha_id`, `exercicio_id`)** para impedir repetição na mesma ficha. Validar `reps_min <= reps_max`. O mesmo exercício pode existir em fichas diferentes.

### 2.6 `sessoes`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `usuario_id`, `ficha_id` | BIGINT UNSIGNED | PK, usuário e ficha associados |
| `nome_ficha_snapshot` | VARCHAR(120) | Nome da ficha copiado no início |
| `status` | ENUM / VARCHAR | `em_andamento` ou `finalizada`; descartar significa exclusão física após confirmação |
| `iniciada_em`, `finalizada_em` | DATETIME | Datas/horas, preferencialmente armazenadas em UTC |
| `duracao_segundos` | INT UNSIGNED | Derivada do intervalo entre início e fim; pausas incluídas |
| `observacoes` | TEXT NULL | Anotações opcionais da sessão, para edição histórica |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

Somente uma sessão `em_andamento` por usuário; precisa de proteção transacional e restrição de banco adequada ao mecanismo escolhido. A sessão não poderá passar a `finalizada` sem uma série realmente concluída; isso também vale após correções históricas. **Decisão confirmada:** descartar uma sessão em andamento exige confirmação e exclui fisicamente a sessão e seus registros dependentes (`sessao_exercicios` e `series_realizadas`) em uma operação transacional. Não existe status persistente `descartada`. Somente a própria sessão ativa do usuário poderá ser descartada; sessões finalizadas não poderão ser excluídas por esse fluxo. A ficha original e os exercícios da Biblioteca permanecem intactos.

### 2.7 `sessao_exercicios`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `sessao_id`, `exercicio_id` | BIGINT UNSIGNED | PK; FKs → sessões / exercícios |
| `nome_exercicio_snapshot` | VARCHAR(150) | Nome copiado ao iniciar |
| `grupo_muscular_snapshot` | VARCHAR(80) | Categoria copiada |
| `ordem` | INT UNSIGNED | Ordem da sessão, editável no Histórico |
| `series_planejadas_snapshot` | SMALLINT UNSIGNED | Total prescrito no início |
| `reps_min_snapshot`, `reps_max_snapshot` | SMALLINT UNSIGNED | Prescrição copiada |
| `status` | ENUM / VARCHAR | `pendente`, `parcialmente_realizado`, `concluido`, `concluido_antecipadamente` |
| `concluido_em` | DATETIME NULL | Momento da conclusão, quando aplicável |

O snapshot impede alterações retroativas após edição da ficha/exercício. Ao corrigir uma sessão, atualizar seus registros específicos com auditoria, sem alterar a ficha original.

### 2.8 `series_realizadas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `sessao_exercicio_id` | BIGINT UNSIGNED | PK; FK → sessao_exercicios.id |
| `numero_serie` | SMALLINT UNSIGNED | Ordem da série |
| `origem` | ENUM / VARCHAR | `planejada` ou `extra` |
| `repeticoes` | SMALLINT UNSIGNED NULL | Valor realizado; só preenchido quando aplicável |
| `carga_kg` | DECIMAL(7,2) NULL | Não negativa; NULL para série sem carga externa informada |
| `concluida` | BOOLEAN | Indica realização real |
| `concluida_em` | DATETIME NULL | Horário, quando houver |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

Séries ainda não realizadas não contam para o volume, recordes ou metas. Volume externo = Σ (`carga_kg` × `repeticoes`) apenas para séries concluídas com carga registrada. Carga vazia não equivale a carga zero. Séries extras devem preservar `origem`.

### 2.9 `historico_alteracoes`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `sessao_id`, `usuario_id` | BIGINT UNSIGNED | PK; FKs → sessões / usuários |
| `tipo_operacao` | VARCHAR(60) | Ex.: editar_sessao, incluir_exercicio, remover_serie |
| `dados_anteriores`, `dados_novos` | JSON | Snapshot da mudança, sem segredos de autenticação |
| `alterado_em` | DATETIME | Horário da correção |

Correções de Histórico são transacionais: verificar autorização, validar existência de ao menos uma série concluída, gravar edição e auditoria, invalidar/recalcular gráficos, metas e conquistas afetadas. Data/hora editadas recalculam duração. **A edição não exclui integralmente a sessão.**

### 2.10 `metas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `usuario_id` | BIGINT UNSIGNED | PK; FK → usuarios.id |
| `exercicio_id` | BIGINT UNSIGNED NULL | FK → exercicios.id em meta de carga |
| `tipo` | ENUM / VARCHAR | `carga`, `frequencia_semanal`, `sessoes_acumuladas` |
| `titulo` | VARCHAR(150) | Identificação |
| `valor_alvo` | DECIMAL(10,2) | Meta positiva; quantidade de treinos deve ser inteira |
| `inicio_em` | DATE | Referência inicial do progresso |
| `prazo_em` | DATE NULL | **Opcional** |
| `status_base` | ENUM / VARCHAR | `ativa` ou `arquivada` (conclusão e prazo são calculados a partir dos registros) |
| `atingida_em` | DATETIME NULL | Data de conquista, sujeita a recálculo |
| `criado_em`, `atualizado_em` | DATETIME | Auditoria |

O estado exibido **Prazo encerrado** será derivado quando a meta ainda não tiver sido atingida e o prazo estiver no passado; ao estender o prazo a meta volta a poder ser acompanhada. Arquivar não elimina progresso, e a edição histórica pode alterar `atingida_em`.

### 2.11 `conquistas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(100) | Chave única da definição |
| `nome`, `descricao` | VARCHAR / TEXT | Textos de exibição |
| `tipo` | VARCHAR(60) | Recorde pessoal, semanal, frequência, marco |
| `parametros` | JSON NULL | Limiar ou regra do tipo de conquista |
| `ativo` | BOOLEAN | Disponível no catálogo |

### 2.12 `usuario_conquistas`

| Campo | Tipo lógico | Detalhes |
|---|---|---|
| `id`, `usuario_id`, `conquista_id` | BIGINT UNSIGNED | PK; FKs → usuários e conquistas |
| `exercicio_id` | BIGINT UNSIGNED NULL | Identifica exercício, quando pertinente |
| `periodo_inicio`, `periodo_fim` | DATE NULL | Identificam semana ou período avaliado |
| `chave_ocorrencia` | VARCHAR(180) | **Única por usuário**, para idempotência; identifica tipo/exercício/período/marco |
| `valor_atingido` | DECIMAL(10,2) NULL | Valor da ocorrência |
| `conquistada_em` | DATETIME | Momento/atribuição histórica |
| `valida` | BOOLEAN | Falso quando correção histórica invalida conquista |
| `aviso_visualizado_em`, `aviso_dispensado_em` | DATETIME NULL | Controle de aviso no Dashboard |

**UNIQUE (`usuario_id`, `chave_ocorrencia`)** para impedir duplicações, inclusive após recálculo. Em recordes semanais, atualizar a ocorrência da semana em vez de criar várias notificações do mesmo marco.

## 3. Relacionamentos principais

- `usuarios` 1:N `fichas`, `sessoes`, `metas`, `recuperacao_senhas` e `usuario_conquistas`.
- `usuarios` 1:N `exercicios` personalizados; exercícios gerais têm `usuario_id = NULL`.
- `fichas` N:N `exercicios`, via `ficha_exercicios`.
- `fichas` 1:N `sessoes` (a sessão guarda snapshot e deve ser preservada).
- `sessoes` 1:N `sessao_exercicios`; `sessao_exercicios` 1:N `series_realizadas`.
- `sessoes` 1:N `historico_alteracoes`.
- `conquistas` 1:N `usuario_conquistas`; metas referenciam usuário e, quando aplicável, exercício.

## 4. Regras de integridade para o banco e o backend

1. Verificar a **propriedade do usuário** em todas as ações, incluindo IDs recebidos via URL, formulário ou API.
2. Impedir **sessões ativas simultâneas** por usuário (transação + restrição confiável no banco).
3. Impedir duplicação de `ficha_exercicios(ficha_id, exercicio_id)` por índice único.
4. Guardar **snapshots** de nome/ordem/prescrição para evitar alteração retroativa de sessões.
5. Preservar registros de sessões finalizadas e impedir exclusões físicas em cascata que destruam o histórico.
6. A finalização e **toda correção histórica** exigem pelo menos **uma série concluída**.
7. Correções históricas alteram apenas a sessão, **não** a ficha de origem. Guardar trilha de auditoria.
8. Duração = `finalizada_em - iniciada_em`, inclusive pausas, com intervalo válido; corrigir datas recalcula duração.
9. Gráficos de carga: maior `carga_kg` de séries concluídas de sessões finalizadas, agrupadas por exercício e semana conforme fuso configurado.
10. O descarte confirmado de uma sessão em andamento exclui fisicamente a sessão e seus registros dependentes, portanto seus dados não aparecem no Histórico, gráficos, métricas, metas ou conquistas. A operação deve ser autorizada, transacional e exclusiva da sessão ativa do usuário.
11. Metas de carga exigem exercício; prazo é opcional; **Prazo encerrado** preserva progresso e aceita prorrogação/arquivamento.
12. Recálculo de conquistas deve ser **idempotente**, considerar edições históricas e não repetir avisos antigos.
13. Credenciais e tokens devem permanecer protegidos; nunca incluir senhas ou tokens em JSON de auditoria.
14. Preferir índices por `(usuario_id, status, iniciada_em)` em sessões e por exercício/data para consultas de evolução. Validar com EXPLAIN quando o sistema estiver implementado.
15. A exclusão de uma ficha sem histórico deve exigir ausência de sessão ativa. Nunca aplicar ON DELETE CASCADE indiscriminadamente às relações que preservam sessões finalizadas. O descarte remove a própria sessão antes de eventual exclusão da ficha.

## 5. Decisões ainda pendentes antes do `schema.sql`

### A. Descarte de sessões — DECISÃO CONFIRMADA

- O usuário precisa confirmar o descarte de uma sessão **em andamento**.
- O backend precisa verificar `usuario_id` e `status = em_andamento` antes de excluir.
- Excluir fisicamente a linha de `sessoes` e suas dependências `sessao_exercicios` e `series_realizadas` em transação; as chaves estrangeiras dessas dependências poderão usar `ON DELETE CASCADE` **somente no sentido de limpeza dos registros daquela sessão**.
- Impedir a exclusão por esse fluxo de uma sessão `finalizada`.
- Não apagar registros de `fichas`, `ficha_exercicios`, `exercicios`, `usuarios`, `metas` ou sessões diferentes.
- Se alguma exclusão falhar, reverter toda a transação.
- Não manter `status = descartada`: o descarte não é um estado persistido.

### B. Informações ao criar uma meta

- **Recomendação:** a meta de carga define exercício e carga-alvo; a meta de frequência define quantidade de treinos por semana; e a meta de sessões acumuladas define quantidade total. Prazo opcional em todos os tipos.
- Essa proposta detalha a interface de cadastro sem alterar as regras de negócio existentes.

## 6. Etapas após aprovação

1. Validar decisões restantes e aprovar a modelagem lógica, incluindo FKs e exclusões.
2. Definir tipos SQL exatos, FKs, ações de exclusão, índices e CHECKs compatíveis com MySQL/MariaDB escolhido.
3. Criar `database/schema.sql` e `database/seeds.sql` com exemplos seguros e exercícios gerais autorizados.
4. Testar o banco no Laragon/Workbench ou phpMyAdmin e registrar resultados.
5. Implementar os models em PHP/PDO e os primeiros fluxos de autenticação.

**Esta proposta não modifica `README.md`, `docs/requisitos.md` nem `docs/regras-negocio.md`.**

## 7. Decisão registrada nesta revisão

**13/11/2026 — descarte confirmado:** sessão descartada deve ser fisicamente excluída junto com seus exercícios e séries, após confirmação, preservando fichas, Biblioteca e histórico finalizado. **A modelagem integral ainda aguarda aprovação.**


---

## 6. Especificação técnica proposta — tipos e chaves

A versão 1.2 **não é o SQL executável**. Antes de converter as definições em
`database/schema.sql`, confirmar se o banco instalado no Laragon é **MySQL 8.x
ou MariaDB** e sua versão específica, pois a sintaxe de coluna gerada, `JSON`,
`CHECK` e índices pode variar.

### Convenções comuns

- Tabelas em **InnoDB**, collation `utf8mb4_unicode_ci` ou equivalente compatível.
- Identificadores: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`.
- Timestamps de eventos em **UTC** no banco; conversão ao fuso do usuário
  no PHP. Preferir `DATETIME(6)` quando precisão subsegundo for importante.
- Campos `criado_em`/`atualizado_em`: `DATETIME`, atualização controlada pelo banco
  ou aplicativo de forma consistente.
- Valores de carga: `DECIMAL(7,2)`; não usar `FLOAT` para pesos ou volume.
- Valores de séries e repetições: `SMALLINT UNSIGNED`, positivos quando realizados;
  valores ainda não executados podem ser `NULL` conforme estado.
- Estados e categorias: `VARCHAR` com `CHECK` válido na versão instalada,
  ou `ENUM` estável. Não permitir estados desconhecidos.
- Nomes de usuário, exercício e ficha devem passar por validação de comprimento,
  normalização e escape de saída.
- Dados sensíveis ficam fora do diretório servido pelo Apache.

### Matriz de chaves estrangeiras e políticas de exclusão

| Origem | Campo | Referência | Exclusão proposta | Justificativa |
|---|---|---|---|---|
| `recuperacao_senhas` | `usuario_id` | `usuarios.id` | `CASCADE` | Remove tokens de conta removida pelo fluxo autorizado |
| `fichas` | `usuario_id` | `usuarios.id` | `RESTRICT` | Não apagar fichas e histórico acidentalmente |
| `exercicios` | `usuario_id` (nullable) | `usuarios.id` | `RESTRICT` | Preservar exercícios personalizados |
| `ficha_exercicios` | `ficha_id` | `fichas.id` | `CASCADE` | Excluir associação quando ficha sem histórico é excluída |
| `ficha_exercicios` | `exercicio_id` | `exercicios.id` | `RESTRICT` | Preferir arquivar exercícios referenciados |
| `sessoes` | `usuario_id` | `usuarios.id` | `RESTRICT` | Preservar sessões finalizadas |
| `sessoes` | `ficha_id` | `fichas.id` | `RESTRICT` | Impede exclusão de ficha com histórico ou sessão ativa |
| `sessao_exercicios` | `sessao_id` | `sessoes.id` | `CASCADE` | Limpeza estrita ao descartar uma sessão em andamento |
| `sessao_exercicios` | `exercicio_id` | `exercicios.id` | `RESTRICT` | Preservar vínculos históricos |
| `series_realizadas` | `sessao_exercicio_id` | `sessao_exercicios.id` | `CASCADE` | Limpeza das séries de sessão descartada; remoção histórica apenas em transação e com auditoria |
| `historico_alteracoes` | `sessao_id` | `sessoes.id` | `RESTRICT` | Preservar auditoria de sessão finalizada |
| `historico_alteracoes` | `usuario_id` | `usuarios.id` | `RESTRICT` | Preservar autoria da correção |
| `metas` | `usuario_id` | `usuarios.id` | `RESTRICT` | Proteger metas |
| `metas` | `exercicio_id` (nullable) | `exercicios.id` | `RESTRICT` | Não perder referência da meta de carga |
| `usuario_conquistas` | `usuario_id` | `usuarios.id` | `RESTRICT` | Preservar conquistas |
| `usuario_conquistas` | `conquista_id` | `conquistas.id` | `RESTRICT` | Preservar definições de conquista |
| `usuario_conquistas` | `exercicio_id` (nullable) | `exercicios.id` | `RESTRICT` | Preservar recordes |

**Observação de integridade:** `CASCADE` é uma política de chave
estrangeira, não uma autorização de negócio. O PHP deve verificar
**proprietário e estado** da sessão antes de excluí-la. O banco, sozinho,
não distingue uma exclusão legitimamente descartada de uma tentativa
indevida de remover uma sessão finalizada. Portanto, acesso ao banco e
permissões de escrita devem permanecer controlados.

**Correções históricas:** mesmo que o relacionamento das séries use
`CASCADE`, o endpoint de edição histórica deverá validar a sessão,
auditar valores anteriores e novos na mesma transação e verificar que
resta pelo menos uma série concluída. A FK não substitui essa verificação.

### Unicidade e índices recomendados

| Tabela | Índice / restrição |
|---|---|
| `usuarios` | `UNIQUE(email)` com collation comparativa apropriada |
| `recuperacao_senhas` | `INDEX(usuario_id, expira_em)`; opcional `UNIQUE(token_hash)` |
| `fichas` | `INDEX(usuario_id, status)` |
| `exercicios` | `INDEX(usuario_id, status, grupo_muscular)` e índice de busca por nome conforme volume |
| `ficha_exercicios` | `UNIQUE(ficha_id, exercicio_id)`; `INDEX(ficha_id, ordem)` |
| `sessoes` | `INDEX(usuario_id, status, iniciada_em)` e unicidade de sessão ativa detalhada abaixo |
| `sessao_exercicios` | `INDEX(sessao_id, ordem)` e `INDEX(exercicio_id)` |
| `series_realizadas` | `UNIQUE(sessao_exercicio_id, numero_serie)` |
| `historico_alteracoes` | `INDEX(sessao_id, alterado_em)` |
| `metas` | `INDEX(usuario_id, status_base, prazo_em)`; `INDEX(exercicio_id)` |
| `conquistas` | `UNIQUE(codigo)` |
| `usuario_conquistas` | `UNIQUE(usuario_id, chave_ocorrencia)` e `INDEX(usuario_id, valida)` |

**Uma sessão ativa por usuário:** índices únicos comuns em
`(usuario_id, status)` bloqueariam várias sessões finalizadas do mesmo
usuário, portanto **não devem ser usados**. Proposta: coluna gerada
`usuario_ativo_id = CASE WHEN status = 'em_andamento' THEN usuario_id ELSE NULL END`
com índice `UNIQUE(usuario_ativo_id)`; índices únicos permitem diversos
`NULL`, mas apenas um registro ativo por usuário. Validar sintaxe e
suporte da edição específica do MySQL/MariaDB; complementar com
transações e tratamento de colisão na criação.

**Ocorrências de conquista:** a `chave_ocorrencia` deve conter
identidade lógica estável (tipo, exercício, período ou marco). Uma nova
carga máxima na mesma semana **atualiza** a ocorrência semanal; não
produz um registro duplicado nem novos avisos para o mesmo evento.

### Restrições que exigem lógica de aplicação ou gatilho específico

1. Ficha e exercício associados devem ser do mesmo usuário, ou o
   exercício pertencer ao catálogo público (`exercicios.usuario_id IS NULL`).
2. Uma sessão pertence ao mesmo usuário da ficha original.
3. Uma sessão finalizada precisa manter pelo menos uma série concluída,
   inclusive após edição histórica.
4. `finalizada_em` não pode anteceder `iniciada_em`; duração deve ser
   recalculada em segundos quando horários forem corrigidos.
5. Cada série concluída precisa registrar repetições coerentes com
   validação aprovada e não ter carga negativa.
6. Metas do tipo `carga` exigem `exercicio_id`; metas de frequência e
   quantidade devem exigir alvo inteiro e positivo. Prazo é opcional.
7. Exercícios gerais (`usuario_id IS NULL`) não podem ser editados pelos
   usuários comuns.
8. Apenas sessão **em andamento** pode ser fisicamente descartada pelo
   fluxo de descarte; sessão finalizada permanece preservada.

Algumas dessas verificações abrangem várias tabelas e não são
expressáveis apenas por `CHECK`. Elas precisam ser garantidas com
**transações e validação no backend**, e em casos críticos, eventualmente
gatilhos ou política mais restrita de permissões do banco.

## 7. Pontos que merecem revisão antes da aprovação do modelo

- **Versão exata do banco:** o Laragon pode utilizar MySQL ou MariaDB.
  Confirmar produto e versão para escrever o esquema executável sem
  incompatibilidades de sintaxe.
- **Exclusão de conta:** ainda não foi definido um fluxo de exclusão
  completa de conta; as relações estão conservadoras (`RESTRICT`) para
  impedir perda acidental de dados. Esse recurso, se desejado, exigirá
  decisão própria sobre retenção e remoção.
- **Estados históricos de exercícios:** após correções em uma sessão,
  preservar série concluída e estado de exercício coerentes; a interface
  não deve marcar séries pendentes como realizadas automaticamente.
- **Dados de progresso das metas:** derivar progresso de sessões
  finalizadas e guardar apenas o estado/identidade necessário para
  conquistas e avisos. Assim, correções históricas podem ser refletidas
  sem divergência entre valores cacheados e reais.

## 8. Controle desta revisão

| Item | Estado |
|---|---|
| Descarte físico de sessão em andamento | **DECISÃO CONFIRMADA** |
| 12 tabelas, tipos, FKs, índices e unicidades | **PROPOSTA PARA APROVAÇÃO** |
| 189 RN e 49 CT | **APROVADOS, inalterados** |
| 46 RF e 25 RNF | **APROVADOS, inalterados** |
| RF/RNF adicionais de Metas | **PENDENTES** |
| `database/schema.sql` | **AINDA NÃO IMPLEMENTADO** |


---

## 9. Homologação parcial do esquema no MySQL 8.4.3 — 13/11/2026

A modelagem de 12 tabelas foi **aprovada pelo usuário para testes**. Os testes
relatados no phpMyAdmin do banco separado `gymprogress_teste` confirmaram:

- 12 tabelas criadas e listadas.
- 12 chaves primárias, 17 chaves estrangeiras, 20 restrições `CHECK` e
  8 restrições `UNIQUE` cadastradas (57 ao todo).
- Coluna gerada `usuario_ativo_id` e índice único
  `uq_sessao_ativa_usuario` encontrados; segunda sessão simultânea
  rejeitada pelo erro `#1062`.
- Tentativa de ficha de usuário inexistente rejeitada pelo erro `#1452`,
  sem registro residual.
- Chaves `ON DELETE CASCADE` em `sessao_exercicios` e
  `series_realizadas`, e `RESTRICT` para o exercício da biblioteca.
- Teste transacional de cascata retornou sessões=0, exercícios da sessão=0,
  séries=0 e exercício da biblioteca preservado=1; seguido de `ROLLBACK`.

**Limites da aprovação:** não foram testados exaustivamente todos os `CHECK`,
todas as FKs, nem as regras de autorização e transição de estado. Uma sessão
finalizada não é protegida contra `DELETE` direto apenas pelo esquema;
o backend PHP deverá exigir propriedade e `status='em_andamento'` em
transação, além de validações e auditoria. Nenhuma execução deste SQL
pelo assistente foi realizada.

**Uso seguro de `database/schema.sql`:** selecionar manualmente um banco
**novo e vazio** no phpMyAdmin, conferir o nome da base com `SELECT DATABASE()`
e só então importar. O arquivo não contém `CREATE DATABASE`, `USE`,
`DROP`, `TRUNCATE` ou `DELETE`. Não reaplicar em banco existente na
expectativa de alterar colunas: migrações futuras serão versionadas à parte.

Os documentos `docs/regras-negocio.md` e `docs/requisitos.md` foram
**preservados sem alterações**. O aditivo de RF/RNF para Metas segue pendente.

## Decisão de ambiente de desenvolvimento — novembro/2026

Até a conclusão dos testes de todos os módulos, o projeto usará **exclusivamente** `gymprogress_teste`. O banco definitivo `gymprogress` **não deve ser criado agora**. O `database/schema.sql` não contém `CREATE DATABASE` nem `USE`; para instalar uma vez em base nova e vazia, selecionar manualmente `gymprogress_teste` no phpMyAdmin antes da importação e confirmar com `SELECT DATABASE()`. Bancos já criados não devem receber a importação novamente como substituto de migração. Depois da validação completa, a versão definitiva terá base própria, com backup e migração seletiva de dados. Esta decisão não modifica RN, CT, RF ou RNF aprovados.


## Atualização do schema.sql

A partir desta revisão, `database/schema.sql` cria automaticamente o banco `gymprogress_teste` e executa `USE gymprogress_teste`, facilitando a configuração do projeto em dispositivos diferentes durante a fase de desenvolvimento e testes.
