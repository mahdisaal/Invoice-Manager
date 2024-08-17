<?php
require "data.php";
require "functions.php";

if (isset($_GET['number'])) {
    $invoice = getInvoice($_GET['number']);
    if (!$invoice) {
        header("Location: index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice = sanitize($_POST);
    $errors = validate($invoice);

    if (count($errors) === 0) {
        updateInvoice($invoice);
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <style>
        .container {
            display: grid;
        }

        .form {
            justify-self: center;
            display: grid;
            grid-gap: 1rem;
            align-content: center;
            width: 100%;
            max-width: 600px;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            padding: 20px;
        }

        .button {
            justify-self: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Invoice Manager</h1>
        <div class="d-flex flex-row justify-content-between mb-3">
            <p>Update this invoice.</p>
            <a href="index.php">
                < Back</a>
        </div>

        <div class="form mt-5">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="number" class="form-label fw-bold text-primary">Number</label>
                    <input disabled class="form-control" name="number" value="<?php echo htmlspecialchars($invoice['number'], ENT_QUOTES, 'UTF-8'); ?>" />
                </div>

                <div class="mb-3">
                    <label for="client" class="form-label fw-bold text-primary">Client Name</label>
                    <input disabled type="text" class="form-control" name="client" placeholder="Client Name" value="<?php echo htmlspecialchars($invoice['client'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label fw-bold text-primary">Amount</label>
                    <input class="form-control" type="number" name="amount" value="<?php echo htmlspecialchars($invoice['amount'], ENT_QUOTES, 'UTF-8'); ?>" />
                    <?php if (isset($errors['amount'])) : ?>
                        <div class="alert alert-primary mt-3" role="alert"><?php echo $errors['amount']; ?></div>
                    <?php endif ?>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold text-primary">Invoice Status</label>
                    <select class="form-select" name="status">
                        <option value="">Select a status</option>
                        <?php for ($i = 0; $i < count($statuses); $i++) : ?>
                            <option value="<?php echo htmlspecialchars($statuses[$i], ENT_QUOTES, 'UTF-8'); ?>" <?php if ($statuses[$i] === $invoice['status']) : ?>selected<?php endif; ?>>
                                <?php echo $statuses[$i]; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($errors['status'])) : ?>
                        <div class="alert alert-primary mt-3" role="alert"><?php echo $errors['status']; ?></div>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <input type="file" class="form-control" name="pdfInvoice" accept=".pdf">
                </div>
                <div class="container text-center">
                    <button type="submit" class="button btn btn-outline-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>