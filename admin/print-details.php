<?php 
include('../connection.php');
require '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$id = $_GET['id'];
$sql = mysqli_query($conn, "SELECT * FROM booking WHERE booking_id='$id'");
$user = mysqli_fetch_assoc($sql);

$dompdf = new Dompdf();
ob_start();
require('details_pdf.php');
$html = ob_get_contents();
ob_end_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('print-details.pdf', ['Attachment' => false]);
?>
