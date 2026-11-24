(() => {
  const elapsed = document.getElementById('elapsed');
  if (elapsed) {
    const started = new Date(elapsed.dataset.start.replace(' ', 'T') + 'Z').getTime();
    const tick = () => {
      if (!Number.isFinite(started)) return;
      const secs = Math.max(0, Math.floor((Date.now() - started) / 1000));
      const pad = n => String(n).padStart(2, '0');
      elapsed.textContent = `${Math.floor(secs / 3600)}:${pad(Math.floor(secs / 60) % 60)}:${pad(secs % 60)}`;
    };
    tick(); setInterval(tick, 1000);
  }
  function plot(canvas, labels, values) {
    const ctx = canvas.getContext('2d');
    const w = canvas.width, h = canvas.height; ctx.clearRect(0,0,w,h);
    if (!values.length) return false;
    const left=48,right=w-14,top=18,bottom=h-54;
    const max=Math.max(1,...values)*1.15;
    ctx.strokeStyle='#526357';ctx.fillStyle='#b0c6b9';ctx.font='13px system-ui';ctx.lineWidth=1;
    for(let j=0;j<=4;j++){const y=bottom-(bottom-top)*j/4;ctx.beginPath();ctx.moveTo(left,y);ctx.lineTo(right,y);ctx.stroke();ctx.fillText((max*j/4).toFixed(0),5,y+4);}
    const x=i=>left+(right-left)*(values.length===1?0.5:i/(values.length-1));
    const y=v=>bottom-v/max*(bottom-top);
    ctx.strokeStyle='#00d87a';ctx.lineWidth=3;ctx.beginPath();values.forEach((v,i)=>i?ctx.lineTo(x(i),y(v)):ctx.moveTo(x(i),y(v)));ctx.stroke();
    values.forEach((v,i)=>{ctx.fillStyle='#00d87a';ctx.beginPath();ctx.arc(x(i),y(v),4,0,Math.PI*2);ctx.fill(); if(i%Math.max(1,Math.ceil(values.length/6))===0){ctx.fillStyle='#c7ded0';ctx.fillText(labels[i].slice(0,5),x(i)-16,h-20);}});
    return true;
  }
  document.querySelectorAll('[data-graph-button]').forEach(button => button.addEventListener('click', async () => {
    const panel = button.closest('.panel').querySelector('.graph-panel');
    const isOpen = panel.hidden; panel.hidden = !isOpen; button.setAttribute('aria-expanded', String(isOpen));
    if (!isOpen || panel.dataset.loaded) return;
    const info=panel.querySelector('.graph-description'); info.textContent='Carregando...';
    try {
      const url=button.dataset.url+'&exercicio_id='+encodeURIComponent(button.dataset.exerciseId);
      const resp=await fetch(url,{credentials:'same-origin'});if(!resp.ok)throw Error('Não foi possível carregar o gráfico.');
      const data=await resp.json();
      const values=data.values.map(Number); const ok=plot(panel.querySelector('canvas'),data.labels,values);
      info.textContent=ok?'Máximas semanais: '+data.labels.map((l,i)=>l+': '+values[i]+' kg').join(' · '):'Sem cargas registradas em sessões finalizadas para este exercício.';
      panel.dataset.loaded='1';
    } catch (e) {info.textContent=e.message;}
  }));
})();
