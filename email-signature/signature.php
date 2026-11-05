<?php
/**
 * Rastin email signature (table layout + inline styles, so it survives Gmail, Outlook and Roundcube).
 *
 *   php email-signature/signature.php <fa|en> [image_base_url] > signature-fa.html
 *
 * Images are loaded from the live website (assets/img/email/). Edit $people to add staff.
 */
declare(strict_types=1);
require __DIR__ . '/../website/app/bootstrap.php';

$lang = ($argv[1] ?? 'fa') === 'en' ? 'en' : 'fa';
$img = rtrim($argv[2] ?? 'https://rastingroup.ir/assets/img/email', '/');
$key = $argv[3] ?? 'sarabi';

$people = [
    'sarabi' => [
        'fa' => ['name' => 'ارشاد سرابی', 'title' => 'مدیرعامل'],
        'en' => ['name' => 'Ershad Sarabi', 'title' => 'Chief Executive Officer'],
        'mobile' => '+989126018213',
        'mobile_fa' => '۰۹۱۲ ۶۰۱ ۸۲۱۳',
        'mobile_en' => '+98 912 601 8213',
        'email' => 'info@rastingroup.ir',
    ],
];
$p = $people[$key];
$rtl = $lang === 'fa';
$L = $rtl ? [
    'company' => 'شرکت توسعه هوشمند راستین',
    'office' => '۰۲۱-۸۸۱۰۰۰۵۰',
    'address' => 'تهران، جردن، بلوار آفریقا، ابتدای خیابان دستگردی غربی، پلاک ۲۶۹، طبقه‌ی ۳',
    'tagline' => t('brand.tagline', 'fa'),
    'logo' => 'logo-fa.png', 'logo_w' => 141,
    'notice' => 'این ایمیل و پیوست‌های آن محرمانه است و فقط برای گیرنده‌ی آن ارسال شده است.',
] : [
    'company' => 'Rastin Intelligent Development',
    'office' => '+98 21 8810 0050',
    'address' => '3rd Floor, No. 269, W. Dastgerdi St., Africa Blvd. (Jordan), Tehran, Iran',
    'tagline' => t('brand.tagline', 'en'),
    'logo' => 'logo-en.png', 'logo_w' => 131,
    'notice' => 'This email and any attachments are confidential and intended only for the addressee.',
];
$dir = $rtl ? 'rtl' : 'ltr';
$align = $rtl ? 'right' : 'left';
$font = "font-family:Tahoma,'Segoe UI',Arial,sans-serif";
$ink = '#1F1630';
$muted = '#6B6375';
$coral = '#E8461F'; // slightly deeper coral for small text contrast

$item = function (string $icon, string $text, string $href) use ($img, $font, $ink, $rtl) {
    $pad = $rtl ? 'padding-left:6px' : 'padding-right:6px';
    return '<td style="padding:0 0 4px;vertical-align:middle;white-space:nowrap">'
        . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tr>'
        . '<td style="' . $pad . ';vertical-align:middle;line-height:0"><img src="' . e($img . '/icon-' . $icon . '.png') . '" width="14" height="14" alt="" style="display:block;border:0"></td>'
        . '<td style="vertical-align:middle;' . $font . ';font-size:12px;line-height:18px;color:' . $ink . '"><a href="' . e($href) . '" style="color:' . $ink . ';text-decoration:none" dir="ltr">' . e($text) . '</a></td>'
        . '</tr></table></td>';
};
$gap = '<td style="width:18px;font-size:0">&nbsp;</td>';
?>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" dir="<?= $dir ?>" style="border-collapse:collapse;direction:<?= $dir ?>;text-align:<?= $align ?>;<?= $font ?>;color:<?= $ink ?>;max-width:520px">
  <tr>
    <td style="padding:0 0 12px;vertical-align:middle">
      <div style="<?= $font ?>;font-size:16px;line-height:24px;font-weight:bold;color:<?= $ink ?>"><?= e($p[$lang]['name']) ?></div>
      <div style="<?= $font ?>;font-size:12px;line-height:18px;font-weight:bold;color:<?= $coral ?>"><?= e($p[$lang]['title']) ?></div>
      <div style="<?= $font ?>;font-size:12px;line-height:18px;color:<?= $muted ?>"><?= e($L['company']) ?></div>
    </td>
    <td style="padding:0 18px 12px;vertical-align:middle">
      <div style="width:2px;height:52px;background:#FF5A36;font-size:0;line-height:0">&nbsp;</div>
    </td>
    <td style="padding:0 0 12px;vertical-align:middle">
      <a href="https://rastingroup.ir" style="text-decoration:none"><img src="<?= e($img . '/' . $L['logo']) ?>" width="<?= $L['logo_w'] ?>" height="44" alt="<?= e($L['company']) ?>" style="display:block;border:0"></a>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="padding:10px 0 6px;border-top:1px solid #ECE6E9">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" dir="<?= $dir ?>" style="border-collapse:collapse">
        <tr>
          <?= $item('mobile', $rtl ? $p['mobile_fa'] : $p['mobile_en'], 'tel:' . $p['mobile']) ?><?= $gap ?>
          <?= $item('building', $L['office'], 'tel:+982188100050') ?>
        </tr>
        <tr>
          <?= $item('mail', $p['email'], 'mailto:' . $p['email']) ?><?= $gap ?>
          <?= $item('globe', 'rastingroup.ir', 'https://rastingroup.ir') ?>
        </tr>
        <tr>
          <?= $item('instagram', '@rastdesk', 'https://instagram.com/rastdesk') ?><?= $gap ?>
          <?= $item('globe', 'rastdesk.ir', 'https://rastdesk.ir') ?>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="padding:0 0 8px">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" dir="<?= $dir ?>" style="border-collapse:collapse"><tr>
        <td style="<?= $rtl ? 'padding-left:6px' : 'padding-right:6px' ?>;vertical-align:top;padding-top:2px;line-height:0"><img src="<?= e($img . '/icon-pin.png') ?>" width="14" height="14" alt="" style="display:block;border:0"></td>
        <td style="<?= $font ?>;font-size:11px;line-height:17px;color:<?= $muted ?>"><?= e($L['address']) ?></td>
      </tr></table>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="padding:0 0 6px;<?= $font ?>;font-size:12px;line-height:18px;font-weight:bold;color:<?= $coral ?>"><?= e($L['tagline']) ?></td>
  </tr>
  <tr>
    <td colspan="3" style="<?= $font ?>;font-size:10px;line-height:15px;color:#9A93A3"><?= e($L['notice']) ?></td>
  </tr>
</table>
