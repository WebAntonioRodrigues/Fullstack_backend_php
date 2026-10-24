<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/clients_crud.php";

$clients = getAllClients($pdo);

$pageTitle = "Clients Management";
$activePage = "clients";
require_once "header.php";
?>

<p id="message" class="message"></p>


<div class="card form-card">
    <h3>Add New Client</h3>
    <form id="client-form">
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

        <button type="submit" class="btn">Save Client</button>
    </form>
</div>

<br>


<div class="card">
    <h3>Existing Clients</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Tax ID</th>
                <th>Contact</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)) { ?>
                <?php foreach ($clients as $client) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client["name"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($client["tax_id"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($client["email"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($client["address"] ?? ''); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">No clients found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    
    const clientsApiUrl = "http://localhost/Fullstack_backend_php/Sales_backend/api/clients_api.php";

    document.getElementById("client-form").addEventListener("submit", async function (event) {
        event.preventDefault();

        const response = await fetch(clientsApiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: document.getElementById("name").value,
                tax_id: document.getElementById("tax_id").value,
                contact: document.getElementById("contact").value,
                address: document.getElementById("address").value,
            })
        });
        const result = await response.json();

       document.getElementById("message").textContent = result.mensagem;
        
      
    });
</script>

<?php require_once "footer.php"; ?>