<?php
require 'include/dbcon.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$where = '';

if ($start_date && $end_date) {
    $where = "WHERE t.borrow_date BETWEEN '$start_date' AND '$end_date'";
}

$query = "SELECT t.transaction_id, u.name AS user, b.title AS book,
                 t.borrow_date, t.due_date, t.return_date, t.status
          FROM transactions t
          JOIN user u ON t.user_id = u.user_id
          JOIN books b ON t.book_id = b.book_id
          $where
          ORDER BY t.borrow_date DESC";
$result = mysqli_query($con, $query);


header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="transactions_report.csv"');


$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'User', 'Book', 'Borrow Date', 'Due Date', 'Return Date', 'Status']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
