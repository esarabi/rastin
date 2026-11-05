<?php /** Coral call-to-action band. Expects $lang, $ctaTitle, $ctaText */ ?>
<section class="cta-band">
  <div class="wrap cta-inner">
    <div class="cta-copy">
      <h2><?= e($ctaTitle) ?></h2>
      <p><?= e($ctaText) ?></p>
    </div>
    <div class="cta-actions">
      <a class="btn btn-ink btn-lg" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('common.request_demo')) ?></a>
      <a class="btn btn-on-coral btn-lg" href="<?= e(url($lang, 'contact')) ?>"><?= e(t('common.contact_us')) ?></a>
    </div>
  </div>
</section>
