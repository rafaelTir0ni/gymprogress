# GymProgress — PWA v1 (instalação e cache estático)

**Estado:** implementado no código, aguardando validação no Laragon e no celular.

## Escopo desta versão
- `public/manifest.webmanifest`: escopo relativo a `public/`, tela inicial `index.php?route=dashboard`, cores e ícones instaláveis.
- Ícones PNG 192 × 192, 512 × 512 e maskable 512 × 512.
- Registro de `public/service-worker.js` em `public/assets/js/main.js` quando a origem é segura.
- Links para manifesto, ícones e script nas telas de Autenticação, Dashboard, Fichas, Biblioteca, Treinar, Histórico, Perfil e Metas.
- Cache **somente** dos arquivos estáticos explicitamente declarados e `offline.html`.
- Navegação para páginas privadas sempre depende da rede; em falha de rede, é apresentada apenas a página informativa genérica.
- Não armazena HTML autenticado, resultados de API, senha, sessão, respostas de formulários, séries ou dados pessoais no Cache Storage.
- Uma atualização do Service Worker substitui o cache estático de versão antiga durante a ativação. Abas antigas podem permanecer na versão anterior até serem fechadas/reabertas; o navegador controla o ciclo de atualização.

## Endereços
- No PC: `http://localhost/gymprogress/public/index.php?route=dashboard`.
- Os caminhos do manifesto (`./`) são relativos à pasta `public`, funcionando sob `/gymprogress/public/` ou outro caminho equivalente.
- No celular, acessar o endereço `http://IP-DO-PC/...` **não é uma origem segura** para Service Workers. Para instalar pelo navegador e validar o SW em celular, configurar HTTPS com certificado confiável para o dispositivo, sem exposição pública desnecessária. O acesso local simples por IP pode funcionar para navegar, mas não habilita instalação confiável do PWA.
- `localhost` do smartphone refere-se ao próprio smartphone, não ao Laragon no PC.

## Roteiro de testes — PC
1. Abrir Laragon, fazer login e carregar o Dashboard pelo navegador Chromium.
2. No DevTools > Application > Manifest: conferir nome, `start_url`, escopo e três ícones.
3. Em Application > Service Workers: verificar ativação e escopo limitados a `/gymprogress/public/`.
4. Confirmar que o aplicativo pode ser instalado no navegador em `localhost` (a interface depende do navegador).
5. Em Application > Cache storage: conferir somente os estáticos autorizados, sem respostas de `index.php?route=...`.
6. Fazer cadastro/login/logout e registrar/finalizar treino normalmente, com servidor online.
7. Simular rede offline: ao navegar para uma rota não carregada, exibir `offline.html`. Não permitir salvar treino offline.
8. Voltar online: conferir treinos e histórico persistidos no MySQL, sem duplicações.
9. Alterar arquivo de Service Worker e versão de cache em nova atualização e verificar substituição depois da ativação.

## Roteiro de testes — celular
1. Configurar acesso HTTPS válido e confiável no celular para o servidor de testes.
2. Abrir o GymProgress no navegador, fazer login e testar navegação.
3. Instalar: Chrome/Android pela opção Instalar app (quando disponível) ou Safari/iOS pela opção Adicionar à Tela de Início.
4. Abrir pelo ícone, verificar modo independente e rota inicial corretamente direcionada ao Dashboard ou Login.
5. Alternar Wi-Fi para simular indisponibilidade e verificar a mensagem informativa; não gravar registros offline.

## Fora do escopo do PWA v1
- Sincronização offline de séries ou treinos; push; atualização em segundo plano; hospedagem de produção; redesign visual.
- A etapa de refinamento da UI/UX será feita após os módulos, conforme planejamento.

## Arquivos alterados
- `public/manifest.webmanifest`, `public/service-worker.js`, `public/offline.html`, `public/assets/js/main.js`, `public/.htaccess`.
- `public/assets/img/icons/icon-192.png`, `icon-512.png` e `icon-maskable-512.png`.
- Oito entradas HTML/layout PHP com manifesto, ícones e registro.
- `docs/estrutura-e-pendencias.md` e este documento.

**Banco de dados:** nenhuma modificação. Manter `DB_DATABASE=gymprogress_teste`.
