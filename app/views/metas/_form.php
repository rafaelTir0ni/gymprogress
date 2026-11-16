<?php if($error!==''): ?><p class="meta-error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form class="meta-form" method="post">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label>Título <input name="titulo" maxlength="150" required value="<?= e((string)($_POST['titulo']??$editing['titulo']??'')) ?>" placeholder="Ex.: Supino 50 kg"></label>
<label>Tipo<select name="tipo" id="meta-tipo">
<?php foreach(['carga'=>'Carga máxima','frequencia_semanal'=>'Frequência semanal','sessoes_acumuladas'=>'Treinos acumulados'] as $v=>$label): ?><option value="<?= e($v) ?>" <?= ($_POST['tipo']??$editing['tipo']??'carga')===$v?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?>
</select></label>
<label id="meta-exercise-wrap">Exercício<select name="exercicio_id"><option value="">Selecione</option><?php foreach($exercises as $ex): ?><option value="<?= (int)$ex['id'] ?>" <?= (string)($_POST['exercicio_id']??$editing['exercicio_id']??'')===(string)$ex['id']?'selected':'' ?>><?= e($ex['nome'].' — '.$ex['grupo_muscular']) ?></option><?php endforeach; ?></select></label>
<label>Valor-alvo<input name="valor_alvo" required type="number" step="0.01" min="0.01" value="<?= e((string)($_POST['valor_alvo']??$editing['valor_alvo']??'')) ?>"></label>
<label>Data de início<input name="inicio_em" type="date" required value="<?= e((string)($_POST['inicio_em']??$editing['inicio_em']??($todayLocal??date('Y-m-d')))) ?>"></label>
<label>Prazo (opcional)<input name="prazo_em" type="date" value="<?= e((string)($_POST['prazo_em']??$editing['prazo_em']??'')) ?>"></label>
<button type="submit"><?= $editing?'Salvar alterações':'Criar meta' ?></button>
</form>
<script src="assets/js/metas.js" defer></script>
