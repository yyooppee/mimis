<?php include "component/db.php";
$sql = "SELECT * FROM orderlist WHERE order_stat = 'pending';";
$result = $conn->query($sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<html>
<head>
<?php include "component/head.php"; ?>
</head>
<body>
    <?php include "component/header.php"; ?>

<div class = "content">
        <div class = " container">
        <h1> Mimi's Pet Shop </h1>
        <p>Mahayahay, Gabi, Cordova</p>
        <p>mimispetcorner@gmail.com</p>
        </div>
    </div>
    <div class="content">
        <div class="container">  
            <form method="post" action="">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Order ID</th>
                            <th>Product ID</th>
                            <th>Order Name</th>
                            <th>Product Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($rows as $row){
                        ?>
                        <tr>
                            <td><?php echo $row['order_id']?></td>
                            <td><?php echo $row['prod_id']?></td>
                            <td><?php echo $row['order_name']?></td>
                            <td><?php echo $row['prod_desc']?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $row['order_id']?>]" value="<?php echo $row['order_qty']?>" min="1">
                            </td>
                            <td><?php echo $row['order_price']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if(isset($_POST["update"])) {
                    foreach ($rows as $row) {
                        $order_id = $row["order_id"];
                        $newQuantity = $_POST['quantity'][$order_id];
                        $prod_id = $row['prod_id'];
                        $prod_price_sql = "SELECT prod_price FROM products WHERE prod_id = '$prod_id'";
                        $result_prod_price = mysqli_query($conn, $prod_price_sql);
                
                        if ($result_prod_price && $prod_price_row = mysqli_fetch_assoc($result_prod_price)) {
                            $prod_price = $prod_price_row['prod_price'];
                            $newPrice = $prod_price * $newQuantity;
                            $update_qty_price_sql = "UPDATE orderlist SET order_qty = '$newQuantity',
                             order_price = '$newPrice' WHERE order_id = '$order_id'";
                            $result_update_qty_price = mysqli_query($conn, $update_qty_price_sql);
                        } else {
                            echo "<script>alert('Error fetching product price')</script>";
                        }
                    }
                
                    echo "<script>alert('Cart Successfully updated!')</script>";
                }
                ?>
                <input type="submit" value="Update Cart" name="update">
            </form>
        </div>
    </div>

    <?php include "component/scripts.php"; ?>
</body>
</html>
