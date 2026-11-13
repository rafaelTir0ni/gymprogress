# GymProgress — Especificação de Requisitos

> **Versão:** 1.0 — **APROVADA DEFINITIVAMENTE**
>
> **Status:** **APROVADO** — 46 requisitos funcionais, 25 requisitos não funcionais e respectivos critérios de aceitação. As 153 regras de negócio e os 42 cuidados técnicos seguem aprovados e inalterados em `regras-negocio.md`.

## 1. Objetivo e escopo

Esta especificação organiza requisitos verificáveis do GymProgress, aplicativo mobile-first para organizar fichas, executar treinos e consultar/editar o histórico. As regras de negócio aprovadas prevalecem em caso de conflito interpretativo.

**Entregas:** MVP 1.0 online; PWA instalável (1.1); sincronização offline (2.0). A definição por etapas não altera o escopo já aprovado para o produto.

## 2. Requisitos funcionais (RF)

| Código | Requisito | Entrega |
|---|---|---|
| RF-01 | Permitir cadastro de usuários com nome, e-mail e senha. | MVP 1.0 |
| RF-02 | Permitir autenticação por e-mail e senha. | MVP 1.0 |
| RF-03 | Permitir logout com encerramento da sessão autenticada. | MVP 1.0 |
| RF-04 | Permitir solicitar recuperação e redefinição de senha por e-mail. | MVP 1.0 |
| RF-05 | Após o cadastro, redirecionar ao Login, sem autenticação automática. | MVP 1.0 |
| RF-06 | Permitir criar fichas, inclusive sem exercícios. | MVP 1.0 |
| RF-07 | Permitir visualizar, pesquisar e filtrar fichas por situação. | MVP 1.0 |
| RF-08 | Permitir editar nome, descrição e grupo muscular das fichas. | MVP 1.0 |
| RF-09 | Permitir associar, remover e reordenar exercícios nas fichas. | MVP 1.0 |
| RF-10 | Permitir configurar séries e repetições planejadas como valor fixo ou intervalo. | MVP 1.0 |
| RF-11 | Permitir arquivar e reativar fichas. | MVP 1.0 |
| RF-12 | Permitir excluir fichas sem histórico e sem sessão ativa, mediante confirmação. | MVP 1.0 |
| RF-13 | Exibir exercícios gerais e os exercícios personalizados do usuário. | MVP 1.0 |
| RF-14 | Permitir pesquisar e filtrar exercícios por categoria muscular. | MVP 1.0 |
| RF-15 | Exibir descrições e mídias demonstrativas disponíveis. | MVP 1.0 |
| RF-16 | Permitir cadastrar e editar exercícios personalizados do usuário. | MVP 1.0 |
| RF-17 | Permitir arquivar e reativar exercícios personalizados preservando vínculos históricos. | MVP 1.0 |
| RF-18 | Permitir selecionar uma ficha ativa e associar a ela exercício não duplicado, configurando séries e repetições. | MVP 1.0 |
| RF-19 | Permitir selecionar ficha ativa com exercícios para iniciar sessão. | MVP 1.0 |
| RF-20 | Permitir retomar sessão em andamento, respeitando uma sessão ativa por usuário. | MVP 1.0 |
| RF-21 | Permitir navegar livremente entre exercícios da sessão. | MVP 1.0 |
| RF-22 | Permitir registrar individualmente séries concluídas, cargas e repetições. | MVP 1.0 |
| RF-23 | Permitir editar e adicionar séries durante a sessão ativa. | MVP 1.0 |
| RF-24 | Permitir concluir exercícios automaticamente ou antecipadamente, preservando séries realmente realizadas. | MVP 1.0 |
| RF-25 | Permitir reabrir exercícios concluídos enquanto a sessão estiver ativa. | MVP 1.0 |
| RF-26 | Exibir a duração da sessão como tempo corrido desde o início, incluindo pausas. | MVP 1.0 |
| RF-27 | Permitir consultar por exercício o gráfico de maior carga semanal de séries concluídas em sessões finalizadas. | MVP 1.0 |
| RF-28 | Permitir finalizar a sessão com pelo menos uma série concluída, mediante confirmação e resumo. | MVP 1.0 |
| RF-29 | Permitir descartar sessão sem séries concluídas ou outra sessão ativa, mediante confirmação, sem incluí-la no Histórico principal. | MVP 1.0 |
| RF-30 | Exibir quantidade de sessões finalizadas na semana e no mês. | MVP 1.0 |
| RF-31 | Exibir a sessão finalizada mais recente. | MVP 1.0 |
| RF-32 | Oferecer acesso à aba Treinar e retomada da sessão ativa. | MVP 1.0 |
| RF-33 | Disponibilizar atalho para o Histórico. | MVP 1.0 |
| RF-34 | Permitir consultar e alterar nome e e-mail do próprio usuário. | MVP 1.0 |
| RF-35 | Permitir alterar senha mediante conferência da senha atual. | MVP 1.0 |
| RF-36 | Permitir consultar e filtrar sessões finalizadas. | MVP 1.0 |
| RF-37 | Permitir consultar detalhes, exercícios, séries e situações de conclusão de sessão finalizada. | MVP 1.0 |
| RF-38 | Permitir editar dados pertinentes de sessão finalizada mediante confirmação, sem permitir que ela fique sem séries concluídas. | MVP 1.0 |
| RF-39 | Permitir adicionar, editar e remover exercícios e séries em sessão finalizada, validando a existência de pelo menos uma série concluída após a correção. | MVP 1.0 |
| RF-40 | Atualizar gráficos e indicadores afetados pelas correções confirmadas. | MVP 1.0 |
| RF-41 | Registrar auditoria das correções históricas com dados anteriores e novos. | MVP 1.0 |
| RF-42 | Disponibilizar o GymProgress em navegadores compatíveis, sem instalação obrigatória. | MVP 1.0 |
| RF-43 | Oferecer instalação como PWA em dispositivos compatíveis na etapa de instalação. | PWA 1.1 |
| RF-44 | Informar indisponibilidade de conexão e distinguir operações não confirmadas. | MVP 1.0 |
| RF-45 | Permitir registro local de operações offline em versão posterior ao MVP. | PWA 2.0 |
| RF-46 | Sincronizar operações locais com o servidor em versão posterior ao MVP. | PWA 2.0 |

