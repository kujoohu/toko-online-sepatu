<?php

session_start();

include("includes/db.php");
include("functions/functions.php");

?>

<?php

if(isset($_GET['c_id'])) {

    $customer_id = $_GET['c_id'];

}

$ip_add = getRealIpUser();
$status = "Pending";
$invoice_no = mt_rand();

$select_cart = "SELECT * FROM cart WHERE ip_add='$ip_add'";
$run_cart = mysqli_query($con,$select_cart);

while($row_cart = mysqli_fetch_array($run_cart)) {

    $pro_id = $row_cart['p_id'];
    $pro_qty = $row_cart['qty'];
    $pro_size = $row_cart['size'];
    $custom_size = $row_cart['custom_size'];

    $get_products = "SELECT * FROM product WHERE product_id='$pro_id'";
    $run_products = mysqli_query($con,$get_products);

    while($row_products = mysqli_fetch_array($run_products)) {

        if ($custom_size=="") {

            $sub_total = $row_products['product_price']*$pro_qty;

        } else {

            $sub_total = $row_products['product_price']*$pro_qty+50000;

        }

        $insert_customer_order = "INSERT INTO customer_orders (customer_id,due_amount,invoice_no,qty,size,custom_size,order_date,order_status) VALUES
        ('$customer_id','$sub_total','$invoice_no','$pro_qty','$custom_size','$pro_size',NOW(),'$status')";

        $run_customer_order = mysqli_query($con,$insert_customer_order);

        $insert_pending_order = "INSERT INTO pending_orders (customer_id,invoice_no,product_id,qty,size,custom_size,order_status) VALUES
        ('$customer_id','$invoice_no','$pro_id','$pro_qty','$pro_size','$custom_size','$status')";

        $run_pending_order = mysqli_query($con,$insert_pending_order);

        $delete_cart = "DELETE FROM cart WHERE ip_add='$ip_add'";
        $run_delete = mysqli_query($con,$delete_cart);

        echo "<script>alert('Your orders has been submitted, Thanks')</script>";

        echo "<script>window.open('customer/my_account.php?my_orders','_self')</script>";

    }

}

?>
