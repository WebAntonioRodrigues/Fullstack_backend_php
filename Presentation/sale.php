<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/clients_crud.php";
require_once "../Sales_backend/crud/materials_crud.php";
require_once "../Sales_backend/crud/sales_crud.php";
require_once "../Sales_backend/crud/payment_types_crud.php";


$paymentTypes = getAllPaymentTypes($pdo);

$clients = getAllClients($pdo);
$materials = getAllMaterials($pdo);
$sales = getAllSales($pdo);

$pageTitle = "New sale";
$activePage = "sale";
require_once "header.php";
?>

<p id="message" class="message"></p>

<div class="card form-card">
    <form id="sale-form">
        <div class="form-group">
            <label>Client</label>
            <select id="client">
                <option value="">Choose a client</option>
                <?php foreach ($clients as $client) { ?>
                    <option value="<?php echo $client["id"]; ?>"><?php echo $client["name"]; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Part</label>
            <select id="material">
                <option value="">Choose a part</option>
                <?php foreach ($materials as $material) { ?>
                    <option value="<?php echo $material["id"]; ?>"
                        data-ref="<?php echo $material["material_ref"]; ?>"
                        data-brand="<?php echo $material["brand"]; ?>"
                        data-category="<?php echo $material["category"]; ?>"
                        data-price="<?php echo $material["sale_price"]; ?>"
                        data-stock="<?php echo $material["current_stock"]; ?>">
                        <?php echo $material["name"]; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <p id="part-info" class="part-info"></p>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" id="quantity" min="1" value="1">
        </div>

        <div class="form-group">
            <label>Payment type</label>
            <select id="payment">
                <?php foreach ($paymentTypes as $type) { ?>
                <option value="<?php echo $type["name"]; ?>"><?php echo $type["name"]; ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn">Place order</button>
    </form>
</div>

<br>

<!-- Tabela de Listagem de Vendas Existentes -->
<div class="card">
    <h3>Existing Sales</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Client ID</th>
                <th>Payment Type</th>
                <th>Date</th>
                <th>Total (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales)) { ?>
                <?php foreach ($sales as $sale) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sale["id"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sale["client_id"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sale["payment_type"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sale["sale_date"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sale["total"] ?? ''); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5">No sales found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    const salesApiUrl = "http://localhost/Fullstack_backend_php/Sales_backend/api/sales_api.php";

    document.getElementById("material").addEventListener("change", function () {
        const option = this.options[this.selectedIndex];
        const info = document.getElementById("part-info");

        if (this.value === "") {
            info.textContent = "";
            return;
        }

        info.textContent = "Ref: " + option.dataset.ref + " | Brand: " + option.dataset.brand + " | Type: " + option.dataset.category + " | Price: " + option.dataset.price + " € | Stock: " + option.dataset.stock;
    });

    document.getElementById("sale-form").addEventListener("submit", async function (event) {
        event.preventDefault();

        const response = await fetch(salesApiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                client_id: document.getElementById("client").value,
                payment_type: document.getElementById("payment").value,
                items: [
                    {
                        material_id: document.getElementById("material").value,
                        quantity: parseInt(document.getElementById("quantity").value)
                    }
                ]
            })
        });
        const result = await response.json();

        document.getElementById("message").textContent = result.mensagem || "Sale registered successfully!";

        if (response.ok) {
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    });
</script>

<?php require_once "footer.php"; ?>