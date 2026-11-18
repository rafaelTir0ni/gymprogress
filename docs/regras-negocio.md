# GymProgress — Regras de Negócio

> **Documento de requisitos aprovados**  
> Aplicação: GymProgress (PWA mobile-first)  
> Estado: documentação incremental por funcionalidade.  
> Última atualização: 18/11/2026.

## Controle de aprovação

| Funcionalidade | Versão | Situação | Conteúdo |
|---|---|---|---|
| Treinar | 1.0 | **Aprovada** | RN-T01 a RN-T35 e CT-T01 a CT-T03 |
| Fichas | 1.0 | **Aprovada** | RN-F01 a RN-F40 e CT-F01 a CT-F05 |
| Biblioteca | 1.0 | **Aprovada** | RN-B01 a RN-B15 e CT-B01 a CT-B06 |
| Login e Cadastro | 1.0 | **Aprovada** | RN-A01 a RN-A16 e CT-A01 a CT-A07 |
| Dashboard | 1.0 | **Aprovada** | RN-D01 a RN-D12 e CT-D01 a CT-D05 |
| Perfil | 1.0 | **Aprovada** | RN-P01 a RN-P08 e CT-P01 a CT-P04 |
| Perfil — Foto de perfil | 1.1 (aditivo) | **Aprovada em 18/11/2026** | RN-P09 a RN-P16 e CT-P05 a CT-P08 |
| Histórico | 1.0 | **Aprovada** | RN-H01 a RN-H15 e CT-H01 a CT-H06 |
| PWA | 1.0 | **Aprovada** | RN-W01 a RN-W12 e CT-W01 a CT-W06 |
| Metas | 1.0 | **Aprovada** | RN-M01 a RN-M36 e CT-M01 a CT-M07 |

---

## 1. Aba Treinar — versão 1.0 (APROVADA)

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

---

---

## 2. Aba Fichas — versão 1.0 (APROVADA)

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


---

## Biblioteca — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-B01 | Validar no servidor a propriedade dos exercícios personalizados. |
| CT-B02 | Impedir que exercícios privados sejam acessados por outros usuários. |
| CT-B03 | Validar que a ficha de destino pertence ao usuário e está ativa. |
| CT-B04 | Proteger a associação contra exercícios duplicados, inclusive no banco de dados. |
| CT-B05 | Utilizar mídias próprias, licenciadas ou autorizadas. |
| CT-B06 | Arquivar exercícios sem apagar referências históricas, permitindo reativação. |

---

## Login e Cadastro — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-A01 | Utilizar `password_hash()` e `password_verify()`. |
| CT-A02 | Utilizar consultas preparadas e validações no backend. |
| CT-A03 | Proteger formulários contra CSRF e saídas HTML contra XSS. |
| CT-A04 | Renovar o identificador de sessão após autenticação. |
| CT-A05 | Utilizar cookies de sessão com configurações seguras. |
| CT-A06 | Não revelar se determinada conta existe durante tentativas de login e recuperação de senha. |
| CT-A07 | Armazenar tokens de recuperação de forma segura e invalidá-los após utilização. |

---

## Dashboard — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-D01 | Considerar apenas sessões finalizadas nos indicadores de frequência. |
| CT-D02 | Adotar segunda-feira como início da semana. |
| CT-D03 | Utilizar uma referência de fuso horário consistente para datas e agrupamentos. |
| CT-D04 | Não contabilizar sessões descartadas. |
| CT-D05 | Evitar consultas desnecessárias ao banco para calcular indicadores. |

---

## Perfil — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-P01 | Validar alterações de nome e e-mail no servidor. |
| CT-P02 | Exigir verificação da senha atual antes de permitir sua alteração pelo Perfil. |
| CT-P03 | Proteger operações sensíveis contra requisições não autorizadas. |
| CT-P04 | Invalidar sessões conforme a política de segurança definida para alterações sensíveis. |

---

## Histórico — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-H01 | Validar todos os dados editáveis (incluindo datas, horários, cargas, repetições, exercícios e estados) antes de salvar correções, respeitando as regras aplicáveis. |
| CT-H02 | Registrar valores anteriores e novos, data e usuário responsável por cada correção. |
| CT-H03 | Recalcular gráficos e estatísticas afetados após cada alteração confirmada. |
| CT-H04 | Preservar a integridade e a identidade da sessão histórica, sem permitir exclusão integral pela interface. |
| CT-H05 | Garantir autorização do proprietário em todas as consultas e atualizações. |
| CT-H06 | Aplicar correções e seus registros de auditoria em uma única transação. |

