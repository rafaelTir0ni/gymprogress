<?php $pageTitle='Corrigir treino';require __DIR__.'/_top.php'; ?>
<a class="back" href="<?= e(routeUrl('historico-detalhes').'&id='.$sid) ?>">← Detalhes</a><h1>Corrigir treino</h1><p class="muted">Edite os dados abaixo. Séries removidas serão excluídas do registro, mas a identidade da sessão será preservada. A correção terá auditoria.</p>
<?php if($notice): ?><p class="alert error" role="alert"><?= e($notice) ?></p><?php endif; ?>
<form id="history-editor" method="post" action="<?= e(routeUrl('historico-salvar')) ?>">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= $sid ?>"><input type="hidden" name="payload" id="hist-payload">
<div class="panel hist-fields"><label>Nome do treino<input id="hist-name" maxlength="120" required value="<?= e($data['session']['nome_ficha_snapshot']) ?>"></label><label>Início (UTC)<input type="datetime-local" id="hist-start" required value="<?= e(str_replace(' ','T',substr($data['session']['iniciada_em'],0,16))) ?>"></label><label>Término (UTC)<input type="datetime-local" id="hist-end" required value="<?= e(str_replace(' ','T',substr($data['session']['finalizada_em'],0,16))) ?>"></label><label>Observações<textarea id="hist-notes" maxlength="5000" rows="3"><?= e($data['session']['observacoes']) ?></textarea></label></div>
<div id="hist-items" class="hist-items"></div><button type="button" class="button secondary" id="hist-add-exercise">+ Adicionar exercício</button>
<div class="panel"><h2>Confirmar correção</h2><p class="muted small">Uma sessão finalizada precisa manter pelo menos uma série concluída. A edição será auditada e refletida nas consultas do gráfico.</p><label>Digite CORRIGIR<input name="confirmar" required pattern="CORRIGIR" placeholder="CORRIGIR" autocomplete="off"></label><button class="primary full" type="submit">Salvar correção</button></div></form>
<script id="hist-initial" type="application/json"><?= json_encode($data['items'],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_THROW_ON_ERROR) ?></script>
<script id="hist-exercises" type="application/json"><?= json_encode($exercises,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_THROW_ON_ERROR) ?></script>
<script defer src="assets/js/historico.js"></script>
<?php require __DIR__.'/_bottom.php'; ?>