### Índice por módulo

- **Autenticação e Cadastro:** RF-01 a RF-05
- **Fichas de Treino:** RF-06 a RF-12
- **Biblioteca de Exercícios:** RF-13 a RF-18
- **Execução de Treinos:** RF-19 a RF-29
- **Dashboard:** RF-30 a RF-33
- **Perfil e Histórico:** RF-34 a RF-41
- **PWA e Aplicativo:** RF-42 a RF-46

## 3. Requisitos não funcionais (RNF)

| Código | Categoria | Requisito | Como verificar |
|---|---|---|---|
| RNF-01 | Responsividade | A interface deverá funcionar em smartphones, tablets e desktops, priorizando celulares. | Sem rolagem horizontal involuntária a partir de 320 px nos fluxos principais. |
| RNF-02 | Usabilidade | Controles e navegação deverão ser adequados ao toque e consistentes nas telas. | Verificação manual de fluxos em celular e desktop. |
| RNF-03 | Acessibilidade | Controles deverão possuir rótulos, foco visível, suporte a teclado e contraste apropriado. | Verificar critérios WCAG 2.2 AA aplicáveis; registrar eventuais exceções. |
| RNF-04 | Segurança | Senhas deverão ser armazenadas exclusivamente em hash seguro. | Inspeção do banco e testes de login. |
| RNF-05 | Segurança | O ambiente publicado deverá utilizar HTTPS. | Testar deploy público com HTTPS e sem conteúdo misto. |
| RNF-06 | Segurança | O servidor deverá autenticar e autorizar acesso a dados privados em todas as operações. | Testar usuário não autenticado e acesso cruzado entre contas. |
| RNF-07 | Segurança | Entradas e saídas deverão ter validação/escape, consultas preparadas e proteção CSRF. | Testes de entradas inválidas, XSS, SQL injection e CSRF. |
| RNF-08 | Segurança | As sessões deverão usar cookies seguros e renovar o ID após login. | Inspeção dos atributos de sessão e teste de fixação. |
| RNF-09 | Segurança | Tentativas excessivas de autenticação deverão ser limitadas. | Teste controlado de tentativas sucessivas. |
| RNF-10 | Integridade | Alterações de fichas e exercícios não deverão modificar acidentalmente sessões já registradas. | Testes de edição com sessões em andamento e finalizadas. |
| RNF-11 | Integridade | Operações críticas em múltiplos registros deverão ocorrer em transações. | Testes com falha intencional para verificar rollback. |
| RNF-12 | Integridade | Finalizações repetidas e exercícios duplicados na mesma ficha deverão ser prevenidos. | Simular envios repetidos/concomitantes. |
| RNF-13 | Persistência | Séries confirmadas pelo backend deverão permanecer disponíveis após fechar e reabrir o aplicativo. | Teste de retomada e consulta após reinicialização. |
| RNF-14 | Auditoria | Correções históricas deverão registrar autor, data/hora e valores anteriores/novos. | Conferir auditoria em todas as alterações de teste. |
| RNF-15 | Consistência | Gráficos e indicadores deverão refletir os registros válidos de sessões finalizadas, inclusive após correções autorizadas. | Conferência de dados após edição histórica. |
| RNF-16 | Desempenho | As páginas principais deverão atingir meta inicial de carregamento de até 3 segundos, em condições de teste documentadas. | Medição em ambiente, rede, dispositivo e base de teste padronizados. |
| RNF-17 | Manutenibilidade | A aplicação deverá separar responsabilidades nas camadas da arquitetura MVC simplificada. | Revisão de organização de controllers, models, views e services. |
| RNF-18 | Manutenibilidade | O código deverá seguir nomenclatura e padrões consistentes. | Revisão técnica e checklist de qualidade. |
| RNF-19 | Configuração | Credenciais, tokens e segredos não poderão ser publicados no repositório. | Revisão do Git e variáveis de ambiente. |
| RNF-20 | PWA | A etapa PWA instalável deverá disponibilizar manifest, ícones e service worker apropriados. | Teste de instalação em dispositivos compatíveis. |
| RNF-21 | PWA | O cache não poderá expor credenciais nem respostas privadas sensíveis. | Inspeção do cache e testes com logout/troca de conta. |
| RNF-22 | Conectividade | Operações sem confirmação do servidor deverão ser apresentadas como não salvas no MVP online. | Desativar conexão durante operações. |
| RNF-23 | Compatibilidade | Fluxos principais deverão funcionar nos navegadores suportados definidos para os testes. | Matriz de testes mobile e desktop. |
| RNF-24 | Qualidade | Todas as telas deverão receber testes manuais e fluxos críticos deverão possuir testes automatizados. | Checklist manual e testes de autenticação, permissões, sessões, histórico, integridade. |
| RNF-25 | Recuperação | O banco em produção deverá possuir estratégia documentada de backup e restauração. | Teste de restauração em ambiente de desenvolvimento/homologação. |

