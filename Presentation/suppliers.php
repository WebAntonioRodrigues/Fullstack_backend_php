<?php
$pageTitle = "Suppliers";
$activePage = "suppliers";
require_once "header.php";
?>

<p id="message" class="message"></p>

<div class="card form-card">
    <h3>New Supplier</h3>
    <form id="supplier-form">
        <div class="form-group">
            <label>Name</label>
            <input type="text" id="name" required>
        </div>

        <div class="form-group">
            <label>Tax ID</label>
            <input type="text" id="tax_id" required>
        </div>

        <div class="form-group">
            <label>Contact</label>
            <input type="text" id="contact">
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" id="address">
        </div>

        <button type="submit" class="btn">Save Supplier</button>
    </form>
</div>

<br>

<div class="card">
    <h3>Existing Suppliers</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Tax ID</th>
                <th>Contact</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody id="suppliers-body"></tbody>
    </table>
</div>

<script>
    const nodeUrl = "http://localhost:3001/api";

    async function getToken() {
        const loginResponse = await fetch(nodeUrl + "/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: "test@admin.com", password: "admin1234" })
        });
        const login = await loginResponse.json();

        if (!loginResponse.ok) {
            throw new Error("Login failed: " + login.error);
        }
        return login.token;
    }

    async function loadSuppliers() {
        try {
            const token = await getToken();
            const suppliersResponse = await fetch(nodeUrl + "/suppliers", {
                headers: { "Authorization": "Bearer " + token }
            });
            const suppliers = await suppliersResponse.json();

            if (!suppliersResponse.ok) {
                document.getElementById("message").textContent = "Could not load suppliers: " + suppliers.error;
                return;
            }

            const tbody = document.getElementById("suppliers-body");
            tbody.innerHTML = ""; 
            suppliers.forEach(supplier => {
                tbody.innerHTML += "<tr><td>" + supplier.name + "</td><td>" + supplier.tax_id + "</td><td>" + supplier.contact + "</td><td>" + supplier.address + "</td></tr>";
            });
        } catch (error) {
            document.getElementById("message").textContent = error.message;
        }
    }

    document.getElementById("supplier-form").addEventListener("submit", async function (event) {
        event.preventDefault();

        try {
            const token = await getToken();
            const response = await fetch(nodeUrl + "/suppliers", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token 
                },
                body: JSON.stringify({
                    name: document.getElementById("name").value,
                    tax_id: document.getElementById("tax_id").value,
                    contact: document.getElementById("contact").value,
                    address: document.getElementById("address").value
                })
            });
            const result = await response.json();

            if (!response.ok) {
                document.getElementById("message").textContent = "Could not save supplier: " + (result.error || result.message);
                return;
            }

            document.getElementById("message").textContent = "Supplier added successfully!";
            document.getElementById("supplier-form").reset(); 
            loadSuppliers();
        } catch (error) {
            document.getElementById("message").textContent = error.message;
        }
    });

    loadSuppliers();
</script>

<?php require_once "footer.php"; ?>