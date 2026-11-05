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
