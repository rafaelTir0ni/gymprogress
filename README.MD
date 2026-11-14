<div align="center">

# GymProgress

**Gerencie seus treinos. Registre seu desempenho. Acompanhe sua evolução.**

**PWA Mobile-First | PHP | MySQL | JavaScript**

![Status: Em planejamento](https://img.shields.io/badge/status-em%20planejamento-334155)
![Módulo Treinar: aprovado](https://img.shields.io/badge/Treinar-v1.0%20aprovado-059669)
![Módulo Fichas: aprovado](https://img.shields.io/badge/Fichas-v1.0%20aprovado-059669)
![Regras aprovadas](https://img.shields.io/badge/regras-189%20RN%20%2B%2049%20CT-059669)
![Interface: PWA](https://img.shields.io/badge/interface-PWA%20mobile--first-059669)

</div>

> **Estado do projeto:** planejamento e especificação. Os protótipos são referências visuais; as funcionalidades descritas ainda não devem ser apresentadas como implementadas.
>
> **Critério de documentação:** somente regras explicitamente aprovadas são identificadas como **APROVADAS**. Requisitos preliminares são rotulados como **PROPOSTOS** e as seções futuras possuem marcadores para substituição.

## Sumário

1. [Sobre o projeto](#sobre-o-projeto)
2. [Objetivos](#objetivos)
3. [Tecnologias e ambiente](#tecnologias-e-ambiente)
4. [Identidade visual e navegação](#identidade-visual-e-navegação)
5. [Funcionalidades e estado de aprovação](#funcionalidades-e-estado-de-aprovação)
6. [Requisitos funcionais](#requisitos-funcionais)
7. [Requisitos não funcionais](#requisitos-não-funcionais)
8. [Regras de negócio aprovadas](#regras-de-negócio-aprovadas)
9. [Regras de negócio pendentes](#regras-de-negócio-pendentes)
10. [Banco de dados](#banco-de-dados)
11. [Estrutura de pastas](#estrutura-de-pastas)
12. [Instalação e execução](#instalação-e-execução)
13. [Roadmap](#roadmap)
14. [Testes e qualidade](#testes-e-qualidade)
15. [Capturas de tela e demonstração](#capturas-de-tela-e-demonstração)
16. [Documentação e histórico](#documentação-e-histórico)

---

## Sobre o projeto

O **GymProgress** é um projeto pessoal de desenvolvimento Full Stack concebido para ajudar pessoas a gerenciar fichas de treino, registrar as séries efetivamente realizadas e consultar o histórico de desempenho. O objetivo é disponibilizar uma experiência de uso simples e rápida no celular.

A aplicação será uma **Progressive Web App (PWA)** com interface **mobile-first**. O backend será desenvolvido em PHP, com persistência em MySQL (ou MariaDB compatível), inicialmente em ambiente local Laragon.

**Problema:** anotações soltas dificultam consultar treinos passados, acompanhar cargas e manter as fichas organizadas.

**Solução:** centralizar planejamento, execução, histórico e visualização da evolução em um único aplicativo.

**Público-alvo inicial:** pessoas que desejam registrar e consultar seus próprios treinos. Não é uma plataforma de prescrição profissional ou aconselhamento médico.

## Objetivos

- Organizar fichas e exercícios de forma individualizada.
- Permitir o registro rápido de séries, repetições e cargas.
- Preservar o histórico mesmo após alterações nas fichas.
- Exibir a evolução semanal da **maior carga** por exercício.
- Priorizar usabilidade em smartphones, com possibilidade de instalação como PWA.
- Demonstrar modelagem relacional, autenticação, validação, segurança e organização de código no portfólio.

## Tecnologias e ambiente

| Camada | Tecnologia | Uso planejado |
|---|---|---|
| Ambiente de desenvolvimento | **Laragon** | Servidor local e banco de dados |
| Editor | **Visual Studio Code** | Desenvolvimento |
| Backend | **PHP 8** | Regras de negócio, autenticação e respostas do servidor |
| Banco de dados | **MySQL / MariaDB** | Persistência relacional |
| Acesso aos dados | **PDO** | Consultas preparadas |
| Frontend | **HTML5, CSS3, JavaScript** | Interface, validações complementares e interatividade |
| Gráficos | **Chart.js** | Evolução de cargas por semana |
| PWA | **Web App Manifest e Service Worker** | Instalabilidade e recursos de cache |
| Armazenamento offline (futuro) | **IndexedDB** | Registros locais e sincronização futura |
| Versionamento | **Git e GitHub** | Histórico de alterações |
| Design | **Figma** | Protótipos e fluxos de navegação |

> **Observação:** Laragon é o ambiente local escolhido. O deploy público e o acesso por HTTPS ainda não foram definidos. Um PWA instalável não implica automaticamente funcionamento offline de todo o sistema.

## Identidade visual e navegação

**Padrão visual aprovado:** dark mode com fundo preto/grafite, textos claros e detalhes em verde-esmeralda; cards arredondados, controles compactos, gráficos limpos e botões adequados ao toque.

**Navegação inferior definida:** `Início` · `Fichas` · `Treinar` · `Biblioteca` · `Perfil`.

| Aba | Função prevista |
|---|---|
| Início | Dashboard e atalho **Iniciar treino**, levando à aba Treinar |
| Fichas | Criação, consulta, edição e arquivamento de fichas; não inicia sessão |
| Treinar | Seleção da ficha para execução; registro e conclusão da sessão |
| Biblioteca | Catálogo de exercícios por grupos musculares e adição a fichas |
| Perfil | Conta, configurações e acesso ao Histórico |

**Fluxo de execução:** `Início → Treinar → Selecionar ficha → Sessão em andamento → Finalizar → Histórico`.

**Fluxo de gerenciamento:** `Fichas → Detalhes → Adicionar/editar exercícios`.

**Fluxo da Biblioteca:** `Biblioteca → + Exercício → Escolher ficha → Configurar séries/repetições → Confirmar`.

### Padrão da tela Treinar

- Cronômetro e botão **Finalizar** no cabeçalho.
- Navegação livre por círculos numerados clicáveis; sem barra horizontal de progresso.
- Tabela de séries, carga em kg, repetições e marcação de conclusão.
- Ação **Adicionar série**, conclusão flexível e reabertura de exercício.
- Ícone de gráfico ao lado do título para mostrar a maior carga semanal.
- Confirmação, resumo e tela de sucesso na finalização.

## Funcionalidades e estado de aprovação

| Módulo | Recursos planejados | Situação das regras |
|---|---|---|
| Treinar | Iniciar, retomar, registrar séries, concluir, gráficos, finalizar | **APROVADAS — v1.0** |
| Fichas | Criar, editar, ordenar exercícios, arquivar e excluir quando permitido | **APROVADO — v1.0** |
| Biblioteca | Busca, categorias, exercícios padrão e personalizados, vinculação à ficha | **APROVADO — v1.0** |
| Login e Cadastro | Autenticação e abertura de conta; link “Cadastre-se agora” | **APROVADO — v1.0** |
| Dashboard | Resumo e atalho para Treinar | **APROVADO — v1.0** |
| Perfil e Histórico | Configurações, sessões concluídas e registros anteriores | **APROVADO — v1.0** |
| Metas | Conquistas automáticas e metas personalizadas no Perfil | **APROVADO — v1.0 (aditivo)** |
| PWA offline | Instalação/cache e sincronização futura, por etapas | **REGRAS APROVADAS — execução em fases** |

> **APROVADOS — v1.0.** São 46 RF, 25 RNF e critérios de aceitação revisados. A especificação oficial está em [`docs/requisitos.md`](docs/requisitos.md). A seção a seguir reproduz esses requisitos aprovados.

> **Aditivo Metas:** o módulo Metas v1.0 possui regras de negócio e cuidados técnicos aprovados; seus requisitos RF/RNF adicionais ainda serão especificados e homologados separadamente. Os RF-01–RF-46 e RNF-01–RNF-25 abaixo permanecem inalterados.

## Requisitos funcionais aprovados (RF)

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

### Índice dos RF por módulo

- **Autenticação e Cadastro:** RF-01 a RF-05
- **Fichas de Treino:** RF-06 a RF-12
- **Biblioteca de Exercícios:** RF-13 a RF-18
- **Execução de Treinos:** RF-19 a RF-29
- **Dashboard:** RF-30 a RF-33
- **Perfil e Histórico:** RF-34 a RF-41
- **PWA e Aplicativo:** RF-42 a RF-46

## Requisitos não funcionais aprovados (RNF)

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

### Critérios de aceitação complementares

- **Duração histórica:** ao corrigir data ou horários de uma sessão, recalcular automaticamente sua duração como diferença entre início e fim, incluindo pausas, sem permitir intervalo negativo ou inconsistência de datas.
- **Desempenho:** meta inicial de até 3 segundos para carregar páginas principais, medida com ferramenta, dispositivo, rede e volume de dados registrados em relatório de testes. Essa é uma meta de planejamento, não desempenho já comprovado.
- **Estratégia de testes:** testes manuais em todas as telas e automação prioritária de autenticação, autorização, associações de fichas, execução/finalização, edição histórica e auditoria.
- **Histórico:** edição abrangente de sessões finalizadas com autorização, confirmação, transação, auditoria e recálculo de indicadores; não permitir exclusão integral da sessão pela interface. **Impedir que a correção deixe a sessão finalizada sem ao menos uma série concluída**; exibir mensagem clara e preservar os dados anteriores caso a validação falhe.
- **Fichas:** jamais alterar automaticamente as configurações de uma sessão em andamento ou já finalizada após editar uma ficha.
- **Conectividade:** no MVP online, o usuário deve ser avisado quando a confirmação do salvamento não chegar ao servidor; modo offline completo é etapa futura.


## Regras de negócio aprovadas

Esta seção documenta **todas as 189 regras de negócio e 49 cuidados técnicos aprovados**, incluindo o aditivo Metas v1.0. Os **46 RF e 25 RNF** também foram homologados, conforme a seção anterior. O documento especializado é [`docs/regras-negocio.md`](docs/regras-negocio.md), que deve permanecer sincronizado com este README.

### Módulo Treinar — v1.0 (APROVADO)

**Escopo:** seleção de fichas para executar treinos, sessões em andamento, registro e conclusão flexível de exercícios, consulta a gráficos, duração e finalização.

### 1.1 Seleção e início da sessão

| Código | Regra de negócio |
|---|---|
| RN-T01 | Apenas fichas ativas que contenham exercícios poderão iniciar sessões. |
| RN-T02 | Na aba Treinar, ao selecionar uma ficha, o usuário poderá iniciar uma sessão. |
| RN-T03 | Cada sessão deverá estar vinculada ao usuário autenticado e à ficha escolhida. |
| RN-T04 | O usuário poderá manter apenas uma sessão em andamento por vez. |
| RN-T05 | Se houver uma sessão ativa, o aplicativo oferecerá a opção de retomá-la. |

### 1.2 Execução de exercícios

| Código | Regra de negócio |
|---|---|
| RN-T06 | Os exercícios serão apresentados na ordem definida na ficha. |
| RN-T07 | O usuário poderá navegar livremente pelos exercícios utilizando os círculos numerados clicáveis. |
| RN-T08 | Cada exercício poderá possuir séries, repetições e cargas registradas individualmente. |
| RN-T09 | O usuário poderá adicionar séries extras durante a sessão. |
| RN-T10 | As séries concluídas serão salvas durante o treino, evitando perda de registros. |

### 1.3 Conclusão flexível de exercícios

| Código | Regra de negócio |
|---|---|
| RN-T11 | O exercício será concluído automaticamente quando todas as séries planejadas forem realizadas. |
| RN-T12 | O usuário poderá concluir antecipadamente um exercício. |
| RN-T13 | A conclusão antecipada não apagará as séries realizadas nem marcará as pendentes como concluídas. |
| RN-T14 | Exercícios concluídos poderão ser reabertos enquanto a sessão estiver ativa. |
| RN-T15 | Ao reabrir um exercício, será possível modificar ou adicionar séries. |
| RN-T16 | Exercícios com algumas séries realizadas, mas não concluídos, serão classificados como parcialmente realizados. |

**Estados previstos:** Pendente (nenhuma série concluída); Parcialmente realizado (algumas séries concluídas, mas sem conclusão do exercício); Concluído (séries planejadas concluídas); Concluído antecipadamente (conclusão manual antes de completar todas as séries). Reabrir e editar um exercício durante uma sessão ativa exige reavaliar seu estado.

### 1.4 Estatísticas por exercício

| Código | Regra de negócio |
|---|---|
| RN-T17 | Cada exercício terá um botão de gráfico ao lado do título. |
| RN-T18 | O gráfico exibirá a evolução da **maior carga registrada por semana**. |
| RN-T19 | O eixo X representará as semanas, e o eixo Y a carga em quilogramas (kg). |
| RN-T20 | Apenas séries concluídas de sessões finalizadas entrarão no cálculo. |
| RN-T21 | O gráfico poderá ser consultado sem encerrar a sessão em andamento. |

### 1.5 Duração da sessão

| Código | Regra de negócio |
|---|---|
| RN-T22 | O sistema registrará o horário de início da sessão. |
| RN-T23 | A duração será calculada pela diferença entre início e finalização. |
| RN-T24 | As pausas do cronômetro também serão incluídas na duração total. |
| RN-T25 | A sessão em andamento poderá ser retomada caso o usuário feche o aplicativo. |

### 1.6 Finalização e histórico

| Código | Regra de negócio |
|---|---|
| RN-T26 | O botão **Finalizar** ficará disponível no cabeçalho do treino. |
| RN-T27 | A finalização exigirá pelo menos uma série concluída. |
| RN-T28 | Caso nenhuma série tenha sido concluída, o aplicativo oferecerá **Continuar treino** ou **Descartar sessão**. |
| RN-T29 | Descartar uma sessão exigirá confirmação do usuário. |
| RN-T30 | O aplicativo permitirá finalizar sessões com exercícios pendentes ou parcialmente realizados. |
| RN-T31 | Antes da confirmação, será apresentado um resumo completo da sessão. |
| RN-T32 | O resumo exibirá duração, exercícios concluídos, parcialmente realizados, pendentes e volume total registrado. |
| RN-T33 | O usuário poderá voltar ao treino sem perder seus registros. |
| RN-T34 | Após a confirmação, a sessão será marcada como finalizada e disponibilizada no Histórico. |
| RN-T35 | Os gráficos de evolução considerarão os novos registros válidos após a finalização. |

### 1.7 Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-T01 | Não aceitar cargas ou repetições negativas; a carga poderá ficar vazia em exercícios sem peso externo. |
| CT-T02 | O volume total será calculado como a soma de **carga × repetições** das séries concluídas com carga registrada. Trata-se de um indicador de trabalho com carga externa, não de todo o esforço do treino. |
| CT-T03 | Impedir a finalização duplicada de uma mesma sessão, inclusive em caso de toques repetidos ou falhas de conexão. |

### 1.8 Fluxo aprovado

1. Na aba **Treinar**, selecionar uma ficha ativa com exercícios.
2. Iniciar a sessão, ou retomar a sessão já em andamento.
3. Navegar livremente pelos exercícios e registrar séries, repetições e cargas.
4. Concluir exercícios automaticamente ou antecipadamente; permitir reabertura durante a sessão.
5. Consultar, pelo ícone ao lado do exercício, o gráfico da maior carga semanal registrada em sessões finalizadas.
6. Acionar **Finalizar**, validar que haja ao menos uma série concluída e apresentar resumo.
7. Confirmar a finalização, registrar a sessão no Histórico e disponibilizar os dados para estatísticas.

### 1.9 Diretriz visual aprovada (referência, não regra de negócio)

PWA mobile-first em dark mode (preto/grafite, destaques verde-esmeralda), com seleção de fichas, cronômetro, navegação por círculos clicáveis, tabela compacta de séries/cargas/repetições e botão **Finalizar** no cabeçalho. O fluxo visual de finalização prevê confirmação, resumo e tela de sucesso. Os protótipos aprovados nesta conversa são as referências visuais.


<!-- INÍCIO BLOCO SUBSTITUÍVEL: FICHAS -->
### Módulo Fichas — v1.0 (APROVADO)

**Escopo:** cadastro, visualização, edição, organização dos exercícios, arquivamento e exclusão controlada de fichas, com integração à Biblioteca.

#### 2.1 Cadastro de fichas

| Código | Regra de negócio |
|---|---|
| RN-F01 | Apenas usuários autenticados poderão criar fichas. |
| RN-F02 | Cada ficha pertencerá exclusivamente ao usuário que a criou. |
| RN-F03 | O usuário poderá criar quantas fichas desejar. |
| RN-F04 | Toda ficha deverá possuir um nome obrigatório. |
| RN-F05 | Descrição e grupo muscular principal serão opcionais. |
| RN-F06 | Será permitido criar fichas sem exercícios. |
| RN-F07 | Novas fichas serão consideradas ativas por padrão. |
| RN-F08 | Fichas diferentes poderão possuir nomes iguais. |

#### 2.2 Visualização de fichas

| Código | Regra de negócio |
|---|---|
| RN-F09 | O usuário visualizará apenas suas próprias fichas. |
| RN-F10 | Cada ficha exibirá nome, grupo muscular, quantidade de exercícios e status. |
| RN-F11 | Haverá filtros: Todas, Ativas e Arquivadas. |
| RN-F12 | Será possível pesquisar fichas pelo nome. |
| RN-F13 | Ao selecionar uma ficha, serão exibidos seus detalhes e exercícios associados. |
| RN-F14 | Selecionar uma ficha na aba Fichas não iniciará um treino. |

#### 2.3 Gerenciamento de exercícios

| Código | Regra de negócio |
|---|---|
| RN-F15 | Uma ficha poderá conter vários exercícios, mas cada exercício poderá aparecer apenas uma vez na mesma ficha. |
| RN-F16 | O mesmo exercício poderá ser utilizado em fichas diferentes. |
| RN-F17 | O usuário poderá adicionar exercícios existentes na Biblioteca. |
| RN-F18 | Será possível criar exercícios personalizados diretamente em uma ficha. |
| RN-F19 | Imagens e vídeos serão opcionais para exercícios personalizados. |
| RN-F20 | Cada exercício associado à ficha deverá possuir séries e repetições planejadas, com quantidade fixa ou intervalo de repetições. |
| RN-F21 | O usuário poderá modificar a ordem dos exercícios. |
| RN-F22 | Remover um exercício da ficha não o excluirá da Biblioteca. |
| RN-F23 | Exercícios personalizados serão adicionados à ficha atual e à biblioteca privada do usuário. |

#### 2.4 Edição das fichas

| Código | Regra de negócio |
|---|---|
| RN-F24 | O usuário poderá alterar o nome, a descrição e o grupo muscular de suas fichas. |
| RN-F25 | Será possível modificar séries e repetições planejadas dos exercícios. |
| RN-F26 | O usuário poderá adicionar ou remover exercícios, respeitando as restrições de sessões em andamento. |
| RN-F27 | Alterações nas fichas não poderão modificar o histórico de sessões finalizadas. |
| RN-F28 | Alterações nas fichas não poderão modificar os registros de sessões em andamento. |

#### 2.5 Arquivamento e exclusão

| Código | Regra de negócio |
|---|---|
| RN-F29 | O usuário poderá arquivar fichas que não utiliza mais. |
| RN-F30 | Fichas arquivadas não poderão iniciar novas sessões. |
| RN-F31 | O histórico de fichas arquivadas será preservado. |
| RN-F32 | O usuário poderá reativar fichas arquivadas. |
| RN-F33 | Fichas sem histórico poderão ser excluídas definitivamente, mediante confirmação. |
| RN-F34 | Fichas com histórico deverão ser arquivadas, não excluídas diretamente. |
| RN-F35 | Fichas com sessões em andamento não poderão ser arquivadas ou excluídas. |

#### 2.6 Integração com a Biblioteca

| Código | Regra de negócio |
|---|---|
| RN-F36 | Ao clicar no `+` de um exercício da Biblioteca, será necessário selecionar uma ficha de destino. |
| RN-F37 | Apenas fichas ativas pertencentes ao usuário poderão receber exercícios. |
| RN-F38 | O usuário deverá configurar séries e repetições antes da inclusão. |
| RN-F39 | A inclusão exigirá confirmação e não poderá gerar exercícios duplicados na mesma ficha. |
| RN-F40 | O exercício permanecerá disponível na Biblioteca após ser associado a uma ficha. |

**Regra complementar de interface:** quando a ficha não tiver exercícios, a área destinada à lista exibirá apenas a ação **Adicionar exercício**, além das informações básicas da ficha.

#### 2.7 Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-F01 | Impedir exercícios duplicados na mesma ficha também por restrição de unicidade no banco de dados. |
| CT-F02 | Exigir que a quantidade de séries planejadas seja um número inteiro maior que zero. |
| CT-F03 | Aceitar repetições planejadas como quantidade fixa positiva (`10`) ou intervalo válido de inteiros positivos (`8–12`), com início menor ou igual ao fim. |
| CT-F04 | Validar no backend que a ficha pertence ao usuário autenticado e que cada exercício é público ou pertence ao próprio usuário. |
| CT-F05 | Criar o exercício personalizado e associá-lo à ficha na mesma transação, evitando registros incompletos. |

#### 2.8 Fluxos aprovados

1. **Fichas → Selecionar ficha → Detalhes:** consultar e gerenciar exercícios sem iniciar sessão.
2. **Detalhes → Adicionar exercício personalizado:** cadastrar no catálogo privado e associar à ficha selecionada.
3. **Biblioteca → + → Escolher ficha ativa → Definir séries/repetições → Confirmar:** adicionar sem duplicidade.
4. **Arquivar ou excluir:** manter histórico; permitir exclusão somente sem histórico e sem sessão em andamento, com confirmação.

<!-- FIM BLOCO SUBSTITUÍVEL: FICHAS -->

## Módulos adicionais aprovados

### Biblioteca — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-B01 | Apenas usuários autenticados poderão acessar a Biblioteca. |
| RN-B02 | Os exercícios serão organizados nas categorias Peito, Costas, Pernas, Ombros, Braços, Abdômen e Outros. |
| RN-B03 | O usuário poderá pesquisar exercícios pelo nome. |
| RN-B04 | Será possível filtrar exercícios por categoria muscular. |
| RN-B05 | A Biblioteca terá exercícios gerais e personalizados. |
| RN-B06 | Exercícios gerais estarão disponíveis para todos os usuários. |
| RN-B07 | Exercícios personalizados serão privados ao usuário que os cadastrou. |
| RN-B08 | Exercícios poderão possuir descrições, imagens e vídeos demonstrativos. |
| RN-B09 | Imagem e vídeo não serão obrigatórios para exercícios personalizados. |
| RN-B10 | Ao clicar no botão `+`, será necessário selecionar a ficha de destino. |
| RN-B11 | Apenas fichas ativas pertencentes ao usuário poderão receber exercícios. |
| RN-B12 | O usuário deverá configurar séries e repetições antes de adicionar o exercício. |
| RN-B13 | O mesmo exercício não poderá ser adicionado duas vezes à mesma ficha. |
| RN-B14 | O usuário poderá editar apenas seus exercícios personalizados. Exercícios gerais serão protegidos contra edições dos usuários. |
| RN-B15 | Exercícios personalizados poderão ser arquivados e reativados, preservando seus registros históricos. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-B01 | Validar no servidor a propriedade dos exercícios personalizados. |
| CT-B02 | Impedir que exercícios privados sejam acessados por outros usuários. |
| CT-B03 | Validar que a ficha de destino pertence ao usuário e está ativa. |
| CT-B04 | Proteger a associação contra exercícios duplicados, inclusive no banco de dados. |
| CT-B05 | Utilizar mídias próprias, licenciadas ou autorizadas. |
| CT-B06 | Arquivar exercícios sem apagar referências históricas, permitindo reativação. |


### Login e Cadastro — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-A01 | O Login será a tela inicial para usuários não autenticados. |
| RN-A02 | O formulário terá e-mail, senha, botão Entrar e link "Cadastre-se agora". |
| RN-A03 | O cadastro exigirá nome, e-mail e senha. |
| RN-A04 | Cada e-mail poderá pertencer a somente uma conta. |
| RN-A05 | A senha deverá conter pelo menos oito caracteres. |
| RN-A06 | As senhas serão armazenadas usando algoritmos seguros de hash. |
| RN-A07 | O login validará as credenciais cadastradas. |
| RN-A08 | Após autenticação, o usuário será direcionado ao Dashboard. |
| RN-A09 | Usuários não autenticados não poderão acessar recursos privados. |
| RN-A10 | O logout encerrará a sessão autenticada. |
| RN-A11 | Após o logout, o usuário será direcionado ao Login. |
| RN-A12 | Será possível solicitar recuperação de senha por e-mail. |
| RN-A13 | Links de recuperação serão temporários, de uso único e validados no servidor. |
| RN-A14 | O sistema limitará tentativas excessivas de autenticação. |
| RN-A15 | Um usuário não poderá acessar informações de outra conta, mesmo alterando URLs ou identificadores. |
| RN-A16 | Após realizar o cadastro, o usuário será redirecionado ao Login para autenticar-se, sem login automático. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-A01 | Utilizar `password_hash()` e `password_verify()`. |
| CT-A02 | Utilizar consultas preparadas e validações no backend. |
| CT-A03 | Proteger formulários contra CSRF e saídas HTML contra XSS. |
| CT-A04 | Renovar o identificador de sessão após autenticação. |
| CT-A05 | Utilizar cookies de sessão com configurações seguras. |
| CT-A06 | Não revelar se determinada conta existe durante tentativas de login e recuperação de senha. |
| CT-A07 | Armazenar tokens de recuperação de forma segura e invalidá-los após utilização. |


### Dashboard — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-D01 | O Dashboard será a primeira tela após o login. |
| RN-D02 | O usuário visualizará uma mensagem de boas-vindas. |
| RN-D03 | O botão "Iniciar treino" abrirá a aba Treinar. |
| RN-D04 | O sistema exibirá a quantidade de sessões finalizadas na semana. |
| RN-D05 | O sistema exibirá a quantidade de sessões finalizadas no mês. |
| RN-D06 | O último treino finalizado será exibido no Dashboard. |
| RN-D07 | Haverá um atalho para o Histórico. |
| RN-D08 | Caso exista uma sessão ativa, será exibida a opção "Continuar treino". |
| RN-D09 | Sessões em andamento não serão contabilizadas como treinos finalizados. |
| RN-D10 | Usuários sem histórico visualizarão mensagens orientativas. |
| RN-D11 | Todos os indicadores utilizarão apenas dados do usuário autenticado. |
| RN-D12 | Os indicadores serão atualizados após a finalização de sessões ou correções válidas no Histórico. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-D01 | Considerar apenas sessões finalizadas nos indicadores de frequência. |
| CT-D02 | Adotar segunda-feira como início da semana. |
| CT-D03 | Utilizar uma referência de fuso horário consistente para datas e agrupamentos. |
| CT-D04 | Não contabilizar sessões descartadas. |
| CT-D05 | Evitar consultas desnecessárias ao banco para calcular indicadores. |


### Perfil — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-P01 | O usuário poderá consultar seu nome e e-mail. |
| RN-P02 | Será possível alterar o nome cadastrado. |
| RN-P03 | O e-mail poderá ser alterado, desde que não esteja vinculado a outra conta. |
| RN-P04 | A alteração de senha exigirá a confirmação da senha atual. |
| RN-P05 | O Perfil terá um atalho para o Histórico de Treinos. |
| RN-P06 | O usuário poderá acessar as configurações do aplicativo. |
| RN-P07 | O Perfil terá a opção "Sair da conta". |
| RN-P08 | Apenas o proprietário poderá visualizar e modificar as informações privadas de sua conta. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-P01 | Validar alterações de nome e e-mail no servidor. |
| CT-P02 | Exigir verificação da senha atual antes de permitir sua alteração pelo Perfil. |
| CT-P03 | Proteger operações sensíveis contra requisições não autorizadas. |
| CT-P04 | Invalidar sessões conforme a política de segurança definida para alterações sensíveis. |


### Histórico — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-H01 | O Histórico principal exibirá apenas sessões finalizadas. |
| RN-H02 | As sessões serão ordenadas da mais recente para a mais antiga. |
| RN-H03 | O usuário poderá filtrar sessões por data e ficha. |
| RN-H04 | Cada sessão exibirá nome da ficha, data, duração e exercícios realizados. |
| RN-H05 | Ao abrir uma sessão, serão apresentados exercícios, séries, cargas e repetições. |
| RN-H06 | Exercícios concluídos antecipadamente serão identificados. |
| RN-H07 | Exercícios parcialmente realizados e pendentes terão identificações diferentes. |
| RN-H08 | O arquivamento de uma ficha não excluirá seu histórico. |
| RN-H09 | O histórico preservará as configurações originais da sessão, independentemente de alterações posteriores na ficha. |
| RN-H10 | O usuário poderá corrigir todas as informações pertinentes de uma sessão finalizada, mediante confirmação, incluindo data, horários, nome, observações, exercícios, séries, cargas, repetições e estados de conclusão. |
| RN-H11 | Quaisquer correções que afetem indicadores deverão atualizar os gráficos e as estatísticas. |
| RN-H12 | Um usuário não poderá consultar ou alterar sessões de outra conta. |
| RN-H13 | Não será possível excluir sessões finalizadas integralmente pela interface do aplicativo. |
| RN-H14 | Será permitido adicionar, editar ou remover exercícios e séries de uma sessão finalizada, preservando a identificação da sessão e a trilha de auditoria. |
| RN-H15 | O sistema registrará as alterações realizadas, incluindo data e detalhes necessários para auditar as correções. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-H01 | Validar todos os dados editáveis (incluindo datas, horários, cargas, repetições, exercícios e estados) antes de salvar correções, respeitando as regras aplicáveis. |
| CT-H02 | Registrar valores anteriores e novos, data e usuário responsável por cada correção. |
| CT-H03 | Recalcular gráficos e estatísticas afetados após cada alteração confirmada. |
| CT-H04 | Preservar a integridade e a identidade da sessão histórica, sem permitir exclusão integral pela interface. |
| CT-H05 | Garantir autorização do proprietário em todas as consultas e atualizações. |
| CT-H06 | Aplicar correções e seus registros de auditoria em uma única transação. |


### PWA — versão 1.0 (APROVADA)

#### Regras de negócio

| Código | Regra de negócio |
|---|---|
| RN-W01 | O GymProgress utilizará uma interface mobile-first. |
| RN-W02 | A aplicação possuirá nome, ícones e configurações apropriadas para instalação como PWA. |
| RN-W03 | O ambiente de produção utilizará HTTPS. |
| RN-W04 | O aplicativo poderá ser acessado pelo navegador sem instalação obrigatória. |
| RN-W05 | A autenticação e o salvamento online dependerão de comunicação com o backend. |
| RN-W06 | Arquivos estáticos poderão ser armazenados em cache para otimizar o carregamento. |
| RN-W07 | O sistema informará quando a conexão estiver indisponível. |
| RN-W08 | O modo offline completo será desenvolvido após a primeira versão funcional online. |
| RN-W09 | Na versão offline, cada operação sincronizável terá identificação para evitar duplicações. |
| RN-W10 | Registros ainda não confirmados pelo servidor serão identificados como não sincronizados. |
| RN-W11 | Conflitos de sincronização deverão ser detectados e tratados. |
| RN-W12 | Dados armazenados localmente deverão receber proteção apropriada à sua sensibilidade. |

#### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-W01 | Configurar corretamente HTTPS, manifest e service worker. |
| CT-W02 | Não armazenar credenciais ou respostas privadas indiscriminadamente no cache. |
| CT-W03 | Garantir autenticação e autorização no servidor, mesmo em uma PWA instalada. |
| CT-W04 | Tratar adequadamente falhas de rede e operações não confirmadas. |
| CT-W05 | Na versão offline, implementar sincronização idempotente para evitar duplicações. |
| CT-W06 | Testar o comportamento nos navegadores e dispositivos móveis suportados. |

## Módulo Metas — versão 1.0 (APROVADA)

**Aditivo aprovado:** 36 regras de negócio (RN-M01–RN-M36) e 7 cuidados técnicos (CT-M01–CT-M07).

**Escopo:** metas personalizadas com prazo opcional, conquistas automáticas, avisos discretos de conquistas no Dashboard e acesso por **Perfil → Metas**. A decisão visual anterior elimina a seção redundante “Acesso rápido” do Dashboard; a navegação inferior mantém **Início, Fichas, Treinar, Biblioteca e Perfil**.

### Regras de negócio

#### Cadastro e gerenciamento

| Código | Regra |
|---|---|
| RN-M01 | Apenas usuários autenticados poderão acessar suas metas. |
| RN-M02 | Cada meta personalizada pertencerá exclusivamente ao usuário que a criou. |
| RN-M03 | O usuário poderá criar quantas metas personalizadas desejar. |
| RN-M04 | Toda meta deverá possuir um tipo e um objetivo mensurável. |
| RN-M05 | O usuário poderá estabelecer metas de carga, frequência semanal e quantidade de sessões finalizadas. |
| RN-M06 | Metas de carga deverão estar associadas a um exercício específico. |
| RN-M07 | A data-limite de metas personalizadas será **opcional**. Caso uma meta com prazo não seja alcançada até a data-limite, passará ao estado **Prazo encerrado**, preservando o progresso registrado. |
| RN-M08 | O usuário poderá editar metas ainda não concluídas. Para metas com **Prazo encerrado**, poderá estender a data-limite para uma data futura, reativando seu acompanhamento, ou optar por arquivá-las. |
| RN-M09 | O usuário poderá arquivar metas que não deseja acompanhar. |
| RN-M10 | Metas arquivadas poderão ser reativadas sem perda dos registros de progresso. |

#### Conquistas automáticas

| Código | Regra |
|---|---|
| RN-M11 | O sistema identificará automaticamente recordes de carga por exercício. |
| RN-M12 | Um novo recorde pessoal ocorrerá quando a maior carga anterior daquele exercício for superada. |
| RN-M13 | Um recorde semanal ocorrerá quando a maior carga anterior do exercício na mesma semana for superada. |
| RN-M14 | O sistema reconhecerá conquistas por quantidade acumulada de sessões finalizadas. |
| RN-M15 | O sistema poderá identificar conquistas por frequência e consistência semanal. |
| RN-M16 | Apenas séries concluídas em sessões finalizadas serão utilizadas nas conquistas de carga. |
| RN-M17 | Sessões descartadas e exercícios pendentes não produzirão conquistas. |

#### Progresso e conclusão

| Código | Regra |
|---|---|
| RN-M18 | O progresso das metas será calculado automaticamente com base nos registros válidos. |
| RN-M19 | Cada meta exibirá seu valor atual e o objetivo definido. |
| RN-M20 | Metas de carga utilizarão a maior carga válida do exercício dentro do período aplicável. |
| RN-M21 | Metas de frequência contabilizarão somente sessões finalizadas no período definido. |
| RN-M22 | Uma meta será considerada alcançada quando seu critério for atendido. |
| RN-M23 | O usuário poderá visualizar metas ativas, alcançadas, com **Prazo encerrado** e arquivadas, com identificação distinta para cada estado. |
| RN-M24 | Uma meta alcançada preservará a data em que foi atingida, sujeita a recálculo após correções no Histórico; para metas com prazo, somente resultados obtidos até a data-limite vigente contarão como cumprimento no prazo. |
| RN-M25 | Nenhuma meta será obrigatória para utilizar o aplicativo ou iniciar treinos. |

#### Integração com Histórico

| Código | Regra |
|---|---|
| RN-M26 | Correções no Histórico recalcularão as metas e conquistas afetadas, inclusive a situação **Alcançada** ou **Prazo encerrado** quando houver alteração de datas ou resultados. |
| RN-M27 | Uma conquista baseada em informação corrigida poderá ter seu estado atualizado ou ser invalidada. |
| RN-M28 | Nenhuma correção histórica poderá gerar conquistas duplicadas. |
| RN-M29 | A classificação de recordes considerará a data efetiva da sessão, inclusive após correções. |
| RN-M30 | O cálculo respeitará a exigência de pelo menos uma série concluída por sessão finalizada. |

#### Integração com Dashboard

| Código | Regra |
|---|---|
| RN-M31 | O Dashboard exibirá aviso discreto quando surgir uma nova conquista válida. |
| RN-M32 | O aviso exibirá o título e uma breve descrição da conquista. |
| RN-M33 | Selecionar o aviso abrirá Perfil → Metas. |
| RN-M34 | O usuário poderá dispensar o aviso sem remover a conquista. |
| RN-M35 | Avisos já visualizados não deverão aparecer repetidamente como novas conquistas. |
| RN-M36 | A ausência de novas conquistas não ocupará espaço no Dashboard. |

### Cuidados técnicos aprovados

| Código | Cuidado |
|---|---|
| CT-M01 | Garantir que metas e conquistas sejam acessíveis somente ao proprietário. |
| CT-M02 | Validar tipos, valores, exercícios e períodos das metas no backend, aceitando prazo ausente; validar também a prorrogação da data-limite. |
| CT-M03 | Calcular recordes usando dados válidos de séries concluídas e sessões finalizadas. |
| CT-M04 | Evitar conquistas duplicadas quando os dados forem processados novamente. |
| CT-M05 | Recalcular metas após correções históricas, mantendo consistência com os gráficos. |
| CT-M06 | Armazenar o estado de visualização dos avisos de conquistas por usuário. |
| CT-M07 | Utilizar transações para atualizações que modifiquem conjuntamente registros e estados das metas. |


## Banco de dados

**Modelagem preliminar (não homologada):**

| Tabela | Responsabilidade |
|---|---|
| `usuarios` | Contas e autenticação |
| `fichas` | Planos e status ativo/arquivado |
| `exercicios` | Catálogo comum e exercícios privados |
| `ficha_exercicios` | Exercícios e séries planejadas em cada ficha |
| `sessoes` | Registro das sessões e de seus estados |
| `sessao_exercicios` | Retrato dos exercícios executados em cada sessão |
| `series_realizadas` | Séries, repetições, cargas e conclusão |

**Relacionamentos principais:** usuário 1:N fichas e sessões; fichas 1:N ficha_exercicios e sessões; sessões 1:N sessao_exercicios; sessao_exercicios 1:N series_realizadas. Exercícios podem pertencer a diferentes fichas, mas não se repetem na mesma ficha.

**Ponto de atenção:** armazenar um *snapshot* da configuração e identificação dos exercícios no momento de iniciar a sessão para preservar registros históricos quando a ficha for alterada. A estratégia final, chaves, índices e regras de exclusão serão homologados no documento do banco de dados.

<!-- INÍCIO BLOCO SUBSTITUÍVEL: BANCO -->
**[INSERIR AQUI O DIAGRAMA ENTIDADE-RELACIONAMENTO, SCHEMA FINAL E DECISÕES DE MODELAGEM APROVADOS]**
<!-- FIM BLOCO SUBSTITUÍVEL: BANCO -->

Documentos: [`docs/banco-de-dados.md`](docs/banco-de-dados.md) · `database/schema.sql` (a criar/validar).

## Estrutura de pastas

Estrutura MVC simplificada planejada, já organizada localmente no projeto:

```text
gymprogress/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── FichaController.php
│   │   ├── ExercicioController.php
│   │   ├── SessaoController.php
│   │   └── PerfilController.php
│   ├── models/
│   │   ├── Usuario.php
│   │   ├── Ficha.php
│   │   ├── Exercicio.php
│   │   ├── FichaExercicio.php
│   │   ├── Sessao.php
│   │   ├── SessaoExercicio.php
│   │   └── SerieRealizada.php
│   ├── views/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── fichas/
│   │   ├── exercicios/
│   │   ├── sessoes/
│   │   ├── perfil/
│   │   ├── estatisticas/
│   │   └── layouts/
│   └── helpers/
├── config/
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seeds.sql
├── public/
│   ├── index.php
│   ├── manifest.webmanifest     # previsto para a PWA
│   ├── service-worker.js        # previsto para a PWA
│   └── assets/
│       ├── css/
│       ├── js/
│       └── img/
│           └── icons/
├── tests/
├── docs/
│   ├── requisitos.md
│   ├── regras-negocio.md
│   └── banco-de-dados.md
├── .env.example
├── .gitignore
└── README.md
```

> As entradas acima indicam o **planejamento dos caminhos e arquivos**, não a garantia de que cada arquivo foi implementado. O diretório `public/` deve ser configurado como raiz pública do servidor, protegendo `app/`, `config/` e `database/` de acesso direto.

## Instalação e execução

> **Em desenvolvimento local:** esquema MySQL 8.4.3 validado parcialmente no phpMyAdmin e autenticação básica implementada, ainda sujeita a testes completos no Laragon.

**Ambiente local:** Windows, Laragon, PHP 8.3, MySQL 8.4.3 e PDO.

### Banco de dados durante o desenvolvimento

1. Durante **todos os testes**, usar apenas o banco **`gymprogress_teste`**. Não criar `gymprogress` por enquanto.
2. Se `gymprogress_teste` já contém as 12 tabelas, **não executar novamente** `database/schema.sql`.
3. Para instalação limpa, crie manualmente `gymprogress_teste`, selecione-o no phpMyAdmin, confirme com `SELECT DATABASE();` e importe `database/schema.sql` **uma única vez** em banco vazio. O script intencionalmente não tem `CREATE DATABASE` nem `USE`.
4. Copie `.env.example` para `.env` (a cópia local **não vai para o Git**), confira `DB_DATABASE=gymprogress_teste` e ajuste as credenciais locais. Nunca publique seu `.env`.
5. Inicie Apache e MySQL no Laragon e tente `http://localhost/gymprogress/public/index.php?route=cadastro`. O acesso depende da pasta e da configuração local.
6. Teste cadastro, login, logout e bloqueio ao Dashboard após logout; veja `docs/autenticacao-implementacao.md`.

**Depois da conclusão dos testes**, criar `gymprogress` como base definitiva e migrar seletivamente os dados úteis, com backup e verificação. Projetado inicialmente para uso pessoal em celular; uso fora da rede local requer publicação segura.

**Segurança:** nunca versionar senhas, segredos, dados reais de usuários ou arquivos com credenciais. Configurações de exemplo devem usar valores fictícios.

## Roadmap

- [x] Definir objetivo, escopo e stack inicial.
- [x] Escolher PWA mobile-first e identidade visual.
- [x] Aprovar protótipos principais de Fichas e Treinar.
- [x] Aprovar Treinar v1.0: RN-T01–RN-T35 e CT-T01–CT-T03.
- [x] Aprovar Fichas v1.0: RN-F01–RN-F40 e CT-F01–CT-F05.
- [ ] Aprovar Biblioteca de Exercícios.
- [ ] Aprovar Login, Cadastro, Dashboard, Perfil e Histórico.
- [ ] Revisar e aprovar requisitos funcionais e não funcionais.
- [ ] Finalizar modelagem do banco de dados.
- [ ] Finalizar protótipos ainda pendentes.
- [ ] Implementar autenticação e isolamento de dados por usuário.
- [ ] Implementar Fichas e Biblioteca.
- [ ] Implementar Treinar, conclusão flexível, retomada e finalização.
- [ ] Implementar Histórico e gráficos por exercício.
- [ ] Preparar manifest, ícones e instalação PWA.
- [ ] Testar, publicar e preparar demonstração.
- [ ] Implementar recursos offline e sincronização (fase posterior).

## Testes e qualidade

**Cenários preliminares (a transformar em casos de teste):**

- Iniciar treino somente com ficha ativa e contendo exercícios.
- Não permitir duas sessões ativas para a mesma pessoa.
- Retomar uma sessão após fechar e reabrir o aplicativo.
- Preservar séries realizadas ao concluir um exercício antecipadamente.
- Reabrir e editar exercício enquanto a sessão estiver ativa.
- Não considerar exercícios pendentes como concluídos.
- Bloquear finalização sem nenhuma série concluída; oferecer continuar ou descartar.
- Impedir finalização duplicada após toques repetidos.
- Exibir gráfico de maior carga semanal usando somente séries concluídas em sessões finalizadas.
- Restringir acesso a dados de outros usuários.
- Validar cargas e repetições; rejeitar valores negativos.

<!-- INÍCIO BLOCO SUBSTITUÍVEL: TESTES -->
**[INSERIR: plano de testes aprovado, testes automatizados, evidências, cobertura e status]**
<!-- FIM BLOCO SUBSTITUÍVEL: TESTES -->

## Capturas de tela e demonstração

Os protótipos visuais aprovados adotam dark mode, grafite e verde-esmeralda. Eles são **referências de design**, não capturas de uma aplicação funcional.

<!-- INÍCIO BLOCO SUBSTITUÍVEL: TELAS -->
**[INSERIR AQUI: imagens finais do Login, Dashboard, Fichas, Biblioteca, Treinar, Perfil e Histórico, preferencialmente salvas em `docs/img/` e referenciadas com Markdown.]**

**[LINK DA DEMONSTRAÇÃO ONLINE — QUANDO PUBLICADA]**
<!-- FIM BLOCO SUBSTITUÍVEL: TELAS -->

## Documentação e histórico

| Documento | Responsabilidade | Situação |
|---|---|---|
| [`docs/regras-negocio.md`](docs/regras-negocio.md) | Regras oficiais e aprovações de todos os módulos | **Aprovado — 189 RN e 49 CT** |
| [`docs/requisitos.md`](docs/requisitos.md) | 46 RF, 25 RNF e critérios de aceitação | **APROVADO — v1.0** |
| [`docs/banco-de-dados.md`](docs/banco-de-dados.md) | Modelo relacional e decisões de persistência | Pendente |
| `database/schema.sql` | Estrutura física do banco | Pendente de implementação |

### Controle de aprovação

| Módulo | Versão | Situação |
|---|---|---|
| Treinar | 1.0 | **Aprovada** |
| Fichas | 1.0 | **Aprovada — RN-F01–RN-F40 e CT-F01–CT-F05** |
| Biblioteca | 1.0 | **Aprovada** |
| Login/Cadastro | 1.0 | **Aprovada** |
| Dashboard | 1.0 | **Aprovada** |
| Perfil | 1.0 | **Aprovada** |
| Histórico | 1.0 | **Aprovada** |
| PWA | 1.0 | **Regras aprovadas, entrega em fases** |
| Metas | 1.0 | **Aprovada — RN-M01–RN-M36 e CT-M01–CT-M07** |

### Histórico de alterações do README

| Data | Mudança |
|---|---|
| 13/11/2026 | README-base com visão geral, requisitos preliminares, Treinar v1.0 aprovada e espaços para os módulos futuros. |
| 13/11/2026 | Inclusão de Fichas v1.0 e aprovação dos módulos Biblioteca, Login/Cadastro, Dashboard, Perfil, Histórico e PWA, incluindo edição abrangente do Histórico. |
| 13/11/2026 | Homologados 46 requisitos funcionais, 25 requisitos não funcionais e critérios de aceitação da especificação v1.0. |
| 13/11/2026 | Aditivo Metas v1.0 aprovado: 36 RN e 7 CT; 46 RF e 25 RNF anteriores continuam inalterados. |

---

<div align="center">

**GymProgress** — Projeto pessoal em desenvolvimento.

</div>


## Banco de dados no desenvolvimento

O arquivo `database/schema.sql` cria automaticamente o banco `gymprogress_teste` com `utf8mb4` e executa `USE gymprogress_teste`. Isso facilita importar o projeto em dispositivos diferentes, sem depender de criar ou selecionar o banco manualmente no phpMyAdmin.
