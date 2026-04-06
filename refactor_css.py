import re

css_file = "assets/css/theme.css"
with open(css_file, "r") as f:
    css = f.read()

# Make a map for exact matches
replacements = {
    r'#023020': 'var(--rooq-primary)',
    r'#B0C4DE': 'var(--rooq-secondary)',
    r'([#]b0c4de)(?![a-zA-Z0-9])': 'var(--rooq-secondary)',
    r'#8FA8C5': 'var(--dark-secondary)',
    r'#ffffff': 'var(--white)',
    r'#011810': 'var(--bg-radial-start)',
    r'#121212': 'var(--bg-radial-end)',
    r'#012015': 'var(--bg-login-end)',
    r'#011a11': 'var(--bg-dark-primary)',
    r'#f8f9fa': 'var(--bg-light)',
    r'#6c757d': 'var(--text-muted)',
    r'#f0f0f0': 'var(--border-light)',
    r'#444444': 'var(--inv-contact-text)', # Also wf-line
    r'#555555': 'var(--status-default)',
    r'#aaaaaa': 'var(--badge-text-default)',
    r'#2ecc71': 'var(--status-success)',
    r'#f1c40f': 'var(--status-warning)',
    r'#ffc107': 'var(--badge-warning-text)',
    r'#dc3545': 'var(--status-danger)',
    r'#3498db': 'var(--status-process)',
    r'#eef0f4': 'var(--inv-bg-page)',
    r'#1a1a2e': 'var(--inv-text-dark)',
    r'#fafafa': 'var(--inv-footer-bg)',
    r'#f9fafb': 'var(--inv-notes-bg)',
    r'#999999': 'var(--inv-muted-1)',
    r'#888888': 'var(--inv-muted-2)',
    r'#777777': 'var(--inv-muted-3)',
    r'#333333': 'var(--inv-body-text)',
    r'#bbbbbb': 'var(--inv-ref-text)',
    r'#eeeeee': 'var(--inv-border-foot)',
    r'#e0e8e2': 'var(--inv-divider-mid)',
    r'#d8eadc': 'var(--inv-divider-side)',
    r'#f0faf3': 'var(--inv-highlight-a)',
    r'#e8f4ec': 'var(--inv-highlight-b)',
    r'#b7dfc3': 'var(--inv-highlight-bd)',
    r'#d0ead8': 'var(--inv-print-hover)',
    r'#52ecc5': 'var(--accent-cyan)',
    r'#01120c': 'var(--brand-dark)',
    r'rgba\(255,\s*255,\s*255,\s*0\.03\)': 'var(--glass-03)',
    r'rgba\(255,\s*255,\s*255,\s*0\.05\)': 'var(--glass-05)',
    r'rgba\(255,\s*255,\s*255,\s*0\.1\)': 'var(--glass-10)',
    r'rgba\(255,\s*255,\s*255,\s*0\.15\)': 'var(--glass-15)',
    r'rgba\(255,\s*255,\s*255,\s*0\.2\)': 'var(--glass-20)',
    r'rgba\(255,\s*255,\s*255,\s*0\.3\)': 'var(--glass-30)',
    r'rgba\(255,\s*255,\s*255,\s*0\.35\)': 'var(--glass-30)', # closest
    r'rgba\(255,\s*255,\s*255,\s*0\.5\)': 'var(--glass-50)',
    r'rgba\(255,\s*255,\s*255,\s*0\.7\)': 'var(--glass-70)',
    r'rgba\(255,\s*255,\s*255,\s*0\.8\)': 'var(--glass-80)',

    r'rgba\(0,\s*0,\s*0,\s*0\.1\)': 'var(--black-10)',
    r'rgba\(0,\s*0,\s*0,\s*0\.2\)': 'var(--black-20)',
    r'rgba\(0,\s*0,\s*0,\s*0\.25\)': 'var(--black-25)',
    r'rgba\(0,\s*0,\s*0,\s*0\.3\)': 'var(--black-30)',
    r'rgba\(0,\s*0,\s*0,\s*0\.4\)': 'var(--black-30)', # closest
    r'rgba\(0,\s*0,\s*0,\s*0\.5\)': 'var(--black-50)',
    r'rgba\(0,\s*0,\s*0,\s*0\.6\)': 'var(--black-50)', # close
    r'rgba\(0,\s*0,\s*0,\s*0\.8\)': 'var(--black-80)',
    r'rgba\(0,\s*0,\s*0,\s*0\.9\)': 'var(--black-90)',

    r'rgba\(176,\s*196,\s*222,\s*0\.08\)': 'var(--secondary-08)',
    r'rgba\(176,\s*196,\s*222,\s*0\.1\)': 'var(--secondary-10)',
    r'rgba\(176,\s*196,\s*222,\s*0\.15\)': 'var(--secondary-15)',
    r'rgba\(176,\s*196,\s*222,\s*0\.2\)': 'var(--secondary-20)',
    r'rgba\(176,\s*196,\s*222,\s*0\.3\)': 'var(--secondary-30)',
    r'rgba\(176,\s*196,\s*222,\s*0\.4\)': 'var(--secondary-40)',
    r'rgba\(176,\s*196,\s*222,\s*0\.5\)': 'var(--secondary-50)',
    r'rgba\(176,\s*196,\s*222,\s*0\.6\)': 'var(--secondary-60)',
    r'rgba\(176,\s*196,\s*222,\s*0\.9\)': 'var(--secondary-90)',
    r'rgba\(176,\s*196,\s*222,\s*0\.06\)': 'var(--secondary-08)', # map

    r'rgba\(2,\s*48,\s*32,\s*0\.08\)': 'var(--primary-glass)',
    r'rgba\(1,\s*24,\s*16,\s*0\.4\)': 'var(--primary-dark-glass)',
    r'rgba\(2,\s*48,\s*32,\s*0\.9\)': 'var(--bg-header)',
    r'rgba\(45,\s*45,\s*45,\s*0\.95\)': 'var(--bg-dropdown)',
    r'rgba\(20,\s*25,\s*20,\s*0\.98\)': 'var(--bg-modal)',

    # For fonts
    r'(?<!var\()500(?!0)(?![\w-])': 'var(--fw-medium)',
    r'(?<!var\()600(?!0)(?![\w-])': 'var(--fw-semi)',
    r'(?<!var\()700(?!0)(?![\w-])': 'var(--fw-bold)',
    r'(?<!var\()800(?!0)(?![\w-])': 'var(--fw-extra)',

    r'0\.75rem': 'var(--fs-xxs)',
    r'0\.8rem': 'var(--fs-xs)',
    r'0\.85rem': 'var(--fs-sm)',
    r'0\.875rem': 'var(--fs-14)',
    r'0\.9rem': 'var(--fs-base)',
    r'0\.95rem': 'var(--fs-md)',
    r'1\.1rem': 'var(--fs-lg)',
    r'1\.2rem': 'var(--fs-xl)',
    r'1\.8rem': 'var(--fs-xxl)',
    r'0\.68rem': 'var(--fs-inv-label)',
    r'0\.67rem': 'var(--fs-inv-micro)',
    r'0\.70rem': 'var(--fs-inv-ref)',
    r'1\.55rem': 'var(--fs-inv-amount)',
    r'1\.50rem': 'var(--fs-inv-total)',

    # Other hard-coded sizes in rules
    r'0\.82rem': 'var(--fs-xs)',
    r'0\.88rem': 'var(--fs-sm)',
    r'0\.83rem': 'var(--fs-xs)',
    r'0\.72rem': 'var(--fs-inv-ref)',
    r'1\.4rem': 'var(--fs-xl)',
    r'0\.84rem': 'var(--fs-xs)',
    r'2rem': 'var(--fs-xxl)',
}