## 4. Critérios de aceitação complementares

- **Duração histórica:** ao corrigir data ou horários de uma sessão, recalcular automaticamente sua duração como diferença entre início e fim, incluindo pausas, sem permitir intervalo negativo ou inconsistência de datas.
- **Desempenho:** meta inicial de até 3 segundos para carregar páginas principais, medida com ferramenta, dispositivo, rede e volume de dados registrados em relatório de testes. Essa é uma meta de planejamento, não desempenho já comprovado.
- **Estratégia de testes:** testes manuais em todas as telas e automação prioritária de autenticação, autorização, associações de fichas, execução/finalização, edição histórica e auditoria.
- **Histórico:** edição abrangente de sessões finalizadas com autorização, confirmação, transação, auditoria e recálculo de indicadores; não permitir exclusão integral da sessão pela interface. **Impedir que a correção deixe a sessão finalizada sem ao menos uma série concluída**; exibir mensagem clara e preservar os dados anteriores caso a validação falhe.
- **Fichas:** jamais alterar automaticamente as configurações de uma sessão em andamento ou já finalizada após editar uma ficha.
- **Conectividade:** no MVP online, o usuário deve ser avisado quando a confirmação do salvamento não chegar ao servidor; modo offline completo é etapa futura.

## 5. Rastreabilidade de alto nível

| Área | RF | Principais regras aprovadas relacionadas |
|---|---|---|
| Autenticação | RF-01 a RF-05 | RN-A01 a RN-A16 |
| Fichas | RF-06 a RF-12 | RN-F01 a RN-F40 |
| Biblioteca | RF-13 a RF-18 | RN-B01 a RN-B15 |
| Treinar | RF-19 a RF-29 | RN-T01 a RN-T35 |
| Dashboard | RF-30 a RF-33 | RN-D01 a RN-D12 |
| Perfil e Histórico | RF-34 a RF-41 | RN-P01 a RN-P08; RN-H01 a RN-H15 |
| PWA | RF-42 a RF-46 | RN-W01 a RN-W12 |

