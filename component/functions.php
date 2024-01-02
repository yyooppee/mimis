<?php
function generatePDFReceipt($order_id, $total, $orderDetails, $invoiceNumber, $invoiceDate, $emp_id, $conn)
{
    require_once('tcpdf/tcpdf.php');

    // Create a new PDF document
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, "Mimi's Pet Corner", 0, true, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, "Mahayahay, Gabi,", 0, true, 'C');
    $pdf->Cell(0, 10, "Cordova", 0, true, 'C');
    $pdf->Cell(0, 10, "Tax No.: 429-885-215-001", 0, true, 'C');
    $pdf->Cell(0, 10, "09284331344", 0, true, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Invoice Number: ' . $order_id, 0, true, 'C');
    $pdf->Cell(0, 10, 'Invoice Date: ' . $invoiceDate, 0, true, 'C');
    $pdf->Cell(0, 10, 'User: ' . 'Employee # ' . $emp_id, 0, true, 'C');
    $pdf->Cell(0, 10, 'Order ID: ' . $invoiceNumber, 0, true, 'C');
    $pdf->Ln(5);

    $productPrices = [];

    foreach ($orderDetails as $order) {
        $pdf->Cell(0, 10, 'Product: ' . $order['order_name'], 0, true, 'C');

        $prod_id = $order['prod_id'];
        $prod_price_sql = "SELECT prod_price FROM products WHERE prod_id = '$prod_id'";
        $result_prod_price = mysqli_query($conn, $prod_price_sql);
        $row_prod_price = mysqli_fetch_assoc($result_prod_price);
        $productPrice = $row_prod_price['prod_price'];

        $pdf->Cell(0, 10, 'Price: ' . $productPrice . ' x ' . $order['order_qty'] . '(qty)', 0, true, 'C');
        $pdf->Cell(0, 10, 'Total: ' . $order['order_price'], 0,true, 'C');
        $pdf->Ln(5);
    }

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 10, 'Total Amount: ' . $total, 2, 1, 'C');

    $pdf->Output('D:\New folder (2)\New folder\resibo\receipt_' . $order_id . '.pdf', 'F');
}
?>
