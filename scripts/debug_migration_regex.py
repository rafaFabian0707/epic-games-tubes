from pathlib import Path
import re
path = Path('database/migrations/2026_05_10_145806_create_games_table.php')
text = path.read_text(encoding='utf-8')
idx = text.find('Schema::')
print('idx', idx)
print('substring', repr(text[idx:idx+100]))
print('match', bool(re.search(r"Schema::(create|table)\('([^']+)'\)", text)))
print('all', re.findall(r"Schema::(create|table)\('([^']+)'\)", text))
