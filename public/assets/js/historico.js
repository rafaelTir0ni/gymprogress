(() => {
  'use strict';
  const form=document.getElementById('history-editor');if(!form)return;
  const root=document.getElementById('hist-items');
  const initial=JSON.parse(document.getElementById('hist-initial').textContent);
  const catalog=JSON.parse(document.getElementById('hist-exercises').textContent);
  const text=(tag,value,cls)=>{const n=document.createElement(tag);n.textContent=value;if(cls)n.className=cls;return n;};
  const button=(caption,callback,cls='secondary')=>{const b=text('button',caption,`button ${cls}`);b.type='button';b.addEventListener('click',callback);return b;};
  const input=(type,value,options={})=>{const el=document.createElement('input');el.type=type;el.value=value??'';for(const [k,v] of Object.entries(options))el.setAttribute(k,v);return el;};
  const field=(caption,el)=>{const wrap=document.createElement('label');wrap.append(text('span',caption),el);return wrap;};
  const setsFrom=(item)=>item.sets.map(s=>({done:Number(s.concluida)===1,reps:s.repeticoes??'',weight:s.carga_kg??'',origin:s.origem??'planejada'}));
  const items=initial.map(item=>({id:Number(item.id),exercise_id:Number(item.exercicio_id),name:item.nome_exercicio_snapshot,planned:Number(item.series_planejadas_snapshot),min:Number(item.reps_min_snapshot),max:Number(item.reps_max_snapshot),early:item.status==='concluido_antecipadamente',sets:setsFrom(item)}));
  function draw(){
    root.replaceChildren();
    items.forEach((item,idx)=>{
      const card=document.createElement('section');card.className='panel hist-edit-card';
      card.append(text('h2',`Exercício ${idx+1}`));
      const select=document.createElement('select');select.required=true;
      const options=catalog.slice();
      if(!options.some(x=>Number(x.id)===item.exercise_id))options.unshift({id:item.exercise_id,nome:item.name,grupo_muscular:'Registro histórico'});
      options.forEach(ex=>{const o=document.createElement('option');o.value=ex.id;o.textContent=`${ex.nome} · ${ex.grupo_muscular}`;select.append(o);});select.value=String(item.exercise_id);
      select.addEventListener('change',()=>{item.exercise_id=Number(select.value);});
      card.append(field('Exercício',select));
      const config=document.createElement('div');config.className='hist-config';
      for(const [prop,label] of [['planned','Séries planejadas'],['min','Reps mínimas'],['max','Reps máximas']]){
        const inp=input('number',item[prop],{min:'1',max:'65535',step:'1',required:''});inp.addEventListener('change',()=>{item[prop]=inp.value;});config.append(field(label,inp));
      }
      const early=input('checkbox','');early.checked=item.early;early.addEventListener('change',()=>{item.early=early.checked;});
      const earlyLabel=field('Conclusão antecipada (quando houver séries concluídas)',early);config.append(earlyLabel);card.append(config);
      const table=document.createElement('div');table.className='hist-sets';
      item.sets.forEach((set,j)=>{
        const row=document.createElement('div');row.className='hist-set-edit';row.append(text('strong',`Série ${j+1}`));
        const reps=input('number',set.reps,{min:'1',max:'65535',step:'1'});reps.addEventListener('change',()=>{set.reps=reps.value;});
        const weight=input('text',set.weight,{inputmode:'decimal',placeholder:'kg, opcional'});weight.addEventListener('change',()=>{set.weight=weight.value;});
        const complete=input('checkbox','');complete.checked=set.done;complete.addEventListener('change',()=>{set.done=complete.checked;});
        row.append(field('Reps',reps),field('Carga (kg)',weight),field('Feita',complete),button('Remover série',()=>{item.sets.splice(j,1);draw();},'danger'));
        table.append(row);
      });card.append(table);
      card.append(button('+ Série',()=>{if(item.sets.length>=200)return alert('Máximo de 200 séries por exercício.');item.sets.push({done:false,reps:'',weight:'',origin:'extra'});draw();}));
      card.append(button('Remover exercício',()=>{if(confirm('Remover este exercício do registro histórico?')){items.splice(idx,1);draw();}},'danger'));
      root.append(card);
    });
  }
  document.getElementById('hist-add-exercise').addEventListener('click',()=>{
    if(!catalog.length)return alert('Cadastre um exercício na Biblioteca antes de adicioná-lo.');
    if(items.length>=100)return alert('Limite de 100 exercícios por sessão.');
    items.push({id:0,exercise_id:Number(catalog[0].id),name:'',planned:3,min:8,max:12,early:false,sets:[{done:false,reps:'',weight:'',origin:'extra'}]});draw();
  });
  form.addEventListener('submit',(event)=>{
    // Preserve the latest input, even if the user did not blur the last field.
    root.querySelectorAll('.hist-edit-card').forEach((card,i)=>{
      const item=items[i],fields=card.querySelectorAll('.hist-config input[type="number"]');
      item.planned=fields[0].value;item.min=fields[1].value;item.max=fields[2].value;
      card.querySelectorAll('.hist-set-edit').forEach((row,j)=>{
        const inputs=row.querySelectorAll('input');item.sets[j].reps=inputs[0].value;item.sets[j].weight=inputs[1].value;item.sets[j].done=inputs[2].checked;
      });
    });
    if(!items.some(item=>item.sets.some(s=>s.done))){event.preventDefault();alert('Conclua pelo menos uma série antes de salvar.');return;}
    if(!confirm('Confirmar a correção deste treino finalizado?')){event.preventDefault();return;}
    document.getElementById('hist-payload').value=JSON.stringify({name:document.getElementById('hist-name').value,start:document.getElementById('hist-start').value,end:document.getElementById('hist-end').value,notes:document.getElementById('hist-notes').value,items});
  });
  draw();
})();
