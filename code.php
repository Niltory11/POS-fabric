<?php

require_once('../config/function.php');

// Add Admin
if (isset($_POST['saveAdmin'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = password_hash(validate($_POST['password']), PASSWORD_BCRYPT);
    $phone = validate($_POST['phone']);
    $role = validate($_POST['role']); // Role field (admin, manager, salesman)
    $is_ban = isset($_POST['is_ban']) ? 1 : 0;

    if (!empty($name) && !empty($email) && !empty($password)) {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => $role,
            'is_ban' => $is_ban
        ];

        $result = insert('admins', $data);
        if ($result) {
            redirect('admins.php', 'Admin/Staff Created Successfully!');
        } else {
            redirect('admins-create.php', 'Something Went Wrong!');
        }
    } else {
        redirect('admins-create.php', 'Please fill all required fields.');
    }
}

// Add Customer
if (isset($_POST['saveCustomer'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']); // Treat as Address
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1 : 0; // Status for visibility

    if (!empty($name)) {
        $data = [
            'name' => $name,
            'email' => $email, // Store email field as address
            'phone' => $phone,
            'status' => $status
        ];

        $result = insert('customers', $data);
        if ($result) {
            redirect('customers.php', 'Customer Added Successfully!');
        } else {
            redirect('customers-create.php', 'Something Went Wrong!');
        }
    } else {
        redirect('customers-create.php', 'Customer Name is Required.');
    }
}

// Add Product
if (isset($_POST['saveProduct'])) {
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $purchaseRate = validate($_POST['purchaseRate']);
    $supplier_name = validate($_POST['supplier_name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $memo_id = validate($_POST['memo_id']);
    $quantity = validate($_POST['quantity']);
    $total = validate($_POST['total']);
    $minimum_sale_rate = validate($_POST['minimum_sale_rate']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = "assets/uploads/products/" . $filename;
    } else {
        $finalImage = '';
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'supplier_name' => $supplier_name,
        'memo_id' => $memo_id,
        'description' => $description,
        'purchaseRate' => $purchaseRate,
        'price' => $price,
        'quantity' => $quantity,
        'total' => $total,
        'minimum_sale_rate' => $minimum_sale_rate,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = insert('products', $data);
    if ($result) {
        redirect('products.php', 'Product Created Successfully!');
    } else {
        redirect('products-create.php', 'Something Went Wrong!');
    }
}

// Update Product
if (isset($_POST['updateProduct'])) {
    $product_id = validate($_POST['product_id']);

    $productData = getById('products', $product_id);
    if (!$productData) {
        redirect('products.php', 'No such product found');
    }

    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $minimum_sale_rate = validate($_POST['minimum_sale_rate']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = "assets/uploads/products/" . $filename;

        $deleteImage = "../" . $productData['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }
    } else {
        $finalImage = $productData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'minimum_sale_rate' => $minimum_sale_rate,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = update('products', $product_id, $data);

    if ($result) {
        redirect('products-edit.php?id=' . $product_id, 'Product Updated Successfully!');
    } else {
        redirect('products-edit.php?id=' . $product_id, 'Something Went Wrong!');
    }
}
?>
