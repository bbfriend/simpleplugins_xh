import html
import re

class URL:
    def __init__(self,src):
        elts = src.split(maxsplit=1)
        self.href = elts[0]
        self.alt = ''
        if len(elts)==2:
            alt = elts[1]
            if alt[0]=='"' and alt[-1]=='"':self.alt=alt[1:-1]
            elif alt[0]=="'" and alt[-1]=="'":self.alt=alt[1:-1]
            elif alt[0]=="(" and alt[-1]==")":self.alt=alt[1:-1]
        
class CodeBlock:
    def __init__(self,line):
        self.lines = [line]
    
    def to_html(self):
        res = '<p><pre class="marked"><code>'
        res += escape('\n'.join(self.lines))+'</code></pre><p>\n'
        return res,[]

class Marked:
    def __init__(self):
        self.lines = []
        self.children = []

    def to_html(self):
        return apply_markdown('\n'.join(self.lines))
        
# get references
refs = {}
ref_pattern = r"^\[(.*)\]:\s+(.*)"

def mark(src):

    global refs
    refs = {}
    # split source in sections
    # sections can be :
    # - a block-level HTML element (markdown syntax will not be processed)
    # - a script
    # - a span-level HTML tag (markdown syntax will be processed)
    # - a code block
    
    lines = src.split('\n')
    
    sections = []
    scripts = []
    section = Marked()

    i = 0
    while i<len(lines):
        line = lines[i]
        if isinstance(section,Marked):
            if line.lower().startswith('<script'):
                j = i+1
                while j<len(lines):
                    if lines[j].lower().startswith('</script>'):
                        scripts.append('\n'.join(lines[i+1:j]))
                        for k in range(i,j+1):
                            lines[k] = ''
                        break
                    j += 1
                i = j+1
                continue
            if line.strip() and line.startswith('    '):
                if section.lines:
                    sections.append(section)
                section = CodeBlock(line[4:])
            else:
                mo = re.search(ref_pattern,line)
                if mo is not None:
                    key = mo.groups()[0]
                    value = URL(mo.groups()[1])
                    refs[key.lower()] = value
                else:
                    section.lines.append(line)
        elif isinstance(section,CodeBlock):
            if line.startswith('    '):
                section.lines.append(line[4:])
            else:
                sections.append(section)
                section = Marked()                
                section.lines.append(line)
        i += 1
    if section.lines:
        sections.append(section)

    res = ''
    for section in sections:
        mk,_scripts = section.to_html()
        res += mk
        scripts += _scripts
    return res,scripts

def escape(czone,*args):
    czone = czone.replace('&','&amp;')
    czone = czone.replace('<','&lt;')
    czone = czone.replace('>','&gt;')
    return czone