## 6. Decisões confirmadas e condições para homologação

| Decisão | Definição consolidada | Situação |
|---|---|---|
| Duração após editar data/horário | Recalcular automaticamente `data_hora_fim - data_hora_inicio`, incluindo pausas e rejeitando intervalos inválidos. | Confirmada |
| Desempenho | Meta inicial de até 3 segundos no carregamento das páginas principais, sob condições documentadas e verificáveis. | Confirmada |
| Estratégia de testes | Testes manuais em todas as telas e automatizados em fluxos críticos. | Confirmada |
| Sessão histórica sem séries concluídas | **Bloquear a correção** quando remover/alterar exercícios ou séries deixar zero séries concluídas em uma sessão finalizada. | Confirmada — Opção A |

### Verificações de aceitação específicas

1. Dada uma sessão finalizada com uma única série concluída, se o usuário tentar remover essa série ou modificar seu status de forma que nenhuma série concluída permaneça, o servidor deve **rejeitar a operação sem gravar alterações parciais** e exibir o motivo.
2. Dada uma sessão finalizada com duas séries concluídas, remover uma delas é permitido quando os demais critérios de validação forem atendidos; os indicadores e o registro de auditoria deverão ser atualizados.
3. Ao corrigir os horários de início/fim, a duração deverá ser recalculada automaticamente, incluindo pausas. Horário final anterior ao inicial é inválido.
4. As correções devem respeitar a propriedade da conta, ser confirmadas e registrar quem alterou, quando, o que mudou e os valores anteriores e novos.

**Consistência com as regras aprovadas:** a validação de pelo menos uma série concluída mantém a RN-T27 sem qualquer alteração. A edição abrangente do Histórico continua permitida dentro dessa restrição, sem possibilitar exclusão integral de sessões pela interface.

## 7. Controle de aprovação

| Artefato | Estado |
|---|---|
| 153 regras de negócio e 42 cuidados técnicos | **APROVADOS**, inalterados |
| 46 requisitos funcionais | **APROVADOS DEFINITIVAMENTE — v1.0** |
| 25 requisitos não funcionais | **APROVADOS DEFINITIVAMENTE — v1.0** |

**Registro de aprovação:** usuário aprovou explicitamente em 13/11/2026 os 46 RF, os 25 RNF e os critérios de aceitação revisados. Alterações posteriores exigem nova aprovação. O arquivo `docs/regras-negocio.md` não foi modificado nesta homologação.


---

## Aditivo de escopo — Metas v1.0 (regras de negócio aprovadas, RF/RNF adicionais pendentes)

**Aprovação do módulo:** RN-M01 a RN-M36 e CT-M01 a CT-M07 estão aprovados e constam em [`regras-negocio.md`](regras-negocio.md). Esta seção é **informativa** e não altera nem renumera os **46 RF** ou os **25 RNF** homologados acima.

**Funcionalidades a especificar em requisitos adicionais para futura aprovação:**
- Acesso a **Perfil → Metas**; criação, edição, arquivamento, reativação e acompanhamento de metas personalizadas com **prazo opcional**.
- Metas com data-limite não atingidas: estado **Prazo encerrado**, com progresso preservado e opção de prorrogar o prazo ou arquivar.
- Conquistas automáticas baseadas em recordes pessoais/semanais, marcos de sessões e frequência, calculadas com dados válidos.
- Aviso discreto de nova conquista no Dashboard, acessível e dispensável, sem repetição indevida.
- Reavaliação de metas e conquistas quando houver correções de sessões finalizadas no Histórico.

**Requisitos adicionais de qualidade a detalhar futuramente:** idempotência no processamento de conquistas, segurança/autorização por usuário, consistência do recálculo e persistência do estado de avisos.

**Situação:** aditivo de regras de negócio **APROVADO**; RF/RNF adicionais de Metas **AINDA NÃO HOMOLOGADOS**. O conteúdo e a numeração de RF-01–RF-46 e RNF-01–RNF-25 permanecem preservados.
