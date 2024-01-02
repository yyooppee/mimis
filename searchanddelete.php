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
            <h1>Search Invoice</h1>
            <form method="post" action="">
                <label for="invoice_id">Enter Invoice ID:</label>
                <input type="text" id="invoice_id" name="invoice_id" required>
                <input type="submit" value="Search" name="search">
            </form>
            <div class="content">
                <div class="container">  
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Invoice Number</th>
                                <th>Order Number</th>
                                <th>Total Amount</th>
                                <th>Date of Invoice</th>
                                <th>Employee ID</th>
                                <th>delete?</th>
                            </tr>
                        </thead>
                        <tbody>

            <?php 
            if(isset($_POST["search"])){
                $invoiceId = $_POST["invoice_id"];
                $invoiceId = mysqli_real_escape_string($conn, $invoiceId);
                $sql = "SELECT * FROM invoice WHERE inv_id = '$invoiceId'";
                $result = $conn->query($sql);
            
                if($result){
                    $invoiceData = $result->fetch_assoc();
            
                    // Display the information in a table
                    if($invoiceData){ 
                        echo "<h2>Invoice Information</h2>";
                        echo "<tr>";
                        echo "<td>" . $invoiceData['inv_id'] . "</td>";
                        echo "<td>" . $invoiceData['order_id'] . "</td>";
                        echo "<td>" . $invoiceData['inv_amnt'] . "</td>";
                        echo "<td>" . $invoiceData['inv_date'] . "</td>";
                        echo "<td>" . $invoiceData['emp_id'] . "</td>";
                        echo "<td><form method='post' action=''><input type='hidden' name='delete_id' value='" . $invoiceData['inv_id'] . "'><input type='submit' name='delete' value='Delete'></form></td>";
                        echo "</tr>";
                    } else {
                        echo "<p>No invoice found with the specified ID.</p>";
                    }
                } else {
                    echo "Error: " . $conn->error;
                }
            }
            if(isset($_POST["delete"])){
                $delete_id = $_POST["delete_id"];
                $delete_sql = "DELETE FROM invoice WHERE inv_id = '$delete_id'";
                $result_delete = mysqli_query($conn, $delete_sql);

                if($result_delete) {
                    echo "<script>alert('Invoice deleted successfully')</script>";
                } else {
                    echo "<script>alert('Failed to delete invoice')</script>";
                }
            }
            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include "component/scripts.php"; ?>
</body>
</html>
