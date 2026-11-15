# Fichas — implementação inicial (GymProgress)

Status: **implementado no código, aguardando testes manuais no Laragon**. Banco utilizado: `gymprogress_teste`. Nenhuma alteração no schema ou regras aprovadas.

## Fluxos disponíveis

- Listar fichas do usuário autenticado; filtrar por todas/ativas/arquivadas e buscar por nome.
- Criar ficha com nome obrigatório, descrição e grupo opcionais; ficha vazia permitida e ativa por padrão.
- Editar os dados da ficha; visualizar detalhes sem iniciar sessão.
- Adicionar exercício da Biblioteca geral ou privada, configurando séries e repetições fixas/intervalo; impedir duplicação.
- Criar exercício privado dentro da ficha, adicionando-o automaticamente à ficha, em transação; URLs de mídia opcionais.
- Alterar prescrição, reordenar exercícios e remover vínculo sem apagar exercício da Biblioteca.
- Arquivar/reativar ficha; excluir ficha sem sessões com confirmação; impedir exclusão quando houver histórico e bloquear alterações com sessão ativa.
- Página mobile-first com menu inferior; links de Treinar/Biblioteca/Perfil aparecem como indisponíveis enquanto não implementados.

## Testes manuais recomendados

1. Acessar `public/index.php?route=fichas` logado e deslogado.
2. Criar ficha vazia; confirmar que ela aparece na lista e no banco `gymprogress_teste`.
3. Criar exercício personalizado e conferir registros em `exercicios` e `ficha_exercicios`.
4. Tentar associar o mesmo exercício duas vezes à mesma ficha: deve ser rejeitado.
5. Adicionar exercício existente a outra ficha; editar séries/repetições; reordenar e remover vínculo.
6. Arquivar a ficha, impedir novas associações, reativar; excluir sem histórico após digitar `EXCLUIR`.
7. Em teste posterior do módulo Treinar, iniciar uma sessão e confirmar bloqueio de alterações, arquivamento e exclusão de ficha vinculada; após finalizar, confirmar preservação do histórico.
8. Validar na tela estreita do celular; verificar menu inferior.

## Limitações

- Integração de inclusão pelo botão `+` da tela Biblioteca depende da implementação completa desse módulo; a adição via ficha já funciona.
- Teste do comportamento com sessões em andamento/finalizadas depende de dados de Treinar.
- Não foram executados testes conectados ao MySQL real do usuário; execute no Laragon antes de registrar como validado.
- A aplicação ainda não expõe recuperação de senha ou módulos Treinar/Histórico funcionais.
