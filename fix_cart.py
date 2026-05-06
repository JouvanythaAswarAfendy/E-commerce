import os

file1 = 'c:/laragon/www/E-commerce/resources/views/cart/index.blade.php'
if os.path.exists(file1):
    with open(file1, 'r', encoding='utf-8') as f:
        content = f.read()
    
    content = content.replace('    <x-header />\n', '')
    content = content.replace('    <x-footer />\n', '')
    
    with open(file1, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Cart fixed")
