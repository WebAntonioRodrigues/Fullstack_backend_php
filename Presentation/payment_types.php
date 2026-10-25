<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/payment_types_crud.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $message = "Name is required";
    } elseif (getPaymentTypeByName($pdo, $name)) {
        $message = "That payment type already exists";
    } else {
        insertPaymentType($pdo, $name);
        $message = "Payment type added";
    }
}

$paymentTypes = getAllPaymentTypes($pdo);

$pageTitle = "Payment types";
$activePage = "payment_types";
require_once "header.php";
?>

<p class="message"><?php echo $message; ?></p>

<div class="card form-card">
    <form method="post">
        <div class="form-group">
            <label>New payment type</label>
            <input type="text" name="name">
        </div>
        <button type="submit" class="btn">Add</button>
    </form>
</div>

<div class="card form-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Payment types</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paymentTypes as $type) { ?>
                <tr>
                    <td><?php echo $type["name"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>