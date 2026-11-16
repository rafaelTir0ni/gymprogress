# GymProgress — Foto de perfil v1.1 — editor de recorte

O usuário escolhe a foto, ajusta o enquadramento com arrastar e zoom 1–3×, e confirma o recorte circular na prévia. O arquivo enviado é uma imagem JPEG quadrada de 512 × 512 px. O backend também força recorte quadrado central como proteção adicional, reprocessa via GD e armazena fora da pasta `public`.

- O limite do arquivo escolhido no navegador é 12 MB, máximo 16 megapixels para decodificação segura. O JPEG gerado para upload tem limite de 3 MB.
- A foto no Dashboard tem largura e altura fixas, sem distorcer o cabeçalho.
- As fotos salvas anteriormente continuam compatíveis; uma nova seleção substitui a anterior.
- Não há migração de banco nesta versão.
- O Service Worker passou a usar cache `gymprogress-static-v5-avatar-crop-20261116` para garantir que o JS/CSS novos sejam carregados no Android.

## Testes manuais

1. Selecionar uma foto vertical e verificar a prévia quadrada/circular.
2. Arrastar com mouse e touchscreen e usar zoom.
3. Cancelar e confirmar que não houve upload.
4. Aplicar o recorte e confirmar foto pequena no cabeçalho e no Perfil.
5. Trocar e remover foto; verificar a inicial padrão.
6. Conferir upload inválido e ausência de acesso à imagem sem login.
7. Confirmar funcionamento do PWA e dos módulos existentes.
