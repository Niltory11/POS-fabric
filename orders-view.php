<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View
                <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Invoice Print / Download PDF</a>
                <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            if (isset($_GET['track'])) {
                if ($_GET['track'] == '') {
                    ?>
                    <div class="text-center py-5">
                        <h5>No Tracking Number Found</h5>
                        <div>
                            <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                        </div>
                    </div>
                    <?php
                    return false;
                }

                $trackingNo = validate($_GET['track']);

                $query = "SELECT o.*, c.* FROM orders o, customers c 
                          WHERE c.id = o.customer_id AND tracking_no='$trackingNo' 
                          ORDER BY o.id DESC";

                $orders = mysqli_query($conn, $query);
                if ($orders) {
                    if (mysqli_num_rows($orders) > 0) {

                        $orderData = mysqli_fetch_assoc($orders);
                        $orderId = $orderData['id'];

                        ?>
                        <div class="card card-body shadow border-1 mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Order Details</h4>
                                    <label class="mb-1">
                                        Tracking No: 
                                        <span class="fw-bold"><?= $orderData['tracking_no']; ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Order Date: 
                                        <span class="fw-bold"><?= $orderData['order_date']; ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Order Status: 
                                        <span class="fw-bold"><?= $orderData['order_status']; ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Payment Mode: 
                                        <span class="fw-bold"><?= $orderData['payment_mode']; ?></span>
                                    </label>
                                    <br/>
                                </div>
                                <div class="col-md-6">
                                    <h4>User Details</h4>
                                    <label class="mb-1">
                                        Full Name: 
                                        <span class="fw-bold"><?= $orderData['name']; ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Address: 
                                        <span class="fw-bold"><?= $orderData['email']; ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Phone Number: 
                                        <span class="fw-bold"><?= $orderData['phone']; ?></span>
                                    </label>
                                    <br/>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, 
                                                oi.advance as orderItemAdvance, oi.due as orderItemDue, 
                                                oi.discount as orderItemDiscount,
                                                o.*, oi.*, p.* 
                                           FROM orders as o, order_items as oi, products as p
                                           WHERE oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo'";
                        
                        $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                        if ($orderItemsRes) {
                            if (mysqli_num_rows($orderItemsRes) > 0) {
                                ?>
                                <h4 class="my-3">Order Items Details</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Discount</th>
                                                <th class="text-center">Advance</th>
                                                <th class="text-center">Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orderItemsRes as $orderItemRow) : ?>
                                                <tr>
                                                    <td>
                                                        <img src="<?= $orderItemRow['image'] != '' ? '../' . $orderItemRow['image'] : '../assets/images/no-img.jpg'; ?>" 
                                                             style="width:50px;height:50px;"
                                                             alt="Img" />
                                                        <?= $orderItemRow['name']; ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= number_format($orderItemRow['orderItemPrice'], 2) ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= $orderItemRow['orderItemQuantity']; ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= number_format(($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity']), 2) ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= number_format($orderItemRow['orderItemDiscount'], 2); ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= number_format($orderItemRow['orderItemAdvance'], 2); ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= number_format($orderItemRow['orderItemDue'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Price: </td>
                                                <td colspan="2" class="text-end fw-bold">TK: <?= number_format($orderData['total_amount'], 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo '<h5>No Items Found!</h5>';
                                return false;
                            }
                        } else {
                            echo '<h5>Something Went Wrong!</h5>';
                            return false;
                        }
                        ?>
                        <?php
                    } else {
                        echo '<h5>No Record Found!</h5>';
                        return false;
                    }
                } else {
                    echo '<h5>Something Went Wrong!</h5>';
                }
            } else {
                ?>
                <div class="text-center py-5">
                    <h5>No Tracking Number Found</h5>
                    <div>
                        <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
