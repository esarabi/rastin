<?php
$s = (array) t('solutions');
$eyebrow = $s['eyebrow'];
$heading = $s['heading'];
$sub = $s['sub'];
$actions = '';
require ROOT . '/views/partials/page_head.php';
$interestFor = ['crm' => 'rastdesk_cloud', 'custom' => 'custom', 'ai' => 'ai', 'speech' => 'speech'];
?>
<section class="section">
  <div class="wrap solutions">
    <?php foreach ($s['items'] as $i => $it): ?>
      <article class="solution" id="<?= e($it['id']) ?>">
        <div class="solution-copy">
          <span class="ico-badge lg"><?= icon($it['icon']) ?></span>
          <h2><?= e($it['title']) ?></h2>
          <p class="lead"><?= e($it['lead']) ?></p>
          <div class="actions">
            <?php if ($it['id'] === 'crm'): ?>
              <a class="btn btn-outline" href="<?= e(url($lang, 'rastdesk')) ?>"><?= e(t('common.learn_more')) ?></a>
            <?php endif; ?>
            <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>?interest=<?= e($interestFor[$it['id']] ?? 'other') ?>#form"><?= e(t('common.request_consult')) ?></a>
          </div>
        </div>
        <ul class="check-list">
          <?php foreach ($it['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?>
        </ul>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head"><h2><?= e($s['industries_title']) ?></h2></div>
    <div class="cards cards-4">
      <?php foreach ($s['industries'] as $ind): ?>
        <div class="card card-flat"><h3><?= e($ind['title']) ?></h3><p><?= e($ind['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php $ctaTitle = t('home.cta_title'); $ctaText = t('home.cta_text'); require ROOT . '/views/partials/cta.php'; ?>
