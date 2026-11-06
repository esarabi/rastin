"""RastDesk product logo — same family as the Rastin mark: the node network inside a coral app tile.

Run: python3 tools/build_rastdesk_logo.py   (needs: pip install fonttools uharfbuzz)
Writes brand/rastdesk-logo/*.svg
"""
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from build_logos import Text, place_text, svg, CORAL, AMBER, INK, WHITE  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, "brand", "rastdesk-logo")


def tile(x, y, s, bg=CORAL, ink=WHITE, accent=INK):
    """Rounded tile with the network mark. (x, y) top-left, s = size."""
    k = s / 100
    r = 24 * k
    return (
        f'<rect x="{x:.2f}" y="{y:.2f}" width="{s:.2f}" height="{s:.2f}" rx="{r:.2f}" fill="{bg}"/>'
        f'<g transform="translate({x + 12 * k:.2f} {y + 12 * k:.2f}) scale({0.76 * k:.4f})">'
        f'<path d="M22 78 L46 54 L76 26 M46 54 L74 70" fill="none" stroke="{ink}" stroke-width="8" stroke-linecap="round"/>'
        f'<circle cx="22" cy="78" r="9.5" fill="{ink}"/><circle cx="46" cy="54" r="11.5" fill="{ink}"/>'
        f'<circle cx="74" cy="70" r="7.5" fill="{accent}"/><circle cx="76" cy="26" r="15" fill="{ink}"/>'
        f'</g>'
    )


def lockup(lang, text_color, sub_color, bg=None, tile_bg=CORAL, tile_ink=WHITE, tile_accent=INK):
    if lang == "fa":
        name = Text("راست‌دسک", "Vazirmatn-Black.ttf", 84)
        sub = Text("RASTDESK  ·  CRM", "Vazirmatn-Medium.ttf", 16, tracking=0.16)
    else:
        name = Text("RastDesk", "Vazirmatn-Black.ttf", 80, tracking=-0.01)
        sub = Text("BY RASTIN", "Vazirmatn-Medium.ttf", 16, tracking=0.2)
    nb, sb = name.bounds(), sub.bounds()
    pad, ts, gap, lg = 20, 104, 24, 10
    n_h, s_h = nb[3] - nb[1], sb[3] - sb[1]
    text_w = max(nb[2] - nb[0], sb[2] - sb[0])
    W = pad + ts + gap + text_w + pad
    H = pad * 2 + ts
    top = pad + (ts - (n_h + lg + s_h)) / 2
    if lang == "fa":  # tile on the right (start of an RTL line), text right-aligned to it
        tx = W - pad - ts
        right = tx - gap
        body = tile(tx, pad, ts, tile_bg, tile_ink, tile_accent)
        body += f'<g fill="{text_color}">{place_text(name, right - nb[2], top - nb[1])}</g>'
        body += f'<g fill="{sub_color}">{place_text(sub, right - sb[2], top + n_h + lg - sb[1])}</g>'
    else:
        left = pad + ts + gap
        body = tile(pad, pad, ts, tile_bg, tile_ink, tile_accent)
        body += f'<g fill="{text_color}">{place_text(name, left - nb[0], top - nb[1])}</g>'
        body += f'<g fill="{sub_color}">{place_text(sub, left - sb[0], top + n_h + lg - sb[1])}</g>'
    return svg(W, H, body, bg, "RastDesk")


def icon_only(size=512, inset=0.0, bg=None):
    s = size
    rect = f'<rect width="{s}" height="{s}" fill="{bg}"/>' if bg else ""
    t = s * (1 - 2 * inset)
    return (f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {s} {s}" width="{s}" height="{s}" role="img">'
            f'<title>RastDesk</title>{rect}{tile(s * inset, s * inset, t)}</svg>\n')


def main():
    os.makedirs(OUT, exist_ok=True)
    files = {
        "rastdesk-fa-on-dark.svg": lockup("fa", WHITE, "#CFC8D6"),
        "rastdesk-fa-on-light.svg": lockup("fa", INK, "#6B6375"),
        "rastdesk-en-on-dark.svg": lockup("en", WHITE, "#CFC8D6"),
        "rastdesk-en-on-light.svg": lockup("en", INK, "#6B6375"),
        "rastdesk-fa-mono-white.svg": lockup("fa", WHITE, WHITE, None, WHITE, INK, INK),
        "rastdesk-icon.svg": icon_only(),
        "favicon.svg": icon_only(64),
    }
    for name, content in files.items():
        with open(os.path.join(OUT, name), "w", encoding="utf-8") as f:
            f.write(content)
    print("written to", OUT)


if __name__ == "__main__":
    main()
