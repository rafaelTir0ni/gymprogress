(() => {
 const type=document.getElementById('meta-tipo'), wrap=document.getElementById('meta-exercise-wrap');
 if(!type||!wrap)return;
 const change=()=>{wrap.hidden=type.value!=='carga';const select=wrap.querySelector('select');if(select)select.required=type.value==='carga';};
 type.addEventListener('change',change);change();
})();
