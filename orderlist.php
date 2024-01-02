<?php include "component/db.php"; ?>
<?php 
$sql = "SELECT * FROM orderlist WHERE order_stat = 'pending';";
$result = $conn->query($sql);
?>

<html>
<head>
    <?php include "component/head.php"; ?>
</head>
<body>
    <?php include "component/header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
            <p>Mahayahay, Gabi, Cordova</p>
            <p>mimispetcorner@gmail.com</p>
        </div>
    </div>

    <div class="content">
        <div class="container">  
            <h2> Cart </h2>
            <form method="post" action="">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>order_id</th>
                            <th>prod_id</th>
                            <th>order_name</th>
                            <th>prod_desc</th>
                            <th>order_qty</th>
                            <th>order_price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Fetch all rows into an array
                        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        foreach($rows as $row){
                        ?>
                        <tr>
                            <td><?php echo $row['order_id']?></td>
                            <td><?php echo $row['prod_id']?></td>
                            <td><?php echo $row['order_name']?></td>
                            <td><?php echo $row['prod_desc']?></td>
                            <td><?php echo $row['order_qty']?></td>
                            <td><?php echo $row['order_price']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <input type="submit" value="Purchase" name="buy">
                <input type="submit" value="Edit Cart" name="edit">
            </form>

            <?php
            if(isset($_POST["buy"])){
                $total = 0;
                $inv_order_id = 0;

                foreach($rows as $row){
                    $inv_order_id = $row["order_id"];
                    $total += $row['order_price'];
                }
                $emp_id = '1';

                // Insert into the invoice table
                $inv_date = date('Y-m-d H:i:s', time());
                $inv_sql = "INSERT INTO invoice (inv_id, order_id, inv_amnt, inv_date, emp_id) VALUES (
                    '',
                    '$inv_order_id',
                    '$total',
                    '$inv_date',
                    '$emp_id'
                )";

                $result_insert = mysqli_query($conn, $inv_sql);

                if($result_insert) {
                    echo "<script>alert('Purchase Successful')</script>";
                    $inv_id_query = "SELECT MAX(inv_id) AS max_inv_id FROM invoice";
                    $result_inv_id = mysqli_query($conn, $inv_id_query);
                    $row_inv_id = mysqli_fetch_assoc($result_inv_id);
                    $inv_id = $row_inv_id['max_inv_id'];
            
                    include "component/functions.php";
                    $prod_id = $row['prod_id'];
                    $prod_price_sql = "SELECT prod_price FROM products WHERE prod_id = '$prod_id'";
                    $result_prod_price = mysqli_query($conn, $prod_price_sql);
                    generatePDFReceipt($inv_id, $total, $rows, $inv_order_id, $inv_date, $emp_id, $conn);
                } else {
                    echo "<script>alert('Failed to insert data')</script>";
                }

                $update_sql = "UPDATE orderlist SET order_stat = 'purchased' WHERE order_stat = 'pending'";
                $result_update = mysqli_query($conn, $update_sql);
            }
            ?>
            <?php
            if(isset($_POST["edit"])){
                echo '<script>window.location.href = "editcart.php";</script>';
            }
            ?>
        </div>
    </div>

    <?php include "component/scripts.php"; ?>
</body>
</html>
