import re
from pathlib import Path
root = Path('.').resolve()
controllers = list((root/ 'app' / 'Http' / 'Controllers').rglob('*.php'))
view_pattern = re.compile(r"view\(\s*['\"]([a-zA-Z0-9_\-\.\/]+)['\"]")
loadview_pattern = re.compile(r"loadView\(\s*['\"]([a-zA-Z0-9_\-\.\/]+)['\"]")
missing = []
found = set()
for c in controllers:
    txt = c.read_text(encoding='utf-8', errors='ignore')
    for m in view_pattern.findall(txt):
        found.add(m)
    for m in loadview_pattern.findall(txt):
        found.add(m)
# check files
for v in sorted(found):
    path = Path('resources') / 'views' / Path(v.replace('.', '/'))
    blade = path.with_suffix('.blade.php')
    # also allow folder/index.blade.php if v refers to folder
    index_blade = path / 'index.blade.php'
    if not blade.exists() and not index_blade.exists():
        missing.append((v, str(blade), str(index_blade)))
out = Path('tools') / 'view_check_results.txt'
with open(out, 'w', encoding='utf-8') as f:
    if not found:
        f.write('No view references found in controllers.')
    else:
        f.write('Total view references found: %d\n\n' % len(found))
        if missing:
            f.write('Missing views (%d):\n' % len(missing))
            for v, b, i in missing:
                f.write(f"- {v} -> {b} | alt: {i}\n")
        else:
            f.write('All referenced views exist.\n')
print('wrote', out)
