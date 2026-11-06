<?php /** Inner page header. Expects $eyebrow, $heading, $sub, optional $actions (html) */ ?>
<section class="page-head">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap page-head-inner">
    <?php if (!empty($crumbs)) echo $crumbs; ?>
    <p class="eyebrow"><?= e($eyebrow) ?></p>
    <h1><?= e($heading) ?></h1>
    <?php if ($sub !== ''): ?><p class="lead"><?= e($sub) ?></p><?php endif; ?>
    <?php if (!empty($actions)) echo $actions; ?>
  </div>
</section>
