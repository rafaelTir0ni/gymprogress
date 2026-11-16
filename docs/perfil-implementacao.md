# GymProgress — Perfil v1

**Status:** implementação entregue para testes no Laragon. Não homologada em produção.

## Escopo
- RN-P01 a RN-P08; CT-P01 a CT-P04: consulta e edição de nome/e-mail, senha atual obrigatória para trocar senha, histórico, configurações, logout e autorização pelo usuário logado.
- Configuração pessoal de fuso horário para o Dashboard; armazenamento de horários dos treinos permanece UTC.
- Navegação inferior compartilhada em todas as páginas internas; links Início, Fichas, Treinar, Biblioteca e Perfil.

## Segurança
- Todas as rotas do Perfil exigem login; atualizações apenas por `currentUserId()`.
- POST com CSRF e validação no servidor; e-mail único pela restrição do banco; hash com `password_hash()`.
- Alterar senha encerra a **sessão atual** e exige novo login. O banco atual não dispõe de revogação global das sessões em outros dispositivos; não afirmar que outras sessões são automaticamente encerradas.
- Projeto pessoal local, banco padrão `gymprogress_teste`, sem alteração no `schema.sql`.

## Testar no Laragon
1. Abrir `?route=perfil` após login, conferir nome/e-mail.
2. Alterar nome/e-mail e verificar atualização no banco e Dashboard.
3. Testar e-mail duplicado e campo inválido: deve rejeitar.
4. Tentar alterar senha com senha atual errada: deve rejeitar.
5. Alterar senha corretamente: deve encerrar sessão e solicitar novo login; senha antiga deixa de funcionar.
6. Alterar fuso horário e verificar a preferência após atualizar a página.
7. Abrir Histórico e usar logout pelo Perfil.
8. Conferir que Perfil e formulários não abrem sem autenticação.
9. Conferir a navbar em todas as páginas e o destaque do item selecionado, inclusive no celular.
10. Confirmar que Fichas, Biblioteca, Treinar e Histórico continuam funcionando.

**Pendência:** Metas e conquistas serão integradas quando esse módulo estiver implementado. A revogação global de sessões exige infraestrutura própria.
