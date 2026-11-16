/* Recorte local da foto: o servidor armazena apenas o resultado 512x512. */
(() => {
  const form = document.getElementById('gp-photo-form');
  const input = document.getElementById('foto');
  const dialog = document.getElementById('gp-crop-dialog');
  const canvas = document.getElementById('gp-crop-canvas');
  const zoomControl = document.getElementById('gp-crop-zoom');
  const cancel = document.getElementById('gp-crop-cancel');
  const save = document.getElementById('gp-crop-save');
  const error = document.getElementById('gp-crop-error');
  if (!form || !input || !dialog || !canvas || !zoomControl || !window.DataTransfer || !dialog.showModal) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;
  let img = null;
  let imageURL = null;
  let scale = 1;
  let offsetX = 0;
  let offsetY = 0;
  let uploading = false;
  let dragging = null;
  let prepared = false;

  function showError(message) { error.textContent = message; error.hidden = !message; }
  function clampOffsets() {
    const boundX = Math.max(0, (img.width * scale - 512) / 2);
    const boundY = Math.max(0, (img.height * scale - 512) / 2);
    offsetX = Math.min(boundX, Math.max(-boundX, offsetX));
    offsetY = Math.min(boundY, Math.max(-boundY, offsetY));
  }
  function render() {
    if (!img) return;
    scale = Math.max(512 / img.width, 512 / img.height) * Number(zoomControl.value);
    clampOffsets();
    ctx.clearRect(0, 0, 512, 512);
    ctx.drawImage(img, (512 - img.width * scale) / 2 + offsetX,
      (512 - img.height * scale) / 2 + offsetY, img.width * scale, img.height * scale);
  }
  function reset() {
    dragging = null;
    img = null;
    if (imageURL) URL.revokeObjectURL(imageURL);
    imageURL = null;
    if (!uploading) input.value = '';
    showError('');
  }

  input.addEventListener('change', () => {
    const file = input.files && input.files[0];
    if (!file) return;
    showError('');
    if (!['image/jpeg','image/png','image/webp'].includes(file.type) || file.size > 12 * 1024 * 1024) {
      input.value = '';
      window.alert('Selecione uma imagem JPEG, PNG ou WebP de até 12 MB. O arquivo final será reduzido.');
      return;
    }
    if (imageURL) URL.revokeObjectURL(imageURL);
    imageURL = URL.createObjectURL(file);
    const candidate = new Image();
    candidate.onload = () => {
      if (candidate.width * candidate.height > 16000000 || !candidate.width || !candidate.height) {
        reset();
        window.alert('Imagem muito grande ou inválida (máximo de 16 megapixels).');
        return;
      }
      img = candidate;
      offsetX = offsetY = 0;
      zoomControl.value = '1';
      render();
      dialog.showModal();
    };
    candidate.onerror = () => { reset(); window.alert('Não foi possível abrir esta imagem.'); };
    candidate.src = imageURL;
  });

  zoomControl.addEventListener('input', render);
  canvas.addEventListener('pointerdown', event => {
    if (!img) return;
    dragging = { id: event.pointerId, x: event.clientX, y: event.clientY, ox: offsetX, oy: offsetY };
    canvas.setPointerCapture(event.pointerId);
  });
  canvas.addEventListener('pointermove', event => {
    if (!dragging || dragging.id !== event.pointerId) return;
    const rect = canvas.getBoundingClientRect();
    offsetX = dragging.ox + (event.clientX - dragging.x) * 512 / rect.width;
    offsetY = dragging.oy + (event.clientY - dragging.y) * 512 / rect.height;
    render();
  });
  for (const eventName of ['pointerup', 'pointercancel', 'lostpointercapture']) {
    canvas.addEventListener(eventName, () => { dragging = null; });
  }
  cancel.addEventListener('click', () => dialog.close());
  dialog.addEventListener('close', () => { if (!uploading) reset(); });
  form.addEventListener('submit', event => {
    if (!prepared && img) {
      event.preventDefault();
      showError('Use “Aplicar e salvar” para confirmar o recorte.');
    }
  });
  save.addEventListener('click', () => {
    if (!img || uploading) return;
    save.disabled = true;
    showError('');
    render();
    canvas.toBlob(blob => {
      if (!blob || blob.size > 3 * 1024 * 1024) {
        save.disabled = false;
        showError('Não foi possível preparar a foto. Tente outra imagem.');
        return;
      }
      const files = new DataTransfer();
      files.items.add(new File([blob], 'foto-perfil.jpg', {type: 'image/jpeg'}));
      input.files = files.files;
      prepared = true;
      uploading = true;
      dialog.close();
      form.requestSubmit();
    }, 'image/jpeg', 0.88);
  });
})();
