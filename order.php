<?php include "component/db.php";?>

<?php
$sql = "SELECT * FROM products";
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
            <h2>Pet Supplies</h2>
            <form method="post" action="">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $prod_id = $row['prod_id'];
                    ?>
                    <div class="product">
                        <button type="button" class="collapsible">
                            <h2><?php echo $row['prod_name']; ?></h2>
                        </button>
                        <div class="content">
                            <p>Description: <?php echo $row['prod_desc']; ?></p>
                            <p>Price: <?php echo $row['prod_price']; ?></p>
                            <input type="number" name="quantity_<?php echo $prod_id; ?>" value="">
                            <input type="hidden" name="prod_desc_<?php echo $prod_id; ?>" value="<?php echo $row['prod_desc']; ?>">
                            <input type="hidden" name="prod_price_<?php echo $prod_id; ?>" value="<?php echo $row['prod_price']; ?>">
                            <input type="hidden" name="prod_id_<?php echo $prod_id; ?>" value="<?php echo $prod_id; ?>">
                            <input type="submit" name="button_<?php echo $prod_id; ?>" value="Add to Cart">
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>

    <?php
    foreach ($result as $row) {
        $prod_id = $row['prod_id'];
        $button_name = "button_" . $prod_id;

        if (isset($_POST[$button_name])) {
            $prod_desc = mysqli_real_escape_string($conn, $_POST["prod_desc_$prod_id"]);
            $query_prod_id = "SELECT prod_id FROM products WHERE prod_desc = '$prod_desc'";
            $result_prod_id = mysqli_query($conn, $query_prod_id);

            if ($result_prod_id) {
                if ($row_prod_id = mysqli_fetch_assoc($result_prod_id)) {
                    $prod_id = $row_prod_id['prod_id'];
                    $order_name = $row["prod_name"];
                    $ord_qty = $_POST["quantity_$prod_id"];
                    $subtotal_price = $_POST["prod_price_$prod_id"];
                    $prod_stat = 'pending';

                    $ord_price = $subtotal_price * $ord_qty;

                    $sql_insert = "INSERT INTO orderlist (order_id, prod_id, order_name, order_qty, order_price, prod_desc, order_stat)
                        VALUES (
                            '', 
                            '$prod_id',
                            '$order_name',
                            '$ord_qty',
                            '$ord_price',
                            '$prod_desc',
                            '$prod_stat'
                        )";

                    $result_insert = mysqli_query($conn, $sql_insert);

                    if ($result_insert) {
                        echo "<script>alert('Data inserted')</script>";
                    } else {
                        echo "<script>alert('Error inserting data: " . mysqli_error($conn) . "')</script>";
                    }
                } else {
                    echo "<script>alert('Product not found')</script>";
                }
            } else {
                echo "<script>alert('Error querying product: " . mysqli_error($conn) . "')</script>";
            }
        }
    }
    
    ?>

    <form action="/" method="post"></form>
    <?php include "component/scripts.php"; ?>
</body>
</html>