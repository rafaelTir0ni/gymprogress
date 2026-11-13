# GymProgress — Modelagem de Banco de Dados

> **Status: PROPOSTA PARA REVISÃO — NÃO APROVADA.**
> Não há esquema SQL definitivo neste repositório.

## Entidades previstas

| Entidade / tabela candidata | Responsabilidade |
|---|---|
| `usuarios` | Autenticação e dados da conta |
| `fichas` | Fichas do usuário e estados ativa/arquivada |
| `exercicios` | Catálogo geral e exercícios personalizados com proprietário |
| `ficha_exercicios` | Ordem e prescrição (séries, repetições fixas/intervalos) |
| `sessoes` | Datas, status, duração e cópia da ficha no momento do treino |
| `sessao_exercicios` | Exercícios específicos de cada sessão e estados de conclusão |
| `series_realizadas` | Séries planejadas/executadas, carga, repetições e confirmação |
| `historico_alteracoes` | Auditoria de edição abrangente de sessões finalizadas |
| `recuperacao_senhas` | Tokens temporários e de uso único |
| `metas` | Metas pessoais, prazos opcionais e estados |
| `conquistas` | Definições de conquistas automáticas |
| `usuario_conquistas` | Conquistas válidas e estado dos avisos para cada usuário |

## Restrições que o esquema deverá atender

- Dados de cada usuário isolados e autorizados pelo backend.
- No máximo uma sessão ativa por usuário.
- Único par `(ficha_id, exercicio_id)` em cada ficha.
- Não apagar o histórico ao editar ou arquivar fichas e exercícios.
- Finalização e correção histórica devem preservar pelo menos uma série concluída.
- Edições históricas auditadas em transações e capazes de recalcular indicadores, recordes e metas.
- Conquistas processadas idempotentemente, sem registros duplicados.
- Prazos de metas opcionais; estado `Prazo encerrado` permite prorrogação ou arquivamento.
- Requisitos de índices, chaves, snapshots, datas e tipos serão definidos na modelagem definitiva.

**Próxima etapa:** aprovar o diagrama entidade-relacionamento, detalhar colunas/relacionamentos e então preencher `database/schema.sql`.
