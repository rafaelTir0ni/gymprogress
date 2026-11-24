# GymProgress — Estado das implementações e pendências (18/11/2026)

> Documento de acompanhamento técnico. Para o texto integral das especificações, consultar `README.MD`, `docs/regras-negocio.md` e `docs/requisitos.md`.

## Homologação atual

- **197 RN, 53 CT, 58 RF e 32 RNF aprovados.** Aditivos de Foto de Perfil e RF/RNF de Metas e Conquistas homologados em 18/11/2026.
- Regras e requisitos previamente aprovados foram preservados.
- `README.MD` contém os documentos normativos integralmente e sincronizados.
- Versão documentada: `0.1.0-alpha.2`. As mudanças desta versão constam no `CHANGELOG.md`.

## Módulos implementados e testados manualmente pelo usuário

| Módulo | Estado |
|---|---|
| Autenticação | Testado no Laragon |
| Fichas | Testado no Laragon |
| Biblioteca | Testado no Laragon, incluindo bloqueio de exercícios equivalentes |
| Treinar | Testado no Laragon |
| Histórico | Testado no Laragon; evolução de carga agrupada por semana |
| Dashboard | Testado no Laragon |
| Perfil | Testado no Laragon |
| Metas e Conquistas | Testado no Laragon |
| Foto de Perfil (upload, recorte, zoom) | Testado, inclusive no Android |
| PWA | Manifesto, ícones, Service Worker e cache validados; instalado no Windows e no Android |

Os resultados são testes manuais relatados pelo usuário, **não** uma certificação de segurança nem testes automatizados exaustivos.

## Ambiente e banco

- PHP/Laragon e MySQL 8.4.3; banco único de desenvolvimento: `gymprogress_teste`.
- `database/schema.sql` cria/seleciona automaticamente esse banco em instalação inicial. Não reimportar sobre dados existentes.
- Foto de Perfil adicionada por migração própria; imagens privadas permanecem fora da pasta pública.
- Android acessa por HTTPS privado via Tailscale; o computador servidor precisa permanecer ativo.

## Pendências reais

1. Refinamento visual completo e testes responsivos adicionais.
2. Recuperação de senha por e-mail (adiada para o aplicativo pessoal).
3. Testes automatizados dos fluxos críticos, testes de segurança e medições de desempenho.
4. Plano testado de backup/restauração de banco e imagens privadas.
5. Sincronização de treinos offline (fase futura; PWA atual depende do servidor).
6. Revisar experiência de atualização do PWA em dispositivos variados.
7. Preparar futura migração para `gymprogress` após testes finais, sem alterar banco atual.

## Índice de referência

Consultar `docs/README.md`, `CHANGELOG.md`, `docs/arquitetura.md`, `docs/banco-de-dados.md` e os documentos de implementação de cada módulo.