def apply_markdown(src):
    scripts = []
    # replace \` by &#96;
    src = re.sub(r'\\\`','&#96;',src)

    # escape < > & in inline code
    code_pattern = r'\`(\S.*?\S)\`'
    src = re.sub(code_pattern,escape,src)
    
    # inline links
    link_pattern1 = r'\[(.+?)\]\s?\((.+?)\)'
    src = re.sub(link_pattern1,r'<a href="\2">\1</a>',src)

    # reference links
    link_pattern2 = r'\[(.+?)\]\s?\[(.*?)\]'
    while True:
        mo = re.search(link_pattern2,src)
        if mo is None:break
        text,key = mo.groups()
        print(text,key)
        if not key:key=text # implicit link name
        if key.lower() not in refs:
            raise KeyError('unknow reference %s' %key)
        url = refs[key.lower()]
        repl = '<a href="'+url.href+'"'
        if url.alt:
            repl += ' title="'+url.alt+'"'
        repl += '>%s</a>' %text
        src = re.sub(link_pattern2,repl,src,count=1)

    # emphasis

    # replace \* by &#42;
    src = re.sub(r'\\\*','&#42;',src)
    # replace \_ by &#95;
    src = re.sub(r'\\\_','&#95;',src)

    strong_patterns = [r'\*\*(\w.*?\w)\*\*',r'__(\w.*?\w)__']
    for strong_pattern in strong_patterns:
        src = re.sub(strong_pattern,r'<strong>\1</strong>',src)

    em_patterns = [r'\*(\S.*?\S)\*',r'\_(\S.*?\S)\_']
    for em_pattern in em_patterns:
        src = re.sub(em_pattern,r'<em>\1</em>',src)

    # inline code
    # replace \` by &#96;
    src = re.sub(r'\\\`','&#96;',src)

    code_pattern = r'\`(\S.*?\S)\`'
    src = re.sub(code_pattern,r'<code>\1</code>',src)

    # blockquotes
    lines = src.split('\n')
    while True:
        nb = 0
        i = 0
        while i<len(lines):
            if lines[i].startswith('>'):
                head = '\n'.join(lines[:i])
                bqlines = []
                nb += 1
                rest = lines[i][1:]
                if rest.lstrip().startswith('>'):
                    rest = rest.lstrip()
                bqlines.append(rest)
                j = i+1
                while j<len(lines):
                    if lines[j].startswith('>'):
                        rest = lines[j][1:]
                        if rest.lstrip().startswith('>'):
                            rest = rest.lstrip()
                        bqlines.append(rest)
                        j += 1
                    elif lines[j].strip() and lines[j][0]!=' ':
                        bqlines.append(lines[j])
                        j += 1
                    else:
                        break
                # bqlines holds the lines in the blockquote, stripped of
                # the leading >
                # we apply the whole markdown process to these lines
                mk,_scripts = mark('\n'.join(bqlines))
                scripts += _scripts
                src = head
                src += '<blockquote>' + mk + '</blockquote>'
                src += '\n'.join(lines[j:])
                break
            i += 1
        if nb==0:break
        lines = src.splitlines()

    # unordered lists
    lines = src.split('\n')
    while True:
        nb = 0
        i = 0
        while i<len(lines):
            mo = re.search(r'^( *[+*-])',lines[i])
            if mo:
                starter = mo.groups()[0]
                if not lines[i].strip(starter):
                    i += 1
                    continue
                nb += 1
                lines[i] = '<ul>\n<li>'+lines[i][len(starter):]
                j = i+1
                while j<len(lines):
                    if lines[j].startswith(starter):
                        lines[j] = '<li>'+lines[j][len(starter):]
                        j += 1
                    elif lines[j].startswith(' ') and lines[j].lstrip().startswith(starter):
                        j += 1
                    elif not lines[j].strip():
                        j+=1
                    elif lines[j].startswith(' '*len(starter)):
                        j+=1
                    else:
                        break
                lines[j-1] += '\n</ul>'
            i += 1
        src = '\n'.join(lines)
        if nb==0:break
        lines = src.splitlines()

    # ordered lists
    lines = src.split('\n')
    while True:
        nb = 0
        i = 0
        while i<len(lines):
            if re.search(r'^\d+\.',lines[i]):
                nb += 1
                lines[i] = '<ol>\n<li>'+lines[i][lines[i].find('.')+1:]
                j = i+1
                while j<len(lines):
                    if re.search(r'^\d+\.',lines[j]):
                        lines[j] = '<li>'+lines[j][lines[j].find('.')+1:]
                        j += 1
                    else:
                        break
                lines[j-1] += '\n</ol>'
            i += 1
        src = '\n'.join(lines)
        if nb==0:break
        lines = src.splitlines()

    # headers
    i = 1
    while i<len(lines):
        line = lines[i]
        if line.startswith('=') and not line.strip('=').strip():
            lines[i-1] = '<H1>%s</H1>' %lines[i-1]
            del lines[i]
        elif line.startswith('-') and not line.strip('-').strip():
            lines[i-1] = '<H2>%s</H2>' %lines[i-1]
            del lines[i]
        else:
            i += 1
           
    atx_header_pattern = '^(#+)(.*)(#*)'
    for i,line in enumerate(lines):
        mo = re.search(atx_header_pattern,line)
        if not mo:continue
        level = len(mo.groups()[0])
        lines[i] = re.sub(atx_header_pattern,
            '<H%s>%s</H%s>\n' %(level,mo.groups()[1],level),
            line,count=1)

    src = '\n'.join(lines)
        
    src = re.sub('\n\n+','\n<p>',src)
    return src,scripts