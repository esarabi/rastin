<?php if ($success): ?>
  <div class="alert ok" role="status"><?= icon('check') ?><span><?= e($successText) ?></span></div>
<?php elseif (!empty($errors['_form'])): ?>
  <div class="alert err" role="alert"><span><?= e($errors['_form']) ?></span></div>
<?php endif; ?>
