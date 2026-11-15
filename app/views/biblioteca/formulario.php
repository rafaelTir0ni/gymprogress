<?php $pageTitle=isset($own)?'Editar exercício':'Criar exercício'; require __DIR__.'/_top.php'; ?>
<a class="back" href="<?= e(isset($own)?routeUrl('biblioteca-detalhes').'&id='.(int)$id:routeUrl('biblioteca')) ?>">← Voltar à Biblioteca</a>
<h1><?= isset($own)?'Editar exercício':'Novo exercício' ?></h1><p class="muted">Exercícios personalizados ficam disponíveis somente para sua conta.</p>
<?php if ($error !== ''): ?><p class="alert error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form class="panel form-stack" method="post" action="<?= e(isset($own)?routeUrl('biblioteca-editar').'&id='.(int)$id:routeUrl('biblioteca-criar')) ?>">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label>Nome<input type="text" name="nome" required maxlength="150" value="<?= e($values['nome']) ?>" placeholder="Ex.: Rosca inclinada"></label>
<label>Grupo muscular<select name="grupo_muscular" required><option value="">Selecione</option><?php foreach(Exercicio::GROUPS as $g): ?><option value="<?= e($g) ?>" <?= $values['grupo_muscular']===$g?'selected':'' ?>><?= e(['Bracos'=>'Braços','Abdomen'=>'Abdômen'][$g]??$g) ?></option><?php endforeach; ?></select></label>
<label>Descrição (opcional)<textarea name="descricao" maxlength="5000" rows="4" placeholder="Instruções, equipamentos e observações"><?= e($values['descricao']) ?></textarea></label>
<label>URL de imagem (opcional)<input type="url" name="imagem_url" maxlength="500" placeholder="https://..." value="<?= e($values['imagem_url']) ?>"></label>
<label>URL de vídeo (opcional)<input type="url" name="video_url" maxlength="500" placeholder="https://..." value="<?= e($values['video_url']) ?>"></label>
<p class="muted small">Use apenas mídias próprias ou autorizadas. A Biblioteca abrirá os links externamente.</p>
<button type="submit" class="primary"><?= isset($own)?'Salvar alterações':'Criar exercício' ?></button></form>
<?php require __DIR__.'/_bottom.php'; ?>
