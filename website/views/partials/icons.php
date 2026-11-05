<?php
// Line icons on a 24px grid, 1.8px stroke, round caps — same visual language as the node mark.
function icon(string $name, string $class = 'ico'): string
{
    $p = [
        'crm' => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 19c.6-3.2 2.8-5 5.5-5s4.9 1.8 5.5 5"/><circle cx="17.5" cy="9.5" r="2.3"/><path d="M16 14.3c2.4-.2 4.1 1.3 4.6 4"/>',
        'code' => '<path d="M8.5 7 3.5 12l5 5M15.5 7l5 5-5 5M13.5 5l-3 14"/>',
        'ai' => '<rect x="6" y="6" width="12" height="12" rx="3"/><circle cx="12" cy="12" r="2.2"/><path d="M9 2.5V6M15 2.5V6M9 18v3.5M15 18v3.5M2.5 9H6M2.5 15H6M18 9h3.5M18 15h3.5"/>',
        'voice' => '<path d="M4 10v4M8 7v10M12 4v16M16 8v8M20 11v2"/>',
        'sales' => '<path d="M4 19V11M10 19V7M16 19v-5M3 19h18"/><path d="m14 5 3.5-1.5L19 7"/><path d="M5 9.5 10 5l4 3 3.5-4.5"/>',
        'ticket' => '<path d="M4 7.5A1.5 1.5 0 0 1 5.5 6h13A1.5 1.5 0 0 1 20 7.5V10a2 2 0 0 0 0 4v2.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 16.5V14a2 2 0 0 0 0-4Z"/><path d="M14 6v12" stroke-dasharray="2 2.5"/>',
        'phone' => '<path d="M6.5 3.5h2.8l1.4 4-2 1.3a10.5 10.5 0 0 0 6.5 6.5l1.3-2 4 1.4v2.8a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2Z"/>',
        'megaphone' => '<path d="M4 10v4a1 1 0 0 0 1 1h2l8 4.5V4.5L7 9H5a1 1 0 0 0-1 1Z"/><path d="M8 15.5 9.5 20M18.5 9.5a3 3 0 0 1 0 5"/>',
        'flow' => '<circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="18" r="2.5"/><rect x="15.5" y="3.5" width="5" height="5" rx="1.2"/><path d="M8.5 6h7M18 8.5v7M6 8.5V14a4 4 0 0 0 4 4h5.5"/>',
        'chart' => '<rect x="3.5" y="3.5" width="17" height="17" rx="3"/><path d="M8 16v-4M12 16V8M16 16v-6"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'globe' => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.5 2.6 3.6 5.4 3.6 8.5s-1.1 5.9-3.6 8.5c-2.5-2.6-3.6-5.4-3.6-8.5S9.5 6.1 12 3.5Z"/>',
        'mail' => '<rect x="3.5" y="5.5" width="17" height="13" rx="2.5"/><path d="m4.5 7 7.5 6 7.5-6"/>',
        'pin' => '<path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3"/>',
        'cloud' => '<path d="M7 18.5a4.5 4.5 0 0 1-.6-9 6 6 0 0 1 11.5 1.6 3.8 3.8 0 0 1-.4 7.4Z"/>',
        'server' => '<rect x="4" y="4" width="16" height="7" rx="2"/><rect x="4" y="13" width="16" height="7" rx="2"/><path d="M8 7.5h.01M8 16.5h.01M12 7.5h4M12 16.5h4"/>',
        'shield' => '<path d="M12 3.5 5 6v5.5c0 4.3 3 7.7 7 9 4-1.3 7-4.7 7-9V6Z"/><path d="m9 12 2 2 4-4"/>',
        'lang' => '<path d="M4 6h9M8.5 4v2M6 6c.5 3 2.5 5.5 5.5 6.5M11 6c-.6 3.4-3 6-6.5 7"/><path d="m12.5 20 3.8-9 3.7 9M13.8 17h5"/>',
        'puzzle' => '<path d="M9 4.5h3.5v2a1.8 1.8 0 1 0 3.5 0v-2h3.5V9h-2a1.8 1.8 0 1 0 0 3.5h2v7.5h-4v-2a1.8 1.8 0 1 0-3.5 0v2H4.5v-4h2a1.8 1.8 0 1 0 0-3.5h-2V4.5Z"/>',
        'map' => '<path d="M3.5 6.5 9 4.5l6 2 5.5-2v13l-5.5 2-6-2-5.5 2Z"/><path d="M9 4.5v13M15 6.5v13"/>',
        'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.6"/><path d="M16.8 7.2h.01"/>',
        'linkedin' => '<rect x="4" y="4" width="16" height="16" rx="3"/><path d="M8 10.5V16M8 7.8v.01M11.5 16v-5.5M11.5 13c0-1.6 1-2.6 2.3-2.6s2.2.9 2.2 2.6V16"/>',
        'telegram' => '<path d="M20.5 4.5 3.5 11l5.5 2 2 6 3-4 4.5 3.5Z"/><path d="m9 13 8-5.5"/>',
        'whatsapp' => '<path d="M4.5 19.5 5.6 16A8 8 0 1 1 8 18.4Z"/><path d="M9.3 8.8c.2 2.8 3 5.6 5.8 5.9l1-1.3-1.8-1-.8.7c-1-.4-1.9-1.3-2.4-2.3l.7-.9-.9-1.8Z"/>',
        'x' => '<path d="M5 4.5h3.8L19 19.5h-3.8ZM18.8 4.5 13.4 10.6M5.2 19.5l5.5-6.2"/>',
        'aparat' => '<circle cx="12" cy="12" r="7.5"/><path d="m10.3 9 4.4 3-4.4 3Z"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
    ];
    $body = $p[$name] ?? $p['check'];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}

function brand_mark(string $class = 'mark'): string
{
    return '<svg class="' . e($class) . '" viewBox="0 0 100 100" aria-hidden="true"><path d="M22 78 L46 54 L76 26 M46 54 L74 70" fill="none" stroke="var(--mk-ink,#1F1630)" stroke-width="7" stroke-linecap="round"/><circle cx="22" cy="78" r="9" fill="var(--mk-ink,#1F1630)"/><circle cx="46" cy="54" r="11" fill="var(--mk-ink,#1F1630)"/><circle cx="74" cy="70" r="7" fill="var(--mk-b2,#FFB547)"/><circle cx="76" cy="26" r="15" fill="var(--mk-b,#FF5A36)"/></svg>';
}