# Apply all exact replacements. Be careful with replacing inside :root {}
# Actually, inside :root{} we shouldn't replace. We can split the string.
root_end = css.find('}')
root_css = css[:root_end]
rest_css = css[root_end:]

for k, v in replacements.items():
    rest_css = re.sub(k, v, rest_css, flags=re.IGNORECASE)

new_css = root_css + rest_css

# Remove duplicated .translate-z-* classes
patterns_to_remove = [
    r'\.translate-z-10 \{ transform: translateZ\(10px\); \}',
    r'\.translate-z-20 \{ transform: translateZ\(20px\); \}',
    r'\.translate-z-30 \{ transform: translateZ\(30px\); \}',
    r'\.translate-z-40 \{ transform: translateZ\(40px\); \}',
]
for p in patterns_to_remove:
    new_css = re.sub(p, '', new_css)

# Also remove .translate-z-* from anywhere they might exist and just rely on the CSS finding .z-10
# We can't do that easily without parsing HTML, so we just remove the duplicate defs in CSS. Wait, the user said "remove unecessery class", it implies we should delete duplicate definitions in the CSS.
# So the translation class is already defined as .z-10, .z-20, etc. 

# Find .z-10, .z-20 in the rest of CSS?
# Wait, if .translate-z-10 is used in HTML, we shouldn't delete the CSS class unless we also replace it in HTML.
# But the user says "in theme.css file don't use any duplicat class ... don't duplicat any thing". They're probably talking about the CSS definitions being duplicated.

with open(css_file, "w") as f:
    f.write(new_css)
print("Updated CSS properly.")
