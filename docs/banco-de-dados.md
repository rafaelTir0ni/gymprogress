# GymProgress — Modelagem do banco de dados

> **Versão:** 1.1 — **PROPOSTA PARA REVISÃO (não aprovada)**  
> **Tecnologia prevista:** MySQL/MariaDB (Laragon), PHP com PDO, `utf8mb4`, InnoDB.  
> **Documentos preservados:** 189 regras de negócio e 49 cuidados técnicos aprovados; 46 requisitos funcionais e 25 não funcionais aprovados. Requisitos adicionais de Metas ainda pendentes de homologação.  
> **Não é um esquema SQL executável.** O futuro `database/schema.sql` será gerado após validação desta modelagem.

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
