<form method="post" class="panel form-stack" action="<?= e($formAction) ?>">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<?php if ($error !== ''): ?><p class="alert error" role="alert"><?= e($error) ?></p><?php endif; ?>
<label for="nome">Nome da ficha <span aria-hidden="true">*</span></label>
<input id="nome" name="nome" maxlength="120" required value="<?= e($values['nome']) ?>" placeholder="Ex.: Treino A — Peito e tríceps">
<label for="descricao">Descrição <span class="hint">(opcional)</span></label>
<textarea id="descricao" name="descricao" maxlength="5000" rows="3" placeholder="Observações sobre esta ficha"><?= e($values['descricao']) ?></textarea>
<label for="grupo">Grupo muscular principal <span class="hint">(opcional)</span></label>
<input id="grupo" name="grupo_muscular" maxlength="80" value="<?= e($values['grupo_muscular']) ?>" placeholder="Ex.: Peito">
<div class="actions"><button type="submit" class="primary"><?= e($submitLabel) ?></button><a class="button ghost" href="<?= e($cancelUrl) ?>">Cancelar</a></div>
</form>
