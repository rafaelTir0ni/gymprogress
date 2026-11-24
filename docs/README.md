# Documentação técnica — GymProgress

**Versão documental:** `v0.1.0-alpha.2` · **Status:** pré-lançamento, uso pessoal

Este índice é o ponto de entrada dos arquivos de `docs/`. O [README principal](../README.MD) apresenta a visão geral, o roadmap, o histórico e a **íntegra** das RN, CT, RF e RNF. Os documentos abaixo são as fontes de especificação e implementação: evite criar cópias divergentes.

## Especificações oficiais

- [Regras de negócio e controles técnicos](regras-negocio.md) — 197 RN e 53 CT aprovados, incluindo Foto de Perfil.
- [Requisitos funcionais e não funcionais](requisitos.md) — 58 RF e 32 RNF aprovados, incluindo Foto de Perfil e Metas/Conquistas.
- [Modelagem e validação do banco](banco-de-dados.md) — MySQL 8.4.3, `gymprogress_teste` durante o desenvolvimento.
- [Arquitetura](arquitetura.md) — MVC simplificado.
- [Estrutura e pendências](estrutura-e-pendencias.md) — registro histórico; para estado corrente priorize README principal e CHANGELOG.

## Implementações por módulo

| Módulo | Documento |
|---|---|
| Autenticação | [autenticacao-implementacao.md](autenticacao-implementacao.md) |
| Fichas | [fichas-implementacao.md](fichas-implementacao.md) |
| Biblioteca | [biblioteca-implementacao.md](biblioteca-implementacao.md) |
| Treinar | [treinar-implementacao.md](treinar-implementacao.md) |
| Histórico | [historico-implementacao.md](historico-implementacao.md) |
| Dashboard | [dashboard-implementacao.md](dashboard-implementacao.md) |
| Perfil | [perfil-implementacao.md](perfil-implementacao.md) |
| Foto de perfil | [foto-perfil-implementacao.md](foto-perfil-implementacao.md) |
| Editor de foto e zoom | [foto-perfil-enquadramento-v1-1.md](foto-perfil-enquadramento-v1-1.md) |
| Metas e conquistas | [metas-implementacao.md](metas-implementacao.md) |
| PWA | [pwa-implementacao.md](pwa-implementacao.md) |
| Refinamento visual inicial | [refinamento-visual-v1.md](refinamento-visual-v1.md) |

## Política de versionamento

Consulte [VERSION](../VERSION) e [CHANGELOG.md](../CHANGELOG.md). O GymProgress utiliza versões pré-lançamento; `0.1.0-alpha.1` é o **primeiro marco documentado**, não uma reconstrução de tags históricas.

**Homologação em 18/11/2026:** os aditivos da Foto de Perfil e dos RF/RNF de Metas e Conquistas estão aprovados. O texto integral está sincronizado com o README principal. Aprovação documental não equivale a teste automatizado completo.
