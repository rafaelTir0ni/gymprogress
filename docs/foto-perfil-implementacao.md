# GymProgress — Foto de perfil v1

**Escopo:** upload, exibição e remoção de foto do titular, usando o `gymprogress_teste`.

## Instalação para banco já existente

Execute **uma vez** `database/migrations/20261116_add_profile_photo.sql` pelo phpMyAdmin, **antes de usar a funcionalidade**. Não execute `database/schema.sql` novamente em uma base populada. Uma instalação nova já inclui `foto_perfil` na tabela `usuarios` do `schema.sql`.

## Implementação

- Cabeçalho do Dashboard: substitui botão Sair por avatar que abre Perfil. Logout permanece no Perfil.
- Página Perfil: selecionar, trocar ou remover foto; sem imagem, mostra inicial do nome.
- `app/helpers/profile_image.php`: gera markup e restringe formato de arquivo interno.
- Foto em `storage/profile-images/`, fora de `public`, excluída do Git. Rota `perfil-foto` exige autenticação e entrega apenas a imagem do usuário logado.
- Upload apenas via POST, CSRF, até 3 MB, MIME e dimensões conferidos; reprocessa com extensão GD para JPEG com até 512 px de lado maior. Necessária extensão PHP GD habilitada.
- Sem alterações em treinos, dados históricos, regras ou requisitos.
- Atualizado nome do cache PWA. PHP e imagens autenticadas não são armazenados em Cache Storage.

## Testes manuais no Laragon

1. Importar migração no banco `gymprogress_teste` e confirmar coluna `foto_perfil`.
2. Abrir Dashboard e verificar avatar inicial no lugar do botão Sair.
3. Clicar no avatar e confirmar abertura de Perfil.
4. Enviar foto PNG, JPEG e/ou WebP válida; conferir prévia no Perfil e cabeçalho.
5. Substituir a foto, recarregar o Dashboard, confirmar atualização.
6. Remover foto e verificar retorno à inicial.
7. Tentar enviar arquivo não-imagem ou maior que 3 MB e conferir bloqueio.
8. Confirmar Logout no Perfil.
9. Repetir upload/visualização no Android pelo PWA com Tailscale.
10. Confirmar que as funções Fichas, Treinar e Histórico seguem funcionando.

**Atenção:** para múltiplos dispositivos, o arquivo fica no computador que executa o Laragon; o banco armazena apenas nome opaco. Se migrar o servidor futuramente, migrar também `storage/profile-images` com cuidado.
