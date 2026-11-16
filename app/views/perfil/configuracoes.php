<?php $pageTitle = 'Configurações'; require __DIR__ . '/_top.php'; ?>
<a class="perfil-back" href="<?= e(routeUrl('perfil')) ?>">← Voltar ao perfil</a><h1>Configurações</h1><p class="perfil-muted">O fuso horário define as datas e a contagem dos treinos no Dashboard.</p>
<?php if ($notice !== ''): ?><p class="perfil-alert" role="status"><?= e($notice) ?></p><?php endif; ?>
<?php if ($error !== ''): ?><p class="perfil-error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" action="<?= e(routeUrl('perfil-configuracoes')) ?>" class="perfil-card perfil-form">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label>Fuso horário<select name="timezone">
<?php foreach (['America/Sao_Paulo'=>'Brasília / São Paulo', 'America/Manaus'=>'Manaus', 'America/Belem'=>'Belém', 'America/Rio_Branco'=>'Rio Branco', 'UTC'=>'UTC'] as $value=>$label): ?>
<option value="<?= e($value) ?>" <?= $account['timezone'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
<?php endforeach; ?>
</select></label>
<button type="submit">Salvar preferências</button>
</form>
<?php require __DIR__ . '/_bottom.php'; ?>
