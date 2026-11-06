<?php $isNew = empty($post['id']); ?>
<div class="page-title">
  <h1><?= $isNew ? 'مطلب جدید' : 'ویرایش مطلب' ?></h1>
  <?php if (!$isNew && $post['status'] === 'published'): ?><a class="btn" href="<?= e(url($post['lang'], 'blog/' . rawurlencode($post['slug']))) ?>" target="_blank" rel="noopener">مشاهده در سایت ↗</a><?php endif; ?>
</div>
<?php foreach ($errors as $er): ?><div class="flash err"><?= e($er) ?></div><?php endforeach; ?>
<form class="editor-form" method="post" enctype="multipart/form-data" action="<?= e(admin_url('posts/' . ($isNew ? 'new' : $post['id']))) ?>" id="post-form">
  <?= csrf_field() ?>
  <div class="editor-main">
    <label class="big">عنوان<input name="title" id="title" value="<?= e($post['title']) ?>" required></label>
    <div class="editor" data-upload="<?= e(admin_url('upload')) ?>" data-token="<?= e(csrf_token()) ?>">
      <div class="toolbar" role="toolbar" aria-label="ابزار ویرایش">
        <button type="button" data-cmd="formatBlock" data-val="h2" title="تیتر">H2</button>
        <button type="button" data-cmd="formatBlock" data-val="h3" title="زیرتیتر">H3</button>
        <button type="button" data-cmd="formatBlock" data-val="p" title="پاراگراف">¶</button>
        <span class="sep"></span>
        <button type="button" data-cmd="bold" title="پررنگ"><b>B</b></button>
        <button type="button" data-cmd="italic" title="کج"><i>I</i></button>
        <span class="sep"></span>
        <button type="button" data-cmd="insertUnorderedList" title="فهرست">• فهرست</button>
        <button type="button" data-cmd="insertOrderedList" title="فهرست شماره‌دار">۱. فهرست</button>
        <button type="button" data-cmd="formatBlock" data-val="blockquote" title="نقل‌قول">❝</button>
        <span class="sep"></span>
        <button type="button" data-act="link" title="لینک">لینک</button>
        <button type="button" data-act="image" title="تصویر">تصویر</button>
        <button type="button" data-cmd="removeFormat" title="حذف قالب">پاک‌کردن قالب</button>
        <span class="grow"></span>
        <button type="button" data-act="html" title="ویرایش HTML">HTML</button>
      </div>
      <div class="link-bar" hidden>
        <input type="url" dir="ltr" placeholder="https://..." aria-label="نشانی لینک">
        <button type="button" class="btn primary sm" data-act="link-ok">افزودن لینک</button>
        <button type="button" class="btn sm" data-act="link-cancel">انصراف</button>
      </div>
      <div class="content prose" contenteditable="true" dir="<?= e(in_array($post['lang'], ['fa', 'ar'], true) ? 'rtl' : 'ltr') ?>"><?= $post['body'] ?></div>
      <textarea class="html-src" dir="ltr" hidden aria-label="HTML"></textarea>
      <input type="file" accept="image/*" class="img-input" hidden>
      <p class="editor-status muted" aria-live="polite"></p>
    </div>
    <textarea name="body" id="body" hidden><?= e($post['body']) ?></textarea>
    <label>خلاصه (برای کارت مطلب و گوگل؛ اگر خالی بماند از متن ساخته می‌شود)<textarea name="excerpt" rows="3"><?= e($post['excerpt']) ?></textarea></label>
  </div>
  <aside class="editor-side">
    <section class="panel">
      <h2>انتشار</h2>
      <label>وضعیت
        <select name="status">
          <option value="draft"<?= $post['status'] === 'draft' ? ' selected' : '' ?>>پیش‌نویس</option>
          <option value="published"<?= $post['status'] === 'published' ? ' selected' : '' ?>>منتشرشده</option>
        </select>
      </label>
      <label>زمان انتشار (میلادی)<input type="datetime-local" name="published_at" value="<?= e(str_replace(' ', 'T', substr((string) $post['published_at'], 0, 16))) ?>" dir="ltr"></label>
      <p class="muted small">اگر زمانی در آینده انتخاب کنید، مطلب در همان زمان خودکار منتشر می‌شود.</p>
      <button class="btn primary block" type="submit">ذخیره</button>
    </section>
    <section class="panel">
      <h2>زبان و نشانی</h2>
      <label>زبان
        <select name="lang" id="lang">
          <?php foreach (languages() as $l): ?><option value="<?= e($l) ?>"<?= $post['lang'] === $l ? ' selected' : '' ?>><?= e(lang_label($l)) ?></option><?php endforeach; ?>
        </select>
      </label>
      <label>نشانی مطلب (اسلاگ)<input name="slug" id="slug" value="<?= e($post['slug']) ?>" dir="auto" placeholder="از روی عنوان ساخته می‌شود"></label>
    </section>
    <section class="panel">
      <h2>تصویر شاخص</h2>
      <?php if ($post['cover']): ?>
        <img class="cover-prev" src="<?= e(media_url($post['cover'])) ?>" alt="">
        <label class="check"><input type="checkbox" name="remove_cover" value="1"> حذف تصویر</label>
      <?php endif; ?>
      <input type="file" name="cover" accept="image/jpeg,image/png,image/webp,image/gif">
      <p class="muted small">اندازه‌ی پیشنهادی ۱۶۰۰×۹۰۰ پیکسل، حداکثر ۵ مگابایت.</p>
    </section>
  </aside>
</form>
<?php if (!$isNew): ?>
<form class="danger-zone" method="post" action="<?= e(admin_url('posts/' . $post['id'] . '/delete')) ?>" data-confirm="این مطلب برای همیشه حذف شود؟">
  <?= csrf_field() ?><button class="btn danger" type="submit">حذف این مطلب</button>
</form>
<?php endif; ?>
