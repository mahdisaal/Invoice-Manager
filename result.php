<?php
require "data.php";
require "functions.php";

if (isset($_GET['status'])) {
    $invoices = getInvoices($_GET['status']);
} else {
    $invoices = getAllInvoices();
}

?>
<div class="d-flex flex-row justify-content-between">
    <p>There are <?php echo count($invoices) ?> invoices.</p>
    <a href="add.php">Add ></a>
</div>
<div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a href="index.php"><button class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All</button></a>
    <a href="index.php?status=draft"><button class="nav-link" id="nav-draft-tab" data-bs-toggle="tab" data-bs-target="#nav-draft" type="button" role="tab" aria-controls="nav-draft" aria-selected="false">Draft</button></a>
    <a href="index.php?status=pending"><button class="nav-link" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending" type="button" role="tab" aria-controls="nav-pending" aria-selected="false">Pending</button></a>
    <a href="index.php?status=paid"><button class="nav-link" id="nav-paid-tab" data-bs-toggle="tab" data-bs-target="#nav-paid" type="button" role="tab" aria-controls="nav-paid" aria-selected="false">Paid</button></a>
</div>
<table class="table table-borderless">
    <thead>
        <tr>
            <th>Number</th>
            <th>Client</th>
            <th>Amount</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice) : ?>
            <tr>
                <td><strong>#<?php echo $invoice['number']; ?></strong></td>
                <td class="text-primary"><?php echo $invoice['client']; ?></td>
                <td>$ <?php echo $invoice['amount']; ?></td>
                <td class="td-status"><button type="button" class="btn btn-status <?php echo $invoice['status']; ?>"><?php echo $invoice['status']; ?></button></td>
                <td><a href="update.php?number=<?php echo $invoice['number'] ?>" class="btn btn-outline-primary">Edit</a></td>
                <td>
                    <form method="post" action="delete.php">
                        <input type="hidden" name="number" value="<?php echo $invoice['number']; ?>" />
                        <button class="btn btn-outline-danger">Delete</button>
                    </form>
                </td>
                <?php if (file_exists("documents/" . $invoice['number'] . ".pdf")) : ?>
                    <td>
                        <a class="btn btn-outline-info" href="documents/<?php echo $invoice['number']; ?>.pdf" target="_blank">View</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>