<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/materials_crud.php";

$materials = getAllMaterials($pdo);

$pageTitle = "New supplier order";
$activePage = "order";
require_once "header.php";
?>

<p id="message" class="message"></p>

<div class="card form-card">
    <form id="order-form">
        <div class="form-group">
            <label>Supplier</label>
            <select id="supplier" required>
                <option value="">Choose a supplier</option>
            </select>
        </div>

        <div class="form-group">
            <label>Part</label>
            <select id="material" required>
                <option value="">Choose a part</option>
                <?php foreach ($materials as $material) { ?>
                    <option value="<?php echo $material["material_ref"]; ?>"
                        data-cost="<?php echo $material["cost_price"]; ?>">
                        <?php echo $material["material_ref"] . " - " . $material["name"]; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" id="quantity" min="1" value="1" required>
        </div>

        <div class="form-group">
            <label>Unit price (€)</label>
            <input type="number" id="unit-price" step="0.01" readonly>
        </div>

        <button type="submit" class="btn">Submit order</button>
    </form>
</div>

<br>


<div class="card">
    <h3>Existing Orders</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Supplier ID</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="orders-body"></tbody>
    </table>
</div>

<script>
    const nodeUrl = "http://localhost:3001/api";
    let token = "";

    
    async function loadOrders() {
        try {
            const response = await fetch(nodeUrl + "/orders", {
                headers: { "Authorization": "Bearer " + token }
            });
            const orders = await response.json();

            if (response.ok && Array.isArray(orders)) {
                const tbody = document.getElementById("orders-body");
                tbody.innerHTML = "";
                orders.forEach(order => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${order.id || ''}</td>
                            <td>${order.supplier_id || ''}</td>
                            <td>${order.order_date || ''}</td>
                            <td>${order.status || ''}</td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            console.error("Could not load orders:", error);
        }
    }

    async function start() {
        const loginResponse = await fetch(nodeUrl + "/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: "test@admin.com", password: "admin1234" })
        });
        const login = await loginResponse.json();

        if (!loginResponse.ok) {
            document.getElementById("message").textContent = "Login failed: " + login.error;
            return;
        }

        token = login.token;

        const suppliersResponse = await fetch(nodeUrl + "/suppliers", {
            headers: { "Authorization": "Bearer " + token }
        });
        const suppliers = await suppliersResponse.json();

        const select = document.getElementById("supplier");
        suppliers.forEach(supplier => {
            select.innerHTML += "<option value='" + supplier.id + "'>" + supplier.name + "</option>";
        });

        loadOrders();
    }

    document.getElementById("material").addEventListener("change", function () {
        const option = this.options[this.selectedIndex];
        document.getElementById("unit-price").value = option.dataset.cost || "";
    });

    document.getElementById("order-form").addEventListener("submit", async function (event) {
        event.preventDefault();

        const response = await fetch(nodeUrl + "/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            },
            body: JSON.stringify({
                supplier_id: document.getElementById("supplier").value,
                items: [
                    {
                        material_ref: document.getElementById("material").value,
                        quantity: parseInt(document.getElementById("quantity").value),
                        unit_price: parseFloat(document.getElementById("unit-price").value)
                    }
                ]
            })
        });
        const result = await response.json();

        if (response.ok) {
            document.getElementById("message").textContent = "Order " + result.id + " created with status " + result.status;
           
            loadOrders();
            document.getElementById("order-form").reset();
            document.getElementById("unit-price").value = "";
        } else {
            document.getElementById("message").textContent = "Error: " + result.error;
        }
    });

    start();
</script>

<?php require_once "footer.php"; ?>