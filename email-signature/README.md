# امضای ایمیل راستین

- **صفحه‌ی نصب برای همکاران:** پس از راه‌اندازی سایت، `https://rastingroup.ir/email-signature.html` را باز کنید، روی «کپی امضا» بزنید و طبق راهنمای همان صفحه در Gmail، Outlook، Roundcube یا Apple Mail بچسبانید.
- `build/signature-fa.html` و `build/signature-en.html`: کد HTML آماده (برای Roundcube یا هر جایی که کد HTML می‌خواهد).
- تصاویر امضا (لوگو و آیکون‌ها) در `website/assets/img/email/` هستند و از سایت بارگذاری می‌شوند؛ تا سایت بالا نیامده، تصاویر در امضا دیده نمی‌شوند.

## امضا برای همکار جدید
در `signature.php` به آرایه‌ی `$people` یک ردیف اضافه کنید، سپس:
```bash
php email-signature/signature.php fa <نشانی-تصاویر> <کلید-همکار> > build/signature-fa-<name>.html
```
برای بازسازی صفحه‌ی نصب:
```bash
php email-signature/install.php > website/email-signature.html
```
