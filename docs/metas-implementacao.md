# GymProgress — Metas e Conquistas v1

**Estado:** implementado; requer testes no Laragon com MySQL 8.4.3.  
**Banco:** `gymprogress_teste`. **Sem migrações** — reutiliza as tabelas `metas`, `conquistas` e `usuario_conquistas` já existentes.

## Recursos

- Perfil → Metas: listar metas, progresso calculado, estados Ativa / Alcançada / Prazo encerrado / Arquivada.
- Criar e editar metas de carga por exercício, frequência semanal ou sessões acumuladas; prazo opcional.
- Quando o prazo se encerra sem cumprimento: mantém o progresso observado até o prazo, permite prorrogar para data futura ou arquivar.
- Reativar metas arquivadas; não há exclusão física de metas.
- Conquistas por primeiro marco de 1, 5, 10, 25, 50, 100 sessões; 2–7 sessões na mesma semana; recorde pessoal e recorde semanal por exercício. Recordes exigem uma carga anterior válida para ser superada (não contam o primeiro registro isolado como superação).
- Usa **somente séries concluídas de sessões finalizadas**, agrupamento semanal por segunda-feira no fuso do usuário, eventos ordenados pela data de finalização.
- Recalcula recordes e metas após **finalizar Treinar** ou **corrigir Histórico**, dentro da mesma transação. Também reconcilia ao entrar em Dashboard/Metas para cobrir registros existentes.
- Chaves estáveis de ocorrência evitam duplicação; ocorrências invalidáveis permanecem guardadas no banco para auditoria lógica.
- Avisos não vistos aparecem discretamente no Dashboard; abrir ou dispensar só modifica o aviso do usuário autenticado. Avisos já dispensados não reaparecem no recálculo.
- Navegação existente preservada; nenhuma alteração nos documentos de RN/RF/RNF.

## Limites atuais

- Conquistas são automáticas; marcos predefinidos de frequência e sessões podem ser ampliados futuramente.
- `atingida_em` de meta é calculada por data de finalização; correções de data podem mudar seu estado.
- O progresso de uma meta de frequência mostra a semana corrente, ou a melhor semana dentro do período se o prazo já encerrou. Uma meta de frequência é alcançada quando qualquer semana válida dentro do período atingiu o alvo.
- Exibir o primeiro aviso histórico após atualizar é esperado, pois o sistema reconcilia conquistas que já existiam nos dados do Treinar.
- O fuso do usuário rege os dias/semanas; o banco armazena eventos em UTC. Mudança do fuso pode reclassificar recordes de fronteira de semana, conforme a configuração atual.
- Somente páginas e código foram verificados em ambiente local; a execução com o MySQL do usuário precisa ser validada.

## Testes manuais

1. Abrir Perfil → Metas e visualizar a lista sem erros.
2. Criar uma meta de carga vinculada a exercício próprio ou geral, com prazo em branco.
3. Criar meta de treinos acumulados e uma de frequência semanal.
4. Finalizar treino com série concluída; confirmar atualização da carga/frequência e conquistas.
5. Corrigir carga e data no Histórico; confirmar recálculo e ausência de conquistas duplicadas.
6. Criar meta com prazo de teste, verificar estado Prazo encerrado e progresso mantido; prorrogar.
7. Arquivar e reativar meta, preservando progresso.
8. Confirmar que exercício de outra conta não pode ser usado em metas.
9. Testar banner de conquista no Dashboard, abrir Metas e dispensar aviso sem apagar a conquista.
10. Confirmar que treinos descartados e séries pendentes não influenciam metas ou conquistas.
11. Refazer as páginas após login/logout para confirmar proteção da conta.
12. Confirmar que o Histórico, Biblioteca, Fichas, Treinar e navbar seguem funcionando.

## Commit sugerido

`feat: implement goals and workout achievements`
