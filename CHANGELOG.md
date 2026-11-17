# Changelog — GymProgress

Este histórico usa marcos reais **documentados**; não recria versões retroativas nem pressupõe tags ou releases publicados no GitHub.

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
- RF/RNF adicionais de Metas e aditivo da Foto de Perfil ainda sem homologação formal.
- Testes automatizados de fluxos críticos, revisão de segurança, backup/restauração e medições de desempenho pendentes.
- Refinamento visual das demais telas ainda em andamento.

> **Nota:** funcionalidades acima foram implementadas ao longo de diversos commits anteriores à adoção formal de versões. Esta seção é a linha de base documentada, não a data de cada implementação.
