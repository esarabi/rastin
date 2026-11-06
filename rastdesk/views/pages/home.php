<?php
$h = (array) t('home');
$data = modules_data();
$mods = $data['modules'];
$d = fn($s) => digits((string) $s, $lang);
$mobile = setting('mobile', '0912 601 8213');
?>
<!-- 1. Hero -->
<section class="hero">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="eyebrow pill"><?= e($h['eyebrow']) ?> · <?= e(sprintf(t('common.modules_count'), $d(count($mods)))) ?></p>
      <h1><?= e(site_text('hero_title', 'home.hero_title')) ?></h1>
      <p class="lead"><?= e(site_text('hero_sub', 'home.hero_sub')) ?></p>
      <div class="actions">
        <a class="btn btn-primary btn-lg" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo')) ?></a>
        <a class="btn btn-outline btn-lg" href="<?= e(url($lang, 'pricing')) ?>"><?= e(t('cta.pricing')) ?></a>
      </div>
      <p class="micro"><?= icon('check') ?><?= e(t('cta.microcopy')) ?> · <a href="<?= e(url($lang, 'catalog')) ?>"><?= e(t('cta.catalog')) ?></a></p>
    </div>
    <div class="hero-visual" aria-hidden="true">
      <div class="mock">
        <div class="mock-bar"><i></i><i></i><i></i><span><?= e($h['mock']['title']) ?></span><em><?= e(t('common.sample_view')) ?></em></div>
        <div class="mock-kpis">
          <?php foreach ($h['mock']['kpis'] as $k => $kpi): ?>
            <div><small><?= e($kpi['label']) ?></small><b><?= e($kpi['value']) ?></b><span class="<?= $k === 2 ? 'down' : 'up' ?>"><?= e($kpi['delta']) ?></span></div>
          <?php endforeach; ?>
        </div>
        <div class="mock-pipe">
          <small><?= e($h['mock']['pipeline']) ?></small>
          <?php foreach ($h['mock']['stages'] as $i => $st): ?>
            <div class="stage"><span><?= e($st) ?></span><i style="--w:<?= [92, 68, 44, 26][$i] ?>%"></i></div>
          <?php endforeach; ?>
        </div>
        <div class="mock-toast call"><?= icon('phone') ?><span><?= e($h['mock']['call']) ?></span></div>
        <div class="mock-toast ai"><?= icon('ai') ?><span><?= e($h['mock']['ai']) ?></span></div>
      </div>
    </div>
  </div>
  <div class="wrap">
    <ul class="stats">
      <?php foreach ($h['stats'] as $st): ?><li><b><?= e($st['value']) ?></b><span><?= e($st['label']) ?></span></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- 2. Why -->
