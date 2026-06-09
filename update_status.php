<?php
$conn = new mysqli("localhost", "root", "", "taaza_db");

if(isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $query = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";
    mysqli_query($conn, $query);
    
    header("Location: admin_orders.php"); // Burayı kendi admin sayfanın adına göre düzelt
}
?>