<section class="section notfound">
  <div class="wrap narrow">
    <?= brand_mark() ?>
    <p class="eyebrow">404</p>
    <h1><?= e(t('errors.not_found_title')) ?></h1>
    <p class="lead"><?= e(t('errors.not_found_text')) ?></p>
    <div class="actions"><a class="btn btn-primary" href="<?= e(url($lang)) ?>"><?= e(t('errors.go_home')) ?></a></div>
  </div>
</section>
