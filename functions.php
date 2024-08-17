<?php

function sanitize($data)
{
    return array_map(function ($value) {
        return htmlspecialchars(stripslashes(trim($value)));
    }, $data);
}

function validate($invoice)
{
    $errors = [];
    $fields = ['amount', 'status'];

    foreach ($fields as $field) {
        switch ($field) {
            case 'amount':
                if (empty($invoice[$field])) {
                    $errors[$field] = "Amount is required.";
                } else if (!filter_var($invoice[$field], FILTER_VALIDATE_INT)) {
                    $errors[$field] = "Amount must be an integer.";
                }
                break;
            case 'status':
                if (empty($invoice[$field])) {
                    $errors[$field] = 'Status is required.';
                } else if ($invoice[$field] != 'draft' && $invoice[$field] != 'pending' && $invoice[$field] != 'paid') {
                    $errors[$field] = 'The status must be either draft, pending, or paid.';
                }
                break;
        }
    }

    return $errors;
}

function savePdf($number)
{
    $pdfInvoice = $_FILES['pdfInvoice'];

    if ($pdfInvoice['error'] === UPLOAD_ERR_OK) {
        // get the file extension
        $ext = pathinfo($pdfInvoice['name'], PATHINFO_EXTENSION);
        $filename = $number . '.' . $ext;

        if (!file_exists('documents/')) {
            mkdir('documents/');
        }

        $dest = 'documents/' . $filename;

        return move_uploaded_file($pdfInvoice['tmp_name'], $dest);
    }
    return false;
}

function getInvoiceNumber($length = 5)
{
    $letters = range('A', 'Z');
    $number = [];

    for ($i = 0; $i < $length; $i++) {
        array_push($number, $letters[rand(0, count($letters) - 1)]);
    }
    return implode($number);
}

function getAllInvoices()
{
    global $db;

    if (!isset($_SESSION['user_id'])) {
        return []; // Return an empty array if user_id is not set in session
    }
    $user_id = $_SESSION['user_id'];
    if ($user_id == 4) {
        $sql = "SELECT invoices.number, users.username AS client, invoices.amount, statuses.status
        FROM invoices
        JOIN users ON invoices.user_id = users.id
        JOIN statuses ON invoices.status_id = statuses.id";
        $result = $db->query($sql);
        $invoices = $result->fetchAll();
    } else {

        $sql = "SELECT invoices.number, users.username AS client, invoices.amount, statuses.status
            FROM invoices
            JOIN users ON invoices.user_id = users.id
            JOIN statuses ON invoices.status_id = statuses.id
            WHERE invoices.user_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $invoices;
}

function getInvoices($status)
{
    global $db;
    $sql = "SELECT invoices.number, users.username AS client, invoices.amount, statuses.status
            FROM invoices
            JOIN users ON invoices.user_id = users.id
            JOIN statuses ON invoices.status_id = statuses.id
            WHERE statuses.status = :status";
    $result = $db->prepare($sql);
    $result->execute([':status' => $status]);
    $invoices = $result->fetchAll();

    return $invoices;
}

function getInvoice($number)
{
    global $db;
    $sql = "SELECT invoices.number, users.username AS client, invoices.amount, statuses.status
            FROM invoices
            JOIN users ON invoices.user_id = users.id
            JOIN statuses ON invoices.status_id = statuses.id
            WHERE invoices.number = :number";
    $result = $db->prepare($sql);
    $result->execute([':number' => $number]);
    $invoice = $result->fetch();

    return $invoice;
}

function addInvoice($invoice)
{
    global $db, $statuses;

    $status_id = array_search($invoice['status'], $statuses) + 1;

    $sql = "INSERT INTO invoices (number, amount, status_id, user_id)
            VALUES(:number, :amount, :status_id, :user_id)";
    $result = $db->prepare($sql);
    $number = getInvoiceNumber();
    $result->execute([
        ':number' => $number,
        ':amount' => $invoice['amount'],
        ':status_id' => $status_id,
        ':user_id' => $invoice['user_id'] // Use user_id from the invoice array
    ]);

    savePdf($number);
}

function updateInvoice($invoice)
{
    global $db, $statuses;
    $status_id = array_search($invoice['status'], $statuses) + 1;
    $sql = "UPDATE invoices
            SET amount = :amount, status_id = :status_id
            WHERE number = :number";
    $result = $db->prepare($sql);
    $result->execute([
        ':number' => $invoice['number'],
        ':amount' => $invoice['amount'],
        ':status_id' => $status_id
    ]);

    savePdf($invoice['number']);
}

function deleteInvoice($number)
{
    global $db;
    $sql = "DELETE FROM invoices
            WHERE number = :number";
    $result = $db->prepare($sql);
    $result->execute([':number' => $number]);
}
