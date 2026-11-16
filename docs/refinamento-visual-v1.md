# GymProgress — Refinamento visual v1

**Situação:** implementação visual pronta para testes no Laragon e no Android.
**Banco:** `gymprogress_teste`, sem alterações de esquema ou dados.

## Escopo realizado

- Base compartilhada em `public/assets/css/style.css`: tokens de cor, superfícies, bordas, cantos, sombra, foco de teclado, espaçamento mobile e comportamento da navbar inferior.
- Dashboard: melhor hierarquia da saudação, cards de indicadores, áreas de toque, botão principal e painel de treino ativo.
- Treinar: navegação por exercícios, contraste dos estados, campos de repetições/carga e ações de série com áreas de toque maiores.
- Demais módulos que carregam o CSS compartilhado: superfícies e formulários padronizados sem mudanças de HTML ou regras de negócio.
- Service Worker: nova versão de cache estático para que os estilos atualizados sejam carregados no PWA instalado.

## O que foi preservado

- Todas as rotas, controllers, models, views, ações de formulários e validações existentes.
- Autenticação, Fichas, Biblioteca, Treinar, Histórico, Dashboard, Perfil, Metas e Conquistas.
- PWA com cache limitado a recursos estáticos; páginas e respostas autenticadas continuam com acesso pela rede.
- Esquema MySQL e configuração do banco de desenvolvimento.

## Roteiro de verificação

1. No computador, limpar apenas cache de imagens/arquivos do navegador ou atualizar com `Ctrl+F5` se a nova aparência não carregar.
2. Abrir Dashboard: conferir saudação, contadores, último treino, botões, links e navbar.
3. Abrir Fichas e Biblioteca: conferir cards, campos, filtros e formulários sem cortes na largura do celular.
4. Iniciar ou retomar um treino no Android: conferir círculos dos exercícios, campos, botões de salvar/concluir e visualização do gráfico. Registrar e concluir uma série de teste, se desejado.
5. Abrir Histórico, Perfil e Metas: conferir legibilidade, campos e navegação inferior; sem perda de dados.
6. Conferir Service Worker e Cache Storage; a nova chave é `gymprogress-static-v2-visual-20261115`.
7. Fechar e reabrir o PWA no Android. Se os estilos antigos persistirem, feche todas as janelas do app, abra novamente e verifique se o novo Service Worker assumiu o controle. Não use “Limpar dados do site” sem necessidade.
8. Verificar paisagem/vertical e largura reduzida (ex.: 360px); campos e botões devem permanecer acessíveis.

## Limites da etapa

É a **primeira rodada** de refinamento visual, não a revisão final de todas as telas. Não foram alterados processos de negócio, cálculos de metas, persistência, gráficos ou estrutura da base.


## Correção visual v1.1 — Dashboard

O Dashboard recebeu uma atualização de layout perceptível: painel de boas-vindas destacado, cards de frequência com visual renovado, bloco do último treino e disposição em duas colunas no desktop. A navbar e a lógica PHP foram preservadas. A versão do cache PWA foi incrementada para que o Android busque o novo CSS.
