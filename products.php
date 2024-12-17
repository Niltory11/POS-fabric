<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Products
                <a href="products-create.php" class="btn btn-primary float-end">Add Product</a>
            </h4>
        </div>
        <div class="card-body">
            
            <?php alertMessage(); ?>

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" id="productSearch" class="form-control" placeholder="Search products by name..." onkeyup="searchProducts()">
            </div>

            <?php
            $products = getAll('products');
            if(!$products){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($products) > 0)
            {
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="productsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $item) : ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td>
                                <img src="../<?= $item['image']; ?>" style="width:50px;height:50px;" alt="Img" />
                            </td>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['unit'] ?></td>
                            <td>
                                <?php  
                                    if($item['quantity'] <= 0){
                                        echo '<span class="badge bg-danger">Stock Out</span>';
                                    }else{
                                        echo '<span class="badge bg-primary">Available</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="products-edit.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a 
                                    href="#" 
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Only Manager can Delete!')"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else
            {
                ?>
                    <h4 class="mb-0">No Record found</h4>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
    function searchProducts() {
        const input = document.getElementById('productSearch').value.toLowerCase();
        const table = document.getElementById('productsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const productName = cells[2]?.textContent.toLowerCase(); // Assuming name is in the 3rd column
            rows[i].style.display = productName && productName.includes(input) ? '' : 'none';
        }
    }
</script>

<?php include('includes/footer.php'); ?>