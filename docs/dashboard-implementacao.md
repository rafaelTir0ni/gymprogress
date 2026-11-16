# GymProgress — Dashboard v1

**Status:** implementado em código e sintaxe validada; aguardando testes funcionais no Laragon.

## Escopo

Segue RN-D01 a RN-D12 e CT-D01 a CT-D05, sem modificar regras aprovadas.

- Página inicial protegida após login; boas-vindas com nome do usuário.
- Botão **Iniciar treino** navega para `?route=treinar` (seleção de ficha).
- Exibe **Continuar treino** somente se existir sessão `em_andamento`, apontando para seu próprio ID.
- Contagem de sessões `finalizada` nesta semana e neste mês, pertencentes ao usuário autenticado.
- Semana começa segunda-feira; a atribuição ao período é feita pelo horário `finalizada_em`, não pelo início da sessão.
- O PHP determina limites de semana/mês no fuso cadastrado em `usuarios.timezone`, convertendo-os para UTC para as consultas SQL. Timestamps do banco estão em UTC.
- Mostra último treino finalizado pelo horário de finalização, duração total e número de séries concluídas, com link para os detalhes do Histórico.
- Exibe orientação quando não há treinos finalizados.
- Barra inferior: Início / Fichas / Treinar / Biblioteca / Perfil (posição Perfil desativada até o módulo ser criado).
- Logout protegido por POST com CSRF. Não há seção redundante de acessos rápidos.

## Consultas e segurança

`DashboardController::index()` utiliza consultas preparadas do PDO. A identificação do usuário vem da sessão autenticada, sem parâmetro fornecido pelo navegador. Executa consulta de fuso, contagem da semana e mês em uma consulta agregada, consulta de último treino e consulta de sessão ativa. Alterações no Histórico afetam os indicadores após recarregar a página; não há cache de estatísticas.

Não há migração do banco. O Dashboard usa as tabelas já existentes: `usuarios`, `sessoes`, `sessao_exercicios`, `series_realizadas`.

### Ainda não incluído

- Avisos de novas conquistas: dependem do módulo Metas/Conquistas.
- Perfil editável: não implementado.
- Testes reais com o MySQL local: precisam ser executados pelo usuário no Laragon.

## Testes de aceitação no Laragon

1. Fazer login e verificar a abertura do novo Dashboard.
2. Confirmar a mensagem de boas-vindas e o botão "Iniciar treino" abrindo Treinar.
3. Conferir indicadores com nenhuma sessão finalizada (0 na semana e 0 no mês).
4. Finalizar uma sessão e confirmar a atualização dos contadores ao retornar ao Dashboard.
5. Verificar a última sessão, duração, séries concluídas e link para os detalhes no Histórico.
6. Iniciar outro treino e confirmar "Continuar treino"; a sessão ativa não deve ser contada.
7. Descartar a sessão ativa e confirmar o desaparecimento do aviso, sem mudar os contadores.
8. Corrigir a data de finalização no Histórico e conferir se as contagens acompanham o período correspondente.
9. Testar os links da navegação inferior em tela de celular ou modo responsivo.
10. Sair da conta e confirmar o redirecionamento ao Login ao tentar abrir `?route=dashboard`.

**Observação:** caso uma sessão seja finalizada próximo da meia-noite de domingo ou da virada do mês, conferir o fuso cadastrado em `usuarios.timezone` e a conversão UTC. A regra de agrupamento deste Dashboard usa a data da **finalização**.
