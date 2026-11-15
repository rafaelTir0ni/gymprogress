# Histórico v1 — implementação e validação

## Implementado

- Rotas: `historico`, `historico-detalhes`, `historico-editar`, `historico-salvar` (POST + CSRF).
- Dados acessíveis somente ao usuário autenticado; apenas sessões finalizadas.
- Filtros por período (datas UTC) e ficha, ordenação decrescente, até 200 sessões.
- Detalhes de snapshots e séries, marcadores pendente/parcial/concluído/antecipado.
- Edição confirmada de nome da sessão, horários UTC, observações, exercícios e suas séries, prescrições e estados.
- Auditoria `historico_alteracoes` com JSON antes/depois, em transação única.
- Pelo menos uma série concluída após correção; nenhuma rota apaga sessão finalizada inteira.
- Gráfico semanal do Treinar consulta diretamente séries concluídas de sessões finalizadas, portanto reflete mudanças no Histórico.

## Atenção

- Não houve alteração no esquema das 12 tabelas. Mantenha `DB_DATABASE=gymprogress_teste`; não reimporte `schema.sql`.
- As datas exibidas/editadas são **UTC**, conforme especificado nas telas; no futuro a UX poderá converter pelo fuso do usuário sem modificar a persistência.
- Ao editar uma sessão, as séries são reconstruídas na mesma transação; a auditoria retém os valores anteriores e novos.
- Metas e conquistas ainda não estão implementadas; o recálculo de progresso e notificações será integrado ao desenvolver esses módulos.
- A verificação de sintaxe PHP/JS não substitui os testes funcionais reais no Laragon.

## Roteiro de testes

1. Faça login e finalize um treino com duas séries concluídas.
2. Abra Histórico e confira a sessão no topo.
3. Filtre por data e ficha, incluindo um filtro sem resultados.
4. Abra detalhes e confira cargas, repetições, status e duração.
5. Edite nome, observações, horários; confirme CORRIGIR e verifique recálculo da duração.
6. Edite carga e repetições e confira atualização do gráfico semanal do Treinar.
7. Adicione e remova séries e exercícios de uma sessão (mantendo ao menos uma série concluída).
8. Tente salvar a correção com zero séries concluídas; deve ser rejeitada.
9. Confira a listagem de auditoria nos detalhes.
10. Verifique que não há opção de excluir completamente uma sessão finalizada.
11. Faça logout e tente abrir diretamente Histórico/detalhes/edição; deve redirecionar ao login.
12. Verifique que suas fichas arquivadas continuam aparecendo no Histórico.
