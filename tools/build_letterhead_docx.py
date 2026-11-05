"""Build Word letterhead templates (fa + en) for Rastin.

The page design (logo, side band, watermark, footer) is a full-page image placed behind the text in
the header, so it appears on every page. Ref/Date/Attachment, the letter body and the signature are
real, editable text set in Vazirmatn.

Run from the repo root after rendering the backgrounds:
    python3 tools/build_letterhead_docx.py
"""
import copy
import os
from docx import Document
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Mm, Pt, RGBColor

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BUILD = os.path.join(ROOT, "stationery", "build")
INK = RGBColor(0x1F, 0x16, 0x30)
MUTED = RGBColor(0x6B, 0x63, 0x75)
FONT = "Vazirmatn"

TEXT = {
    "fa": {
        "rtl": True,
        "fields": ["شماره:", "تاریخ:", "پیوست:"],
        "to": "جناب آقای / سرکار خانم ...",
        "to2": "مدیرعامل محترم شرکت ...",
        "subject": "موضوع: ...",
        "greeting": "احتراماً،",
        "body": "متن نامه را اینجا بنویسید. این قالب با فونت وزیرمتن تنظیم شده است؛ اگر این فونت روی رایانه‌ی شما نصب نیست، فایل‌های آن را از پوشه‌ی brand/fonts نصب کنید.",
        "closing": "با تشکر و احترام",
        "name": "ارشاد سرابی",
        "title": "مدیرعامل شرکت توسعه هوشمند راستین",
    },
    "en": {
        "rtl": False,
        "fields": ["Ref.:", "Date:", "Encl.:"],
        "to": "Mr / Ms ...",
        "to2": "Company ...",
        "subject": "Subject: ...",
        "greeting": "Dear ...,",
        "body": "Write the letter here. This template uses the Vazirmatn font; if it is not installed on your computer, install it from the brand/fonts folder.",
        "closing": "Sincerely,",
        "name": "Ershad Sarabi",
        "title": "Chief Executive Officer, Rastin Intelligent Development",
    },
}


def set_rtl(paragraph, rtl):
    pPr = paragraph._p.get_or_add_pPr()
    bidi = pPr.find(qn("w:bidi"))
    if rtl and bidi is None:
        bidi = OxmlElement("w:bidi")
        pPr.insert(0, bidi)


def style_run(run, size=11, bold=False, color=INK, rtl=False):
    run.font.name = FONT
    run.font.size = Pt(size)
    run.font.bold = bold
    run.font.color.rgb = color
    rPr = run._r.get_or_add_rPr()
    fonts = rPr.find(qn("w:rFonts"))
    if fonts is None:
        fonts = OxmlElement("w:rFonts")
        rPr.insert(0, fonts)
    for k in ("w:ascii", "w:hAnsi", "w:cs", "w:eastAsia"):
        fonts.set(qn(k), FONT)
    szCs = OxmlElement("w:szCs")
    szCs.set(qn("w:val"), str(int(size * 2)))
    rPr.append(szCs)
    if bold:
        rPr.append(OxmlElement("w:bCs"))
    if rtl:
        rPr.append(OxmlElement("w:rtl"))


def add_par(container, text, rtl, size=11, bold=False, color=INK, align="start", space_after=4, line=1.5):
    p = container.add_paragraph()
    set_rtl(p, rtl)
    # In a right-to-left paragraph Word mirrors left/right, so "start" is always LEFT here.
    p.alignment = {"start": WD_ALIGN_PARAGRAPH.LEFT, "end": WD_ALIGN_PARAGRAPH.RIGHT, "justify": WD_ALIGN_PARAGRAPH.JUSTIFY}[align]
    pf = p.paragraph_format
    pf.space_after = Pt(space_after)
    pf.space_before = Pt(0)
    pf.line_spacing = line
    if text:
        style_run(p.add_run(text), size, bold, color, rtl)
    return p


def anchor_behind(run, width_mm, height_mm):
    """Turn the run's inline picture into a page-anchored image behind the text."""
    inline = run._r.find(".//" + qn("wp:inline"))
    anchor = OxmlElement("wp:anchor")
    for k, v in {"distT": "0", "distB": "0", "distL": "0", "distR": "0", "simplePos": "0", "relativeHeight": "0",
                 "behindDoc": "1", "locked": "1", "layoutInCell": "1", "allowOverlap": "1"}.items():
        anchor.set(k, v)
    sp = OxmlElement("wp:simplePos"); sp.set("x", "0"); sp.set("y", "0"); anchor.append(sp)
    for axis, rel in (("wp:positionH", "page"), ("wp:positionV", "page")):
        pos = OxmlElement(axis); pos.set("relativeFrom", rel)
        off = OxmlElement("wp:posOffset"); off.text = "0"; pos.append(off); anchor.append(pos)
    ext = OxmlElement("wp:extent"); ext.set("cx", str(int(width_mm * 36000))); ext.set("cy", str(int(height_mm * 36000))); anchor.append(ext)
    ee = OxmlElement("wp:effectExtent")
    for k in ("l", "t", "r", "b"):
        ee.set(k, "0")
    anchor.append(ee)
    anchor.append(OxmlElement("wp:wrapNone"))
    for tag in ("wp:docPr", "wp:cNvGraphicFramePr", "a:graphic"):
        el = inline.find(qn(tag))
        if el is not None:
            anchor.append(copy.deepcopy(el))
    inline.getparent().replace(inline, anchor)


def build(lang):
    t = TEXT[lang]
    rtl = t["rtl"]
    doc = Document()

    # defaults: Vazirmatn everywhere, complex-script font too
    st = doc.styles["Normal"]
    st.font.name = FONT
    st.font.size = Pt(11)
    rpr = st.element.get_or_add_rPr()
    rf = rpr.find(qn("w:rFonts"))
    for k in ("w:ascii", "w:hAnsi", "w:cs", "w:eastAsia"):
        rf.set(qn(k), FONT)
    szcs = OxmlElement("w:szCs"); szcs.set(qn("w:val"), "22"); rpr.append(szcs)
    if rtl:
        rpr.append(OxmlElement("w:rtl"))

    sec = doc.sections[0]
    sec.page_width, sec.page_height = Mm(210), Mm(297)
    sec.top_margin, sec.bottom_margin = Mm(48), Mm(34)
    # the coloured band sits on the reading-start edge
    start, end = Mm(24), Mm(18)
    sec.right_margin, sec.left_margin = (start, end) if rtl else (end, start)
    sec.header_distance = Mm(12)
    sec.footer_distance = Mm(10)
    if rtl:
        sec._sectPr.append(OxmlElement("w:bidi"))

    # header: background image + editable Ref / Date / Attachment
    hdr = sec.header
    hdr.is_linked_to_previous = False
    hp = hdr.paragraphs[0]
    run = hp.add_run()
    run.add_picture(os.path.join(BUILD, f"letterhead-{lang}-bg.jpg"), width=Mm(210))
    anchor_behind(run, 210, 297)
    hp.paragraph_format.space_after = Pt(0)
    for label in t["fields"]:
        p = add_par(hdr, "", rtl, space_after=0, line=1.25, align="end")
        style_run(p.add_run(label + " "), 9, True, INK, rtl)
        style_run(p.add_run("................................"), 9, False, MUTED, rtl)

    # sample letter
    add_par(doc, t["to"], rtl, bold=True, space_after=0)
    add_par(doc, t["to2"], rtl, space_after=10)
    add_par(doc, t["subject"], rtl, bold=True, space_after=10)
    add_par(doc, t["greeting"], rtl, space_after=6)
    add_par(doc, t["body"], rtl, align="justify" if rtl else "start", space_after=24, line=1.75 if rtl else 1.5)
    # Persian letters sign at the far (left) side; English letters sign at the start.
    sign = "end" if rtl else "start"
    add_par(doc, t["closing"], rtl, align=sign, space_after=30)
    add_par(doc, t["name"], rtl, bold=True, align=sign, space_after=0)
    add_par(doc, t["title"], rtl, size=9.5, color=MUTED, align=sign, space_after=0)

    # drop the empty first body paragraph python-docx creates
    first = doc.paragraphs[0]
    if not first.text:
        first._p.getparent().remove(first._p)

    out = os.path.join(BUILD, f"letterhead-{lang}.docx")
    doc.save(out)
    print("wrote", out)


if __name__ == "__main__":
    for lang in ("fa", "en"):
        build(lang)
