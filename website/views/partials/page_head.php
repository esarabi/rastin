<?php /** Inner page header. Expects $eyebrow, $heading, $sub, optional $actions (html) */ ?>
<section class="page-head">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap page-head-inner">
    <p class="eyebrow"><?= e($eyebrow) ?></p>
    <h1><?= e($heading) ?></h1>
    <p class="lead"><?= e($sub) ?></p>
    <?php if (!empty($actions)) echo $actions; ?>
  </div>
</section>
