"""Inline the logo SVGs into brand/guidelines/template.html -> brand/guidelines/index.html"""
import os, re
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LOGO = os.path.join(ROOT, "brand", "logo")
G = os.path.join(ROOT, "brand", "guidelines")
KEYS = {"mark-color": "rastin-mark-color", "fa-color": "rastin-horizontal-fa-color",
        "en-color": "rastin-horizontal-en-color", "vertical-color": "rastin-vertical-color",
        "fa-reverse": "rastin-horizontal-fa-reverse", "fa-on-coral": "rastin-horizontal-fa-on-coral",
        "fa-mono-black": "rastin-horizontal-fa-mono-black", "fa-mono-white": "rastin-horizontal-fa-mono-white"}
def load(name, transparent):
    s = open(os.path.join(LOGO, name + ".svg"), encoding="utf-8").read().strip()
    s = re.sub(r' width="[\d.]+" height="[\d.]+"', "", s, count=1)
    if transparent:
        s = re.sub(r'<rect width="[\d.]+" height="[\d.]+"( rx="[\d.]+")? fill="#[0-9A-Fa-f]+"/>', "", s, count=1)
    return s
t = open(os.path.join(G, "template.html"), encoding="utf-8").read()
def sub(m):
    k, tr = m.group(1), bool(m.group(2))
    return load(KEYS[k], tr)
out = re.sub(r"\{\{([a-z-]+?)(-t)?\}\}", sub, t)
open(os.path.join(G, "index.html"), "w", encoding="utf-8").write(out)
print("ok", len(out))
