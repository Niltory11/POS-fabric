<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                <p>Served by: <?= $_SESSION['loggedInUser']['name']; ?></p>
                <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <div id="myBillingArea">
                <?php
                    if (isset($_GET['track'])) {
                        $trackingNo = validate($_GET['track']);
                        if ($trackingNo == '') {
                            ?>
                            <div class="text-center py-5">
                                <h5>Please provide Tracking Number</h5>
                                <div>
                                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                                </div>
                            </div>
                            <?php
                            exit;
                        }

                        $orderQuery = "SELECT o.*, c.* FROM orders o, customers c 
                            WHERE c.id=o.customer_id AND tracking_no='$trackingNo' LIMIT 1";
                        $orderQueryRes = mysqli_query($conn, $orderQuery);

                        if (!$orderQueryRes) {
                            echo "<h5>Something Went Wrong</h5>";
                            exit;
                        }

                        if (mysqli_num_rows($orderQueryRes) > 0) {
                            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                            ?>
                            <table style="width: 100%; margin-bottom: 20px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;" colspan="2">
                                            <h4 style="font-size: 23px; line-height: 30px; margin:2px; padding: 0;">RJ Group Ltd</h4>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">Shubhanighat, Bandarbazar Road</p>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">Sylhet Bangladesh</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Customer Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Name: <?= $orderDataRow['name'] ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Phone No: <?= $orderDataRow['phone'] ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Address: <?= $orderDataRow['email'] ?> </p>
                                        </td>
                                        <td align="end">
                                            <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Invoice Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice No: <?= $orderDataRow['tracking_no']; ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Order Date: <?= date('d M Y', strtotime($orderDataRow['order_date'])); ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Delivery Date: <?= date('d M Y', strtotime($orderDataRow['delivery_date'])); ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Served by: <?= $_SESSION['loggedInUser']['name']; ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Address: Shibganj Sylhet  </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            echo "<h5>No data found</h5>";
                            exit;
                        }

                        $orderItemQuery = "SELECT oi.quantity AS orderItemQuantity, oi.price AS orderItemPrice, oi.discount AS orderItemDiscount, 
                                           oi.advance AS orderItemAdvance, oi.due AS orderItemDue, p.name 
                                           FROM order_items oi 
                                           JOIN products p ON p.id = oi.product_id 
                                           JOIN orders o ON oi.order_id = o.id 
                                           WHERE o.tracking_no = '$trackingNo'";
                        $orderItemQueryRes = mysqli_query($conn, $orderItemQuery);

                        if ($orderItemQueryRes) {
                            if (mysqli_num_rows($orderItemQueryRes) > 0) {
                                ?>
                                <div class="table-responsive mb-3">
                                    <table style="width:100%;" cellpadding="5">
                                        <thead>
                                            <tr>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="5%">ID</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;">Product Name</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Discount</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Advance</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Due</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $i = 1;
                                                foreach ($orderItemQueryRes as $row) : 
                                            ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['orderItemPrice'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['orderItemQuantity']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['orderItemDiscount'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['orderItemAdvance'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['orderItemDue'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    <?= number_format(($row['orderItemPrice'] * $row['orderItemQuantity']) - $row['orderItemDiscount'], 2); ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo "<h5>No data found</h5>";
                            }
                        } else {
                            echo "<h5>Something Went Wrong!</h5>";
                        }
                    } else {
                        ?>
                        <div class="text-center py-5">
                            <h5>No Tracking Number Parameter Found</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-info px-4 mx-1" onclick="window.print()">Print</button>
                <button class="btn btn-primary px-4 mx-1" onclick="downloadPDF('<?= $orderDataRow['tracking_no']; ?>')">Download PDF</button>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
