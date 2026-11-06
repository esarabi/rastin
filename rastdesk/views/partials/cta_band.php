<?php /** Closing call to action. Expects $lang, optional $ctaTitle, $ctaText */ ?>
<section class="cta-band">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap cta-inner">
    <div class="cta-copy">
      <h2><?= e($ctaTitle ?? t('home.final_title')) ?></h2>
      <p><?= e($ctaText ?? t('cta.microcopy')) ?></p>
    </div>
    <div class="cta-actions">
      <a class="btn btn-ink btn-lg" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo')) ?></a>
      <a class="btn btn-on-coral btn-lg" href="<?= e(url($lang, 'catalog')) ?>"><?= e(t('cta.catalog')) ?></a>
    </div>
  </div>
</section>
