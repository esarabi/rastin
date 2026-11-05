# اقلام اداری راستین

## کارت ویزیت (۸۵×۵۵ میلی‌متر)
| فایل | کاربرد |
|---|---|
| `build/business-card-print-3mm-bleed.pdf` | برای چاپخانه: ۳ صفحه (رو، پشت فارسی، پشت انگلیسی) در اندازه‌ی ۹۱×۶۱ با ۳ میلی‌متر حاشیه‌ی برش |
| `build/business-card-digital.pdf` | نسخه‌ی دیجیتال بدون حاشیه‌ی برش |
| `build/business-card-*.png` | پیش‌نمایش |

برای چاپ دورو، صفحه‌ی ۱ (رو) را با صفحه‌ی ۲ (پشت فارسی) یا صفحه‌ی ۳ (پشت انگلیسی) چاپ کنید.
پیشنهاد: مقوای ۳۵۰ گرم با روکش مات (سلفون مات)، و در صورت تمایل لمینت مخملی یا یووی موضعی روی لوگو.
کد QR پشت کارت اطلاعات تماس را مستقیم در گوشی ذخیره می‌کند (vCard).

برای ساخت کارت برای همکار دیگر، آرایه‌ی `$person` در `business-card.php` را ویرایش کنید و QR را دوباره بسازید، سپس:
```bash
cd stationery && php business-card.php 3 > build/business-card-print.html && node ../tools/stationery_pdf.js build/business-card-print.html build/business-card-print-3mm-bleed.pdf 91 61
```

## سربرگ (A4)
| فایل | کاربرد |
|---|---|
| `build/letterhead-fa.docx` / `letterhead-en.docx` | **قالب Word** برای نوشتن نامه: طرح سربرگ پشت متن در همه‌ی صفحه‌ها تکرار می‌شود. شماره، تاریخ، پیوست، متن و امضا قابل ویرایش‌اند |
| `build/letterhead-fa-print-3mm-bleed.pdf` / `-en-` | برای چاپ سربرگ خام در چاپخانه (۲۱۶×۳۰۳ با حاشیه‌ی برش) |
| `build/letterhead-fa.pdf` / `-en.pdf` | نسخه‌ی دیجیتال A4 |

قالب Word با فونت **وزیرمتن** تنظیم شده است. فایل‌های فونت را از `brand/fonts/` روی رایانه‌ها نصب کنید.
اگر روی سربرگ چاپ‌شده در چاپخانه نامه پرینت می‌گیرید، تصویر پس‌زمینه را در Word حذف کنید (دوبار کلیک روی سرصفحه → انتخاب تصویر → Delete).

## پاکت
| فایل | اندازه |
|---|---|
| `build/envelope-dl-print-3mm-bleed.pdf` | پاکت نامه‌ای (ملخی) ۲۲۰×۱۱۰ |
| `build/envelope-c4-print-3mm-bleed.pdf` | پاکت A4 (C4) ۲۲۹×۳۲۴ |

## ساخت دوباره
```bash
cd stationery
php letterhead.php 3 fa print > build/lh-fa-print.html && node ../tools/stationery_pdf.js build/lh-fa-print.html build/letterhead-fa-print-3mm-bleed.pdf 216 303
php envelope.php 3 dl > build/env-dl-print.html && node ../tools/stationery_pdf.js build/env-dl-print.html build/envelope-dl-print-3mm-bleed.pdf 226 116
# قالب Word (پس از ساخت تصویر پس‌زمینه‌ی letterhead-*-bg.jpg):
python3 ../tools/build_letterhead_docx.py
```
