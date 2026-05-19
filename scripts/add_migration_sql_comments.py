from pathlib import Path
import re

root = Path('database/migrations')

def parse_column(line):
    line = line.strip().rstrip(';')
    if not line.startswith('$table->'):
        return None
    expr = line[len('$table->'):]
    if expr.startswith('id('):
        m = re.search(r"id\('([^']+)'\)", expr)
        return f"{m.group(1)} BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY" if m else None
    if expr.startswith('foreignId('):
        m = re.search(r"foreignId\('([^']+)'\)", expr)
        if not m: return None
        name = m.group(1)
        null = 'nullable()' in expr
        sql = f"{name} BIGINT UNSIGNED {'NULL' if null else 'NOT NULL'}"
        cm = re.search(r"constrained\('([^']+)'(?:,\s*'([^']+)')?\)", expr)
        if cm:
            ref_table = cm.group(1)
            ref_col = cm.group(2) or name
            sql += f", FOREIGN KEY ({name}) REFERENCES {ref_table}({ref_col})"
            if 'cascadeOnDelete' in expr:
                sql += ' ON DELETE CASCADE'
            elif 'nullOnDelete' in expr:
                sql += ' ON DELETE SET NULL'
        return sql
    if expr.startswith('string('):
        m = re.search(r"string\('([^']+)'(?:,\s*(\d+))?\)", expr)
        if not m: return None
        name, length = m.group(1), m.group(2) or '255'
        null = 'nullable()' in expr
        return f"{name} VARCHAR({length}) {'NULL' if null else 'NOT NULL'}"
    if expr.startswith('text('):
        m = re.search(r"text\('([^']+)'\)", expr)
        if not m: return None
        name = m.group(1)
        null = 'nullable()' in expr
        return f"{name} TEXT {'NULL' if null else 'NOT NULL'}"
    if expr.startswith('enum('):
        m = re.search(r"enum\('([^']+)',\s*\[([^\]]+)\]\)", expr)
        if not m: return None
        name = m.group(1)
        options = [o.strip().strip("'\"") for o in m.group(2).split(',')]
        null = 'nullable()' in expr
        default = re.search(r"default\('([^']+)'\)", expr)
        opts = ', '.join(f"'{opt}'" for opt in options)
        sql = f"{name} ENUM({opts}) {'NULL' if null else 'NOT NULL'}"
        if default:
            sql += f" DEFAULT '{default.group(1)}'"
        return sql
    if expr.startswith('decimal('):
        m = re.search(r"decimal\('([^']+)',\s*(\d+),\s*(\d+)\)\)", expr)
        if not m: return None
        name, p, s = m.group(1), m.group(2), m.group(3)
        null = 'nullable()' in expr
        return f"{name} DECIMAL({p},{s}) {'NULL' if null else 'NOT NULL'}"
    if expr.startswith('date('):
        m = re.search(r"date\('([^']+)'\)", expr)
        if not m: return None
        name = m.group(1)
        null = 'nullable()' in expr
        return f"{name} DATE {'NULL' if null else 'NOT NULL'}"
    if expr.startswith('boolean('):
        m = re.search(r"boolean\('([^']+)'\)", expr)
        if not m: return None
        name = m.group(1)
        default = re.search(r"default\((true|false|\d+)\)", expr)
        sql = f"{name} TINYINT(1) NOT NULL"
        if default:
            val = '1' if default.group(1) == 'true' else '0' if default.group(1) == 'false' else default.group(1)
            sql += f" DEFAULT {val}"
        return sql
    if expr.startswith('timestamps'):
        return 'created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL'
    if expr.startswith('primary('):
        m = re.search(r"primary\(\[([^\]]+)\]\)", expr)
        if not m: return None
        cols = [c.strip().strip("'\"") for c in m.group(1).split(',')]
        return f"PRIMARY KEY ({', '.join(cols)})"
    if expr.startswith('fullText('):
        m = re.search(r"fullText\(\[([^\]]+)\],\s*'([^']+)'\)", expr)
        if not m: return None
        cols = [c.strip().strip("'\"") for c in m.group(1).split(',')]
        return f"FULLTEXT KEY {m.group(2)} ({', '.join(cols)})"
    if expr.startswith('dropFullText('):
        m = re.search(r"dropFullText\('([^']+)'\)", expr)
        if not m: return None
        return f"DROP FULLTEXT KEY {m.group(1)}"
    return None

for path in sorted(root.glob('*.php')):
    print('Processing', path.name)
    text = path.read_text(encoding='utf-8')
    if 'SQL equivalent:' in text:
        print('  skipping already commented')
        continue
    idx = text.find('Schema::')
    if idx == -1:
        print('  no Schema:: found')
        continue
    m = re.search(r"Schema::(create|table)\('([^']+)'", text)
    print('  schema match', bool(m))
    if not m:
        continue
    table = m.group(2)
    start = idx
    end = text.find('});', start)
    if end == -1:
        print('  no end marker')
        continue
    body = text[start:end].splitlines()
    stmt_lines = []
    current = []
    for line in body:
        stripped = line.strip()
        if stripped.startswith('$table->'):
            current = [stripped]
            if stripped.endswith(';'):
                stmt_lines.append(' '.join(current))
                current = []
            continue
        if current:
            current.append(stripped)
            if stripped.endswith(';'):
                stmt_lines.append(' '.join(current))
                current = []
    cols = []
    for stmt in stmt_lines:
        c = parse_column(stmt)
        if c:
            cols.append(c)
    print('  parsed cols', len(cols))
    if not cols:
        continue
    comment = ['/*', ' * SQL equivalent for this migration:', f' * CREATE TABLE `{table}` (']
    for c in cols:
        comment.append(f' *   {c},')
    if comment[-1].endswith(','):
        comment[-1] = comment[-1][:-1]
    comment.extend([' * );', ' */', ''])
    insert_point = text.find('public function up(): void')
    if insert_point == -1:
        insert_point = text.find('function up(): void')
    print('  insert_point', insert_point)
    if insert_point == -1:
        continue
    new_text = text[:insert_point] + '\n'.join(comment) + text[insert_point:]
    path.write_text(new_text, encoding='utf-8')
    print('Updated', path.name)
