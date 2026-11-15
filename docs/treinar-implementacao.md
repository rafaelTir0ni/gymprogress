# Treinar v1 — GymProgress

**Status:** implementado no código; aguardando testes funcionais no Laragon/MySQL 8.4.3. **Não homologado**.

## Fluxos implementados

1. Lista fichas ativas com exercícios, bloqueia início quando já existe sessão ativa.
2. Inicia sessão em transação, registra data/hora UTC, snapshots da ficha/exercícios e séries planejadas.
3. Retoma sessão em andamento após fechar a página (dados persistidos no MySQL).
4. Navega pelos círculos numerados, salva/corrige repetições e carga, conclui ou desmarca séries, adiciona séries extras.
5. Classifica exercício como pendente, parcial, concluído automático ou concluído antecipadamente; permite reabrir.
6. Mostra gráfico semanal com máxima carga de séries concluídas em sessões finalizadas, sem exigir encerrar treino.
7. Exibe resumo antes de finalizar, com contagem, duração estimada e volume de carga externa, permite voltar.
8. Finalização protegida por transação e bloqueio do registro, exigindo pelo menos uma série concluída; grava duração e status.
9. Descarte exige confirmação explícita (DESCARTAR), verifica proprietário e status; apaga apenas sessão ativa e dependências por CASCADE.

## O que ainda falta

- Testes reais no Laragon e no celular via LAN; não foi possível executar MySQL do computador do usuário.
- Histórico completo, correções auditadas e recálculo de metas/conquistas pertencem a módulos futuros.
- Uma edição do nome ou status da ficha não altera snapshots de sessões já criadas.
- O estado de exercícios reabertos passa a refletir as séries já concluídas; uma série antes concluída pode ser desmarcada na sessão ativa. O exercício antecipadamente concluído exige reabertura explícita antes de editar séries.
- Sem cache offline de gravações; PWA offline ficará para fase posterior.

## Testes manuais recomendados

- Iniciar ficha ativa com exercícios; conferir início e retomada.
- Tentar segunda sessão na mesma conta: impedir.
- Marcar série, fechar navegador, retomar: persistência.
- Adicionar série extra e salvar carga fracionada; não aceitar carga negativa e repetições inválidas.
- Testar exercícios pendente/parcial/antecipado/reaberto/concluído.
- Exibir gráfico vazio e gráfico com registro após finalizar.
- Com zero séries concluídas: impedir finalização; permitir continuar ou descartar.
- Conferir resumo e volume com carga opcional.
- Finalizar e tentar repetir POST: não duplicar finalização.
- Iniciar outro treino após finalização.
- Descartar sessão em andamento e conferir cascata no MySQL, sem excluir ficha/biblioteca.

## Segurança

Todas as mutações exigem sessão autenticada + CSRF e checagem de propriedade; operações críticas têm transação. O banco permanece `gymprogress_teste` durante desenvolvimento. Não importar schema novamente em banco populado.