---

## PWA — versão 1.0 (APROVADA)

### Regras de negócio

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

### Cuidados técnicos aprovados

| Código | Cuidado técnico |
|---|---|
| CT-W01 | Configurar corretamente HTTPS, manifest e service worker. |
| CT-W02 | Não armazenar credenciais ou respostas privadas indiscriminadamente no cache. |
| CT-W03 | Garantir autenticação e autorização no servidor, mesmo em uma PWA instalada. |
| CT-W04 | Tratar adequadamente falhas de rede e operações não confirmadas. |
| CT-W05 | Na versão offline, implementar sincronização idempotente para evitar duplicações. |
| CT-W06 | Testar o comportamento nos navegadores e dispositivos móveis suportados. |


---

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


## Histórico de revisões

| Data | Versão | Alteração |
|---|---|---|
| 13/11/2026 | 1.0 | Registro das 35 regras e dos 3 cuidados técnicos da aba Treinar aprovados pelo usuário. |
| 13/11/2026 | 1.1 | Inclusão das 40 regras e 5 cuidados técnicos da aba Fichas v1.0, aprovados pelo usuário. |
| 13/11/2026 | 1.2 | Aprovação dos 6 módulos restantes: 78 regras e 34 cuidados técnicos; Histórico permite correção abrangente, mas não exclusão integral de sessão. |
| 13/11/2026 | 1.3 | Aditivo Metas v1.0 aprovado: RN-M01–RN-M36 e CT-M01–CT-M07. Total: 189 RN e 49 CT. |

> **Critério editorial:** somente funcionalidades explicitamente aprovadas são incorporadas como regras definitivas. Funcionalidades em discussão ficam fora do corpo de regras aprovadas até validação do usuário.


---

## Aditivo homologado — Perfil: Foto de perfil e enquadramento (APROVADO em 18/11/2026)

> **Homologado em 18/11/2026:** RN-P09 a RN-P16 e CT-P05 a CT-P08. Aditivo referente a funcionalidade já implementada e validada manualmente. Mantêm-se intactas as RN-P01 a RN-P08 e CT-P01 a CT-P04.

| Código | Regra de negócio aprovada |
|---|---|
| RN-P09 | O usuário autenticado poderá adicionar uma foto ao próprio perfil, selecionando uma imagem do dispositivo. |
| RN-P10 | O usuário poderá substituir a foto existente ou removê-la a qualquer momento. |
| RN-P11 | Ao selecionar a foto, o aplicativo oferecerá prévia e ajuste de enquadramento, com arraste e zoom, antes da confirmação. |
| RN-P12 | Cancelar o enquadramento não modificará a foto anteriormente salva. |
| RN-P13 | Na ausência de foto, o aplicativo exibirá um avatar padrão com a inicial do nome do usuário. |
| RN-P14 | A foto será exibida em dimensões apropriadas no Perfil e no cabeçalho; tocar no avatar do cabeçalho abrirá o Perfil. |
| RN-P15 | O envio aceitará apenas imagens em formatos suportados e dentro dos limites de tamanho e dimensões definidos para segurança. |
| RN-P16 | Somente o usuário autenticado poderá consultar, alterar ou remover sua própria foto de perfil. |

| Código | Controle técnico aprovado |
|---|---|
| CT-P05 | Verificar autenticação e CSRF em operações de envio, substituição e remoção; impedir acesso a fotos de outros usuários. |
| CT-P06 | Validar tipo real, tamanho e dimensões da imagem; reprocessar no servidor por GD e salvar com nome interno seguro fora de `public`. |
| CT-P07 | Gerar o recorte quadrado em 512 × 512 pixels para envio e exibi-lo circularmente; limitar o arquivo final a 3 MB e aplicar limites de processamento no navegador. |
| CT-P08 | Não armazenar fotos autenticadas nem respostas PHP personalizadas no cache estático do Service Worker; excluir arquivos antigos com segurança ao substituir/remover. |

**Referências de implementação existentes:** `docs/foto-perfil-implementacao.md` e `docs/foto-perfil-enquadramento-v1-1.md`.

**Consolidação após homologação (18/11/2026):** 197 RN e 53 CT aprovados no total (189 RN + 8 RN; 49 CT + 4 CT).
