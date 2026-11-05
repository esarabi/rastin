<?php
/** One form field. Expects $f (name), $type, $required, $old, $errors, optional $options, $full */
$id = 'f-' . $f;
$err = $errors[$f] ?? null;
$val = $old[$f] ?? '';
?>
<div class="field<?= !empty($full) ? ' full' : '' ?><?= $err ? ' has-error' : '' ?>">
  <label for="<?= e($id) ?>"><?= e(t('form.' . $f)) ?><?= $required ? ' <span class="req" aria-hidden="true">*</span>' : ' <span class="opt">(' . e(t('form.optional')) . ')</span>' ?></label>
  <?php if ($type === 'textarea'): ?>
    <textarea id="<?= e($id) ?>" name="<?= e($f) ?>" rows="5"<?= $required ? ' required' : '' ?><?= $err ? ' aria-invalid="true" aria-describedby="' . e($id) . '-err"' : '' ?>><?= e($val) ?></textarea>
  <?php elseif ($type === 'select'): ?>
    <select id="<?= e($id) ?>" name="<?= e($f) ?>"<?= $required ? ' required' : '' ?><?= $err ? ' aria-invalid="true" aria-describedby="' . e($id) . '-err"' : '' ?>>
      <option value=""><?= e(t('form.choose')) ?></option>
      <?php foreach ($options as $k => $label): ?><option value="<?= e($k) ?>"<?= (string) $val === (string) $k ? ' selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
    </select>
  <?php else: ?>
    <input id="<?= e($id) ?>" name="<?= e($f) ?>" type="<?= e($type) ?>" value="<?= e($val) ?>"<?= $required ? ' required' : '' ?><?= in_array($type, ['tel', 'email'], true) ? ' dir="ltr"' : '' ?><?= $type === 'tel' ? ' autocomplete="tel" inputmode="tel"' : '' ?><?= $type === 'email' ? ' autocomplete="email"' : '' ?><?= $f === 'name' ? ' autocomplete="name"' : '' ?><?= $f === 'company' ? ' autocomplete="organization"' : '' ?><?= $err ? ' aria-invalid="true" aria-describedby="' . e($id) . '-err"' : '' ?>>
  <?php endif; ?>
  <?php if ($err): ?><p class="field-err" id="<?= e($id) ?>-err"><?= e($err) ?></p><?php endif; ?>
</div>
