<?php
require "data.php";

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
        .btn-status {
            background-color: transparent;
            border-radius: 5px;
            width: 70%;
        }

        .pending {
            color: #ffc384;
            border-color: #ffc384;
        }

        .pending:hover {
            background-color: #ffc384;
            color: white;
        }

        .paid {

            color: #93f093;
            border-color: #93f093;
        }

        .paid:hover {
            background-color: #93f093;
            color: white;
        }

        .draft {

            color: grey;
            border-color: grey;

        }

        .draft:hover {
            background-color: grey;
            color: white;
        }

        a {
            text-decoration: none;
        }

        tr {
            line-height: 35px;
        }

        th,
        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Invoice Manager</h1>
        <div class="d-flex flex-row justify-content-between">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="logout.php">Logout</a>
        </div>
        <?php require 'result.php' ?>
    </div>
</body>

</html>