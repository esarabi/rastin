"""Build Rastin logo SVGs with text converted to outlines (no font needed to view them).

Run: python3 tools/build_logos.py   (needs: pip install fonttools uharfbuzz)
"""
import os
import uharfbuzz as hb
from fontTools.ttLib import TTFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen
from fontTools.pens.boundsPen import BoundsPen

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
FONTS = os.path.join(ROOT, "brand", "fonts")
OUT = os.path.join(ROOT, "brand", "logo")

CORAL = "#FF5A36"
AMBER = "#FFB547"
INK = "#1F1630"
WHITE = "#FFFFFF"


class Text:
    """Shape a string and expose its outline as an SVG path at a given size."""

    def __init__(self, text, font, size, tracking=0.0):
        path = os.path.join(FONTS, font)
        blob = hb.Blob.from_file_path(path)
        face = hb.Face(blob)
        self.hbfont = hb.Font(face)
        self.tt = TTFont(path)
        self.upem = self.tt["head"].unitsPerEm
        self.scale = size / self.upem
        buf = hb.Buffer()
        buf.add_str(text)
        buf.guess_segment_properties()
        hb.shape(self.hbfont, buf, {"kern": True, "liga": True})
        self.infos, self.pos = buf.glyph_infos, buf.glyph_positions
        self.tracking = tracking * self.upem
        self.glyphset = self.tt.getGlyphSet()
        self.order = self.tt.getGlyphOrder()

    def _draw(self, pen_factory):
        x = 0
        for info, pos in zip(self.infos, self.pos):
            name = self.order[info.codepoint]
            s = self.scale
            pen = pen_factory((s, 0, 0, -s, (x + pos.x_offset) * s, -pos.y_offset * s))
            self.glyphset[name].draw(pen)
            x += pos.x_advance + self.tracking
        return (x - self.tracking) * self.scale

    def bounds(self):
        bp = BoundsPen(self.glyphset)
        self._draw(lambda t: TransformPen(bp, t))
        return bp.bounds  # xMin, yMin, xMax, yMax  (y already flipped)

    def path(self):
        sp = SVGPathPen(self.glyphset)
        self._draw(lambda t: TransformPen(sp, t))
        return sp.getCommands()


def mark(ink=INK, b=CORAL, b2=AMBER):
    """Concept B mark: a growing network, drawn on a 100x100 grid."""
    return (
        f'<path d="M22 78 L46 54 L76 26 M46 54 L74 70" fill="none" stroke="{ink}" '
        f'stroke-width="7" stroke-linecap="round"/>'
        f'<circle cx="22" cy="78" r="9" fill="{ink}"/>'
        f'<circle cx="46" cy="54" r="11" fill="{ink}"/>'
        f'<circle cx="74" cy="70" r="7" fill="{b2}"/>'
        f'<circle cx="76" cy="26" r="15" fill="{b}"/>'
    )


# mark visual bounds on its grid
MX0, MY0, MX1, MY1 = 13, 11, 91, 87


def place_text(t, x, y_baseline):
    return f'<path transform="translate({x:.2f} {y_baseline:.2f})" d="{t.path()}"/>'


def svg(w, h, body, bg=None, title="Rastin"):
    rect = f'<rect width="{w}" height="{h}" fill="{bg}"/>' if bg else ""
    return (
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w:.2f} {h:.2f}" '
        f'width="{w:.0f}" height="{h:.0f}" role="img"><title>{title}</title>{rect}{body}</svg>\n'
    )


def horizontal_fa(ink, b, b2, sub, bg=None):
    """Mark on the right (start of an RTL line), Persian name + English line on the left."""
    fa = Text("راستین", "Vazirmatn-Black.ttf", 92)
    en = Text("RASTIN INTELLIGENT DEVELOPMENT", "Vazirmatn-Medium.ttf", 17.5, tracking=0.08)
    fb, eb = fa.bounds(), en.bounds()
    pad = 24
    mark_h = 120
    ms = mark_h / (MY1 - MY0)
    fa_w, en_w = fb[2] - fb[0], eb[2] - eb[0]
    text_w = max(fa_w, en_w)
    gap = 26
    W = pad + text_w + gap + (MX1 - MX0) * ms + pad
    H = pad * 2 + mark_h
    # text block: fa ascender to en baseline, centred on the mark
    fa_h = fb[3] - fb[1]
    line_gap = 14
    en_h = eb[3] - eb[1]
    block = fa_h + line_gap + en_h
    top = pad + (mark_h - block) / 2
    right = pad + text_w
    fa_x = right - fb[2]
    fa_y = top - fb[1]
    en_x = right - eb[2]
    en_y = top + fa_h + line_gap - eb[1]
    mx = W - pad - (MX1 - MX0) * ms
    body = (
        f'<g transform="translate({mx - MX0 * ms:.2f} {pad - MY0 * ms:.2f}) scale({ms:.4f})">{mark(ink, b, b2)}</g>'
        f'<g fill="{ink}">{place_text(fa, fa_x, fa_y)}</g>'
        f'<g fill="{sub}">{place_text(en, en_x, en_y)}</g>'
    )
    return svg(W, H, body, bg, "توسعه هوشمند راستین")


def horizontal_en(ink, b, b2, sub, bg=None):
    name = Text("RASTIN", "Vazirmatn-Black.ttf", 82, tracking=0.02)
    tag = Text("INTELLIGENT DEVELOPMENT", "Vazirmatn-Medium.ttf", 19, tracking=0.12)
    nb, tb = name.bounds(), tag.bounds()
    pad, mark_h, gap = 24, 120, 24
    ms = mark_h / (MY1 - MY0)
    text_w = max(nb[2] - nb[0], tb[2] - tb[0])
    W = pad + (MX1 - MX0) * ms + gap + text_w + pad
    H = pad * 2 + mark_h
    n_h, t_h, lg = nb[3] - nb[1], tb[3] - tb[1], 14
    top = pad + (mark_h - (n_h + lg + t_h)) / 2
    left = pad + (MX1 - MX0) * ms + gap
    body = (
        f'<g transform="translate({pad - MX0 * ms:.2f} {pad - MY0 * ms:.2f}) scale({ms:.4f})">{mark(ink, b, b2)}</g>'
        f'<g fill="{ink}">{place_text(name, left - nb[0], top - nb[1])}</g>'
        f'<g fill="{sub}">{place_text(tag, left - tb[0], top + n_h + lg - tb[1])}</g>'
    )
    return svg(W, H, body, bg, "Rastin Intelligent Development")


def vertical(ink, b, b2, sub, bg=None):
    fa = Text("راستین", "Vazirmatn-Black.ttf", 96)
    en = Text("RASTIN INTELLIGENT DEVELOPMENT", "Vazirmatn-Medium.ttf", 15, tracking=0.1)
    fb, eb = fa.bounds(), en.bounds()
    pad, mark_h = 32, 170
    ms = mark_h / (MY1 - MY0)
    fa_w, en_w = fb[2] - fb[0], eb[2] - eb[0]
    W = pad * 2 + max(fa_w, en_w, (MX1 - MX0) * ms)
    fa_h, en_h = fb[3] - fb[1], eb[3] - eb[1]
    y_fa = pad + mark_h + 26
    y_en = y_fa + fa_h + 16
    H = y_en + en_h + pad
    cx = W / 2
    body = (
        f'<g transform="translate({cx - (MX0 + MX1) / 2 * ms:.2f} {pad - MY0 * ms:.2f}) scale({ms:.4f})">{mark(ink, b, b2)}</g>'
        f'<g fill="{ink}">{place_text(fa, cx - (fb[0] + fb[2]) / 2, y_fa - fb[1])}</g>'
        f'<g fill="{sub}">{place_text(en, cx - (eb[0] + eb[2]) / 2, y_en - eb[1])}</g>'
    )
    return svg(W, H, body, bg, "توسعه هوشمند راستین")


def mark_only(ink, b, b2, bg=None, size=512, rounded=False, inset=0.0):
    s = size
    rect = ""
    if bg:
        r = s * 0.22 if rounded else 0
        rect = f'<rect width="{s}" height="{s}" rx="{r:.1f}" fill="{bg}"/>'
    inner = s * (1 - 2 * inset)
    k = inner / 100
    off = s * inset
    body = f'{rect}<g transform="translate({off:.2f} {off:.2f}) scale({k:.4f})">{mark(ink, b, b2)}</g>'
    return f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {s} {s}" width="{s}" height="{s}" role="img"><title>Rastin</title>{body}</svg>\n'


def write(name, content):
    with open(os.path.join(OUT, name), "w", encoding="utf-8") as f:
        f.write(content)


def main():
    os.makedirs(OUT, exist_ok=True)
    SUB = "#6B6375"
    variants = {
        "color": (INK, CORAL, AMBER, SUB, None),
        "reverse": (WHITE, CORAL, AMBER, "#CFC8D6", INK),
        "mono-black": (INK, INK, INK, INK, None),
        "mono-white": (WHITE, WHITE, WHITE, WHITE, INK),
        "on-coral": (WHITE, INK, INK, WHITE, CORAL),
    }
    for v, (ink, b, b2, sub, bg) in variants.items():
        write(f"rastin-horizontal-fa-{v}.svg", horizontal_fa(ink, b, b2, sub, bg))
        write(f"rastin-horizontal-en-{v}.svg", horizontal_en(ink, b, b2, sub, bg))
        write(f"rastin-vertical-{v}.svg", vertical(ink, b, b2, sub, bg))
        write(f"rastin-mark-{v}.svg", mark_only(ink, b, b2, bg, inset=0.06 if bg else 0))
    # transparent versions of the reverse / white logos (for placing on photos or dark UIs)
    write("rastin-horizontal-fa-reverse-transparent.svg", horizontal_fa(WHITE, CORAL, AMBER, "#CFC8D6"))
    write("rastin-mark-white-transparent.svg", mark_only(WHITE, WHITE, WHITE))
    # app icon / favicon: white mark on a coral rounded square
    write("rastin-app-icon.svg", mark_only(WHITE, WHITE, INK, CORAL, rounded=True, inset=0.16))
    write("favicon.svg", mark_only(WHITE, WHITE, INK, CORAL, rounded=True, inset=0.12))
    print("logos written to", OUT)


if __name__ == "__main__":
    main()
