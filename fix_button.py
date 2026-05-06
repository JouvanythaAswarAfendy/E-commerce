import os
file1 = 'c:/laragon/www/E-commerce/resources/views/welcome.blade.php'
if os.path.exists(file1):
    with open(file1, 'r', encoding='utf-8') as f:
        content = f.read()
    content = content.replace('<x-button ', '<button type="button" ')
    content = content.replace('</x-button>', '</button>')
    with open(file1, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Button fixed")
