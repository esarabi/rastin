<?php
declare(strict_types=1);

/**
 * Allow-list HTML sanitizer for blog posts written in the admin editor.
 * Keeps basic formatting, links and images; drops scripts, styles, event handlers and unknown tags.
 */
function sanitize_html(string $html): string
{
    $allowed = [
        'p' => [], 'br' => [], 'hr' => [],
        'h2' => [], 'h3' => [], 'h4' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'ul' => [], 'ol' => [], 'li' => [],
        'blockquote' => [], 'code' => [], 'pre' => [],
        'a' => ['href', 'title'],
        'img' => ['src', 'alt', 'width', 'height'],
        'figure' => [], 'figcaption' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => [], 'td' => [],
    ];
    $drop = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'svg', 'math', 'link', 'meta'];

    if (trim($html) === '') {
        return '';
    }
    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $root = $doc->getElementById('__root');
    if (!$root) {
        return '';
    }

    $walk = function (DOMNode $node) use (&$walk, $allowed, $drop, $doc) {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if ($child instanceof DOMComment) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($child->nodeName);
            if (in_array($tag, $drop, true)) {
                $node->removeChild($child);
                continue;
            }
            $walk($child);
            if (!isset($allowed[$tag])) {
                // unwrap: keep the children, drop the tag (e.g. <span>, <div>, <font>)
                $frag = $doc->createDocumentFragment();
                while ($child->firstChild) {
                    $frag->appendChild($child->firstChild);
                }
                if (in_array($tag, ['div', 'section', 'article'], true)) {
                    $p = $doc->createElement('p');
                    $p->appendChild($frag);
                    $node->replaceChild($p, $child);
                } else {
                    $node->replaceChild($frag, $child);
                }
                continue;
            }
            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->nodeName);
                if (!in_array($name, $allowed[$tag], true)) {
                    $child->removeAttribute($attr->nodeName);
                    continue;
                }
                if (in_array($name, ['href', 'src'], true)) {
                    $val = trim($attr->nodeValue);
                    $ok = preg_match('#^(https?://|/|\#|mailto:|tel:)#i', $val) && !preg_match('#^//#', $val);
                    if (!$ok) {
                        $child->removeAttribute($attr->nodeName);
                    }
                }
            }
            if ($tag === 'a' && $child->hasAttribute('href') && preg_match('#^https?://#i', $child->getAttribute('href'))) {
                $child->setAttribute('rel', 'noopener');
                $child->setAttribute('target', '_blank');
            }
            if ($tag === 'img') {
                $child->setAttribute('loading', 'lazy');
            }
        }
    };
    $walk($root);

    $out = '';
    foreach ($root->childNodes as $c) {
        $out .= $doc->saveHTML($c);
    }
    return trim($out);
}
