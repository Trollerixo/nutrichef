import re
from pathlib import Path
root = Path('.').resolve()
route_dir = root / 'routes'
controller_dir = root / 'app' / 'Http' / 'Controllers'
report = []
route_files = list(route_dir.glob('*.php'))
route_text = ''
for rf in route_files:
    route_text += rf.read_text(encoding='utf-8', errors='ignore') + '\n'
# patterns
# 1) Route::get/post/...\( 'path', [Controller::class, 'method'] )
array_pattern = re.compile(r"\[\s*([A-Za-z0-9_\\\\]+)::class\s*,\s*['\"]([A-Za-z0-9_]+)['\"]\s*\]")
# 2) Route::get('path', 'Controller@method')
string_pattern = re.compile(r"['\"]([A-Za-z0-9_\\\\/]+)['\"]\s*\,\s*['\"]([A-Za-z0-9_\\\\]+)@([A-Za-z0-9_]+)['\"]")
# 3) Route::resource('name', Controller::class)
resource_pattern = re.compile(r"Route::resource\(\s*['\"][A-Za-z0-9_\-]+['\"]\s*,\s*([A-Za-z0-9_\\\\]+)::class")
found = []
for m in array_pattern.findall(route_text):
    found.append((m[0], m[1]))
for m in string_pattern.findall(route_text):
    # m[1] may be like Admin\\UserController or UserController
    controller = m[1]
    method = m[2]
    found.append((controller, method))
for m in resource_pattern.findall(route_text):
    controller = m
    # resource methods
    for method in ['index','create','store','show','edit','update','destroy']:
        found.append((controller, method))
# normalize and check
issues = []
checked = set()
for controller, method in found:
    # normalize controller class path
    ctrl = controller.replace('/', '\\').lstrip('\\')
    # if it already contains namespace like Admin\\UserController, prefix App\\Http\\Controllers\\
    if not ctrl.startswith('App'):
        fqcn = 'App\\Http\\Controllers\\' + ctrl
    else:
        fqcn = ctrl
    # convert to file path
    parts = fqcn.split('\\')
    # remove leading 'App','Http','Controllers' to map to controller_dir
    if parts[0] == 'App' and parts[1] == 'Http' and parts[2] == 'Controllers':
        rel = Path(*parts[3:])
    else:
        rel = Path(*parts)
    file_candidate = controller_dir / rel
    # ensure .php
    if not str(file_candidate).lower().endswith('.php'):
        file_candidate = Path(str(file_candidate) + '.php')
    exists = file_candidate.exists()
    has_method = False
    if exists:
        txt = file_candidate.read_text(encoding='utf-8', errors='ignore')
        # look for function method(
        if re.search(rf"function\s+{re.escape(method)}\s*\(|public\s+function\s+{re.escape(method)}\s*\(|protected\s+function\s+{re.escape(method)}\s*\(|private\s+function\s+{re.escape(method)}\s*\(", txt):
            has_method = True
    else:
        # maybe controller in subnamespace; try app/Http/Controllers/**/*Controller.php matching end
        matches = list(controller_dir.rglob(f"{controller.split('\\')[-1]}.php"))
        if matches:
            exists = True
            file_candidate = matches[0]
            txt = file_candidate.read_text(encoding='utf-8', errors='ignore')
            if re.search(rf"function\s+{re.escape(method)}\s*\(|public\s+function\s+{re.escape(method)}\s*\(|protected\s+function\s+{re.escape(method)}\s*\(|private\s+function\s+{re.escape(method)}\s*\(", txt):
                has_method = True
    checked.add((str(file_candidate), method, exists, has_method))
# produce report
out = root / 'tools' / 'routes_methods_report.txt'
with open(out, 'w', encoding='utf-8') as f:
    f.write(f'Total references checked: {len(found)}\n')
    f.write('\n')
    missing = [c for c in checked if not c[2]]
    no_method = [c for c in checked if c[2] and not c[3]]
    f.write(f'Missing controller files: {len(missing)}\n')
    for path, method, exists, has_method in missing:
        f.write(f'- file: {path} referenced method: {method}\n')
    f.write('\n')
    f.write(f'Controller files present but missing method: {len(no_method)}\n')
    for path, method, exists, has_method in no_method:
        f.write(f'- file: {path} missing method: {method}\n')
    if not missing and not no_method:
        f.write('\nAll route controller methods exist.\n')
print('wrote', out)
