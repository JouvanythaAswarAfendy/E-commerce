import os

model_methods = {
    'Cart': ['addCartItem', 'getCartItems', 'removeCartItem', 'updateCartItemQty'],
    'Category': ['createCategory', 'deleteCategory', 'getCategories', 'getDetailCategory', 'updateCategory'],
    'Notification': ['createNotification', 'getNotifications', 'readNotification'],
    'OfflineTransaction': ['createOfflineOrder', 'getDailyStats', 'getOfflineOrders'],
    'OfflineTransactionItem': ['getOfflineTransactionItems'],
    'Order': ['confirmOrderCompleted', 'createOrder', 'getAllOrders', 'getDetailOrder', 'getDetailUserOrder', 'getOnlineOrders', 'getUserOrders', 'paymentOrder', 'updateOrderStatus'],
    'OrderItem': ['getOrderItems'],
    'Product': ['createProduct', 'deleteProduct', 'getAllProducts', 'getProductById', 'searchProducts', 'updateProduct'],
    'User': ['forgotPassword', 'getUserProfile', 'login', 'logout', 'register', 'resetPassword', 'updateProfile']
}

for model, methods in model_methods.items():
    filepath = f"app/Models/{model}.php"
    if not os.path.exists(filepath): continue
    
    with open(filepath, 'r') as f:
        content = f.read()
    
    stubs = ""
    for method in methods:
        if f"function {method}(" not in content:
            stubs += f"\n    public function {method}()\n    {{\n        // TODO: Implement {method}() from UML Class Diagram\n    }}\n"
    
    if stubs:
        last_brace_idx = content.rfind('}')
        if last_brace_idx != -1:
            new_content = content[:last_brace_idx] + "\n    // --- UML Class Diagram Methods ---\n" + stubs + content[last_brace_idx:]
            with open(filepath, 'w') as f:
                f.write(new_content)
    print(f"{model} patched.")
