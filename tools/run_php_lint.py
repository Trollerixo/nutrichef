import subprocess
from pathlib import Path
root = Path(__file__).resolve().parents[1]
php = r"C:\xampp\php\php.exe"
out_file = root / 'php_lint_results.txt'
errors = []
count = 0
for p in root.rglob('*.php'):
    if 'vendor' in p.parts:
        continue
    count += 1
    proc = subprocess.run([php, '-l', str(p)], capture_output=True, text=True)
    text = proc.stdout.strip() + proc.stderr.strip()
    if proc.returncode != 0:
        errors.append((str(p), text))
with open(out_file, 'w', encoding='utf-8') as f:
    f.write(f'Total scanned: {count}\n')
    f.write(f'Files with errors: {len(errors)}\n\n')
    for path, txt in errors:
        f.write('FILE: ' + path + '\n')
        f.write(txt + '\n\n')
print('wrote', out_file.exists())
print('scanned', count, 'errors', len(errors))
