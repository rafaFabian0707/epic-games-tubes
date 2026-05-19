from pathlib import Path
import re
root = Path('database/migrations')
pattern = re.compile(r"/\*.*?\* SQL equivalent for this migration:.*?\*/\n?", re.S)
for path in sorted(root.glob('*.php')):
    text = path.read_text(encoding='utf-8')
    new_text = pattern.sub('', text)
    if new_text != text:
        path.write_text(new_text, encoding='utf-8')
        print('Cleaned', path.name)
