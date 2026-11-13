# GymProgress — Autenticação: primeira implementação

**Estado:** cadastro, login, logout e proteção do Dashboard implementados; precisam ser testados no Laragon. O restante do projeto permanece em placeholders. Nenhuma RN/CT/RF/RNF aprovada foi editada.

## Preparação local

1. Use uma **cópia** do projeto em `C:\laragon\www\gymprogress`.
2. Copie `.env.example` para `.env` na raiz (fora de `public/`) e configure os dados do MySQL, com banco que contenha as 12 tabelas. **Use `gymprogress_teste` durante os testes**.
3. Confirme que o PHP do Laragon tem `pdo_mysql` e `mbstring` habilitados.
4. Inicie Apache e MySQL pelo Laragon.
5. Acesse `http://localhost/gymprogress/public/index.php?route=cadastro`. A estrutura funciona sem regras de reescrita. Ideal para produção: DocumentRoot direcionado para `public/`.
6. Crie uma conta de teste com senha de 8+ caracteres. O cadastro deve redirecionar para Login; não faz login automático.
7. Entre, confirme o Dashboard e use Sair. Teste acesso direto ao Dashboard após logout.
8. No phpMyAdmin, confira `usuarios.senha_hash` (jamais deve conter a senha literal).

## Testes sugeridos

- Cadastro válido, e-mail duplicado e senha com menos de 8 caracteres.
- Login válido e inválido; não expor existência de contas nos erros de login.
- Redirecionamento de visitantes ao Login quando tentam visitar Dashboard.
- Logout via POST com CSRF, invalidação da sessão e bloqueio do Dashboard após sair.
- Verificar que requisição POST sem CSRF é recusada.
- Verificar responsividade no celular e uso de cookies de sessão.

## Segurança e pendências explícitas

- Usa PDO com parâmetros e emulação de prepared statements desativada; `password_hash()`/`password_verify()`; CSRF em formulários; escape HTML; cookie HttpOnly/SameSite e Secure quando HTTPS; regeneração do ID na autenticação.
- **Limitação do rate limit:** cinco tentativas por sessão com bloqueio por cinco minutos. Ainda não é proteção suficiente contra tentativas distribuídas ou troca de sessão; requer mecanismo persistente por IP/conta e testes antes de produção.
- **Recuperação por e-mail (RN-A12/RN-A13, RF-04)** ainda NÃO implementada. As views correspondentes permanecem placeholders. Integrar e testar envio por SMTP, tokens de uso único e expiração antes de considerar o módulo de autenticação concluído.
- **Atualização de perfil/senha (RF-35)** permanece pendente no módulo Perfil.
- O Dashboard é temporário e só demonstra a sessão autenticada; os demais módulos ainda não funcionam.
- Ambiente `localhost` usa HTTP para desenvolvimento; implantação real exige HTTPS e configuração segura do servidor Apache para servir apenas `public/`.
- Não utilizar conta MySQL `root` em produção; criar usuário SQL dedicado com permissões mínimas.
- Não publicar `.env` e não armazenar credenciais reais nos arquivos versionados.
