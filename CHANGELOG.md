# Changelog — GymProgress

Este histórico usa marcos reais **documentados**; não recria versões retroativas nem pressupõe tags ou releases publicados no GitHub.

## [Não lançado] — 2026-11-18

### Documentação homologada
- Homologados RN-P09–RN-P16 e CT-P05–CT-P08 para Foto de Perfil, preservando RN-P01–P08 e CT-P01–P04.
- Homologados RF-47–RF-50 e RNF-26–RNF-28 para Foto de Perfil.
- Homologados RF-51–RF-58 e RNF-29–RNF-32 para Metas e Conquistas, preservando RN-M01–M36 e CT-M01–M07.
- README e `docs/` sincronizados integralmente; contagens oficiais: **197 RN, 53 CT, 58 RF e 32 RNF**.
- Corrigida a contagem antiga de 153 RN/42 CT no quadro de aprovação de requisitos.
- **Somente documentação:** nenhum arquivo de código, estrutura SQL ou comportamento do aplicativo foi alterado neste marco.

## [0.1.0-alpha.1] — 2026-11-16

### Implementado antes do primeiro marco documentado
- Autenticação, Fichas, Biblioteca, Treinar, Histórico, Dashboard, Perfil e Metas/Conquistas, com validação manual relatada no Laragon.
- PWA instalável no Windows e Android, usando HTTPS privado via Tailscale e cache estático.
- Foto de perfil com upload, alteração, remoção, acesso autenticado, editor de recorte, zoom e posicionamento.
- Primeira rodada de refinamento visual do Dashboard, Treinar e navbar compartilhada.

### Documentação
- Regras de negócio, controles técnicos e requisitos aprovados consolidados no README, com propostas identificadas separadamente.
- Inventário das implementações, documentação técnica por módulo e política de versões Alpha.
- README reorganizado em um padrão de consulta rápida, inspirado na organização por marcos do DevTrack (sem incorporar seu conteúdo).
- Índice da documentação e política de versionamento adicionados em `docs/`.

### Limitações conhecidas
- Recuperação de senha por e-mail ainda não implementada.
- Sincronização offline de treinos não implementada.
- Aditivos de Metas e Foto de Perfil homologados documentalmente em 18/11/2026 (ver seção Não lançado).
- Testes automatizados de fluxos críticos, revisão de segurança, backup/restauração e medições de desempenho pendentes.
- Refinamento visual das demais telas ainda em andamento.

> **Nota:** funcionalidades acima foram implementadas ao longo de diversos commits anteriores à adoção formal de versões. Esta seção é a linha de base documentada, não a data de cada implementação.