<section class="section">
  <div class="wrap">
    <div class="sec-head center">
      <p class="eyebrow"><?= e($h['why_eyebrow']) ?></p>
      <h2><?= e($h['why_title']) ?></h2>
      <p class="lead"><?= e($h['why_sub']) ?></p>
    </div>
    <div class="why-grid">
      <?php foreach ($h['why'] as $w): ?>
        <div class="card"><span class="ico-badge"><?= icon($w['icon']) ?></span><h3><?= e($w['title']) ?></h3><p><?= e($w['text']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 3. Teams -->
<section class="section alt" id="teams">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['teams_eyebrow']) ?></p>
      <h2><?= e($h['teams_title']) ?></h2>
    </div>
    <div class="tabs" data-tabs>
      <div class="tab-list" role="tablist">
        <?php foreach ($h['teams'] as $i => $tm): ?>
          <button type="button" role="tab" id="tab-<?= e($tm['id']) ?>" aria-controls="panel-<?= e($tm['id']) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= icon($tm['icon']) ?><?= e($tm['title']) ?></button>
        <?php endforeach; ?>
      </div>
      <?php foreach ($h['teams'] as $i => $tm): ?>
        <div class="tab-panel" role="tabpanel" id="panel-<?= e($tm['id']) ?>" aria-labelledby="tab-<?= e($tm['id']) ?>">
          <div class="team-copy">
            <h3><?= icon($tm['icon']) ?><?= e($tm['title']) ?></h3>
            <ul class="check-list big"><?php foreach ($tm['points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
            <a class="btn btn-primary" href="<?= e(url($lang, 'demo')) ?>"><?= e(t('cta.demo')) ?></a>
          </div>
          <div class="team-mods">
            <?php foreach ($tm['modules'] as $slug) { $m = $mods[$slug]; require ROOT . '/views/partials/mod_chip.php'; } ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 4. Module explorer -->
<section class="section" id="modules">
  <div class="wrap">
    <div class="sec-head row">
      <div>
        <p class="eyebrow"><?= e($h['modules_eyebrow']) ?></p>
        <h2><?= e($h['modules_title']) ?></h2>
        <p class="lead"><?= e($h['modules_sub']) ?></p>
      </div>
      <a class="btn btn-outline" href="<?= e(url($lang, 'modules')) ?>"><?= e(t('cta.all_modules')) ?> <?= icon('arrow', 'ico flip') ?></a>
    </div>
    <div class="groups">
      <?php foreach ($data['groups'] as $gid => $g): $gm = array_filter($mods, fn($m) => $m['group'] === $gid); ?>
        <div class="group-card">
          <h3><span class="ico-badge sm"><?= icon($g['icon']) ?></span><?= e($g['title']) ?><span class="count"><?= e($d(count($gm))) ?></span></h3>
          <ul>
            <?php foreach ($gm as $slug => $m): ?>
              <li><a href="<?= e(url($lang, 'modules/' . $slug)) ?>"><span><?= e($m['title']) ?></span><span class="ed-badge ed-<?= e($m['edition']) ?>"><?= e(t('editions.' . $m['edition'])) ?></span></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 5. AI -->
<section class="section alt">
  <div class="wrap split">
    <div>
      <p class="eyebrow"><?= e($h['ai_eyebrow']) ?></p>
      <h2><?= e($h['ai_title']) ?></h2>
      <p class="lead"><?= e($h['ai_sub']) ?></p>
      <ul class="check-list big"><?php foreach ($h['ai_points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      <div class="actions">
        <a class="btn btn-outline" href="<?= e(url($lang, 'modules/ai-assistant')) ?>"><?= e($mods['ai-assistant']['title']) ?></a>
        <a class="btn btn-outline" href="<?= e(url($lang, 'modules/call-ai')) ?>"><?= e($mods['call-ai']['title']) ?></a>
      </div>
    </div>
    <div class="chat" aria-hidden="true">
      <div class="chat-head"><?= icon('ai') ?><b><?= e($mods['ai-assistant']['title']) ?></b><em><?= e(t('common.sample_view')) ?></em></div>
      <?php foreach ($h['ai_chat'] as $msg): ?><p class="bubble <?= e($msg['who']) ?>"><?= e($msg['text']) ?></p><?php endforeach; ?>
      <div class="chat-input"><span></span><?= icon('arrow', 'ico flip') ?></div>
    </div>
  </div>
</section>

<!-- 6. Mobile -->
<section class="section">
  <div class="wrap split reverse">
    <div class="phone" aria-hidden="true">
      <div class="phone-screen">
        <div class="ps-head"><img src="<?= e(asset('assets/img/favicon.svg')) ?>" width="22" height="22" alt=""><b><?= e(t('brand.product')) ?></b></div>
        <?php foreach (array_slice($h['mobile_points'], 0, 4) as $i => $pt): ?>
          <div class="ps-row"><span class="dot d<?= $i ?>"></span><span><?= e($pt) ?></span></div>
        <?php endforeach; ?>
        <div class="ps-chart"><?php for ($i = 0; $i < 7; $i++): ?><i style="height:<?= [40, 62, 48, 80, 58, 90, 72][$i] ?>%"></i><?php endfor; ?></div>
      </div>
    </div>
    <div>
      <p class="eyebrow"><?= e($h['mobile_eyebrow']) ?></p>
      <h2><?= e($h['mobile_title']) ?></h2>
      <p class="lead"><?= e($h['mobile_sub']) ?></p>
      <ul class="check-list big"><?php foreach ($h['mobile_points'] as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      <div class="actions"><a class="btn btn-outline" href="<?= e(url($lang, 'modules/mobile-app')) ?>"><?= e(t('cta.learn_more')) ?> <?= icon('arrow', 'ico flip') ?></a></div>
    </div>
  </div>
</section>

<!-- 7. Integrations + industries -->
<section class="section alt">
  <div class="wrap">
    <div class="sec-head center">
      <p class="eyebrow"><?= e($h['integrations_eyebrow']) ?></p>
      <h2><?= e($h['integrations_title']) ?></h2>
    </div>
    <ul class="integrations">
      <?php foreach ($h['integrations'] as $it): ?><li><?= icon($it['icon']) ?><span><?= e($it['title']) ?></span></li><?php endforeach; ?>
    </ul>
    <div class="industries">
      <h3><?= e($h['industries_title']) ?></h3>
      <ul><?php foreach ($h['industries'] as $ind): ?><li><?= e($ind) ?></li><?php endforeach; ?></ul>
    </div>
  </div>
</section>

<!-- 8. Onboarding -->
<section class="section">
  <div class="wrap">
    <div class="sec-head">
      <p class="eyebrow"><?= e($h['onboard_eyebrow']) ?></p>
      <h2><?= e($h['onboard_title']) ?></h2>
      <p class="lead"><?= e($h['onboard_sub']) ?></p>
    </div>
    <ol class="steps">
      <?php foreach ($h['onboard'] as $i => $st): ?><li><span class="step-n"><?= e($d($i + 1)) ?></span><h3><?= e($st['title']) ?></h3><p><?= e($st['text']) ?></p></li><?php endforeach; ?>
    </ol>
    <div class="extras">
      <h3><?= e($h['extras_title']) ?></h3>
      <ul><?php foreach ($h['extras'] as $x): ?><li><b><?= e($x['title']) ?></b><span><?= e($x['text']) ?></span></li><?php endforeach; ?></ul>
    </div>
  </div>
</section>

<!-- 9. Pricing preview -->
<section class="section alt" id="pricing">
  <div class="wrap">
    <div class="sec-head row">
      <div>
        <p class="eyebrow"><?= e($h['pricing_eyebrow']) ?></p>
        <h2><?= e($h['pricing_title']) ?></h2>
        <p class="lead"><?= e($h['pricing_sub']) ?></p>
      </div>
      <a class="btn btn-outline" href="<?= e(url($lang, 'pricing')) ?>#compare"><?= e(t('cta.compare')) ?> <?= icon('arrow', 'ico flip') ?></a>
    </div>
    <div class="plans">
      <?php foreach (EDITIONS as $ed) require ROOT . '/views/partials/edition_card.php'; ?>
    </div>
    <p class="price-note"><?= icon('plus') ?><span><?= e(t('pricing.extra_user')) ?>: <b><?= e(money(setting_raw('extra_user_price', EXTRA_USER_DEFAULT), $lang)) ?> <?= e(t('common.toman')) ?></b> · <?= e(t('pricing.extra_user_text')) ?></span></p>
  </div>
</section>

<!-- 10. FAQ -->
<section class="section">
  <div class="wrap narrow">
    <div class="sec-head center"><h2><?= e($h['faq_title']) ?></h2></div>
    <div class="faq">
      <?php foreach ((array) t('faq') as $i => $f): ?>
        <details<?= $i === 0 ? ' open' : '' ?>><summary><?= e($f['q']) ?><?= icon('plus', 'ico faq-ico') ?></summary><p><?= e($f['a']) ?></p></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 11. Final CTA with inline quick form -->
<section class="final" id="start">
  <canvas class="net" aria-hidden="true"></canvas>
  <div class="wrap final-grid">
    <div class="final-copy">
      <h2><?= e($h['final_title']) ?></h2>
      <p class="lead"><?= e($h['final_sub']) ?></p>
      <ul class="check-list big"><?php foreach ((array) t('demo.points') as $pt): ?><li><?= icon('check') ?><span><?= e($pt) ?></span></li><?php endforeach; ?></ul>
      <p class="call-line"><?= e(t('common.or_call')) ?> <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $mobile)) ?>" dir="ltr"><?= e($d($mobile)) ?></a></p>
    </div>
    <form class="form card" method="post" action="<?= e(url($lang, 'quick')) ?>#start" novalidate>
      <?php if (!empty($quickSuccess)): ?>
        <div class="alert ok" role="status"><?= icon('check') ?><span><?= e(t('demo.quick_success')) ?></span></div>
      <?php elseif (!empty($quickErrors['_form'])): ?>
        <div class="alert err" role="alert"><span><?= e($quickErrors['_form']) ?></span></div>
      <?php endif; ?>
      <?= csrf_field() ?>
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="fields one">
        <?php
        $errors = $quickErrors ?? [];
        $old = $quickOld ?? [];
        foreach ([['name', 'text', true, true], ['phone', 'tel', true, true], ['company', 'text', false, true]] as [$f, $type, $required, $full]) {
            require ROOT . '/views/partials/field.php';
        }
        ?>
      </div>
      <button class="btn btn-primary btn-lg btn-block" type="submit"><?= e(t('form.send_quick')) ?></button>
      <small class="muted"><?= e(t('form.privacy')) ?></small>
    </form>
  </div>
</section>
