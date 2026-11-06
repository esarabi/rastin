<section class="section notfound">
  <div class="wrap narrow">
    <img class="mark" src="<?= e(asset('assets/img/rastdesk-icon.svg')) ?>" alt="" width="64" height="64">
    <p class="eyebrow">404</p>
    <h1><?= e(t('errors.not_found_title')) ?></h1>
    <p class="lead"><?= e(t('errors.not_found_text')) ?></p>
    <div class="actions"><a class="btn btn-primary" href="<?= e(url($lang)) ?>"><?= e(t('errors.go_home')) ?></a></div>
  </div>
</section>
