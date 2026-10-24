<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/clients_crud.php";
require_once "../Sales_backend/crud/materials_crud.php";
require_once "../Sales_backend/crud/sales_crud.php";

$totalParts = count(getAllMaterials($pdo));
$totalClients = count(getAllClients($pdo));
$totalSales = count(getAllSales($pdo));
$listSales = getAllSales($pdo);
$listClients = getAllClients($pdo);
$listMaterials = getAllMaterials($pdo);

$pageTitle = "Dashboard";
$activePage = "home";

require_once "header.php";
?>

<p id="message" class="message"></p>


<div class="cards-row">
    <div class="card">
        <div class="stat-number"><?php echo $totalParts; ?></div>
        <div class="stat-label">Parts in catalog</div>
    </div>
    <div class="card">
        <div class="stat-number"><?php echo $totalClients; ?></div>
        <div class="stat-label">Clients</div>
    </div>
    <div class="card">
        <div class="stat-number"><?php echo $totalSales; ?></div>
        <div class="stat-label">Sales</div>
    </div>
    <div class="card">
        <div class="stat-number" id="total-suppliers">...</div>
        <div class="stat-label">Suppliers</div>
    </div>
    <div class="card">
        <div class="stat-number" id="total-orders">...</div>
        <div class="stat-label">Orders</div>
    </div>
</div>

<br>


<div class="cards-row">
   
    <div class="card">
        <h3>Materials</h3>
        <?php if (!empty($listMaterials)): ?>
            <?php foreach ($listMaterials as $material): ?>
                <div class="card">
                    <p>============</p>
                    <div class="stat-label"><?php echo htmlspecialchars($material['material_ref'] ?? ''); ?></div>
                    <p>============</p>
                    <div class="stat-label"><?php echo htmlspecialchars($material['name'] ?? ''); ?></div>
                    <p>------------</p>
                    <div class="stat-label"><span>brand:</span> <?php echo htmlspecialchars($material['brand'] ?? ''); ?></div>
                    <div class="stat-label"><span>stock:</span> <?php echo htmlspecialchars($material['current_stock'] ?? ''); ?></div>
                    <div class="stat-label"><span>price:</span> <?php echo htmlspecialchars($material['sale_price'] ?? ''); ?></div>
                    <div class="stat-label"><span>cost:</span> <?php echo htmlspecialchars($material['cost_price'] ?? ''); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
   
    
    <div class="card">
        <h3>Clients</h3>
        <?php if (!empty($listClients)): ?>
            <?php foreach ($listClients as $client): ?>
                <div class="card">
                    <p>============</p>
                    <div class="stat-label"><?php echo htmlspecialchars($client['id'] ?? ''); ?><span>.</span></div>
                    <p>============</p>
                    <div class="stat-label"><?php echo htmlspecialchars($client['name'] ?? ''); ?></div>
                    <p>------------</p>
                    <div class="stat-label"><span>NIF:</span> <?php echo htmlspecialchars($client['tax_id'] ?? ''); ?></div>
                    <div class="stat-label"><span>email:</span> <?php echo htmlspecialchars($client['email'] ?? ''); ?></div>
                    <div class="stat-label"><span>phone:</span> <?php echo htmlspecialchars($client['phone'] ?? ''); ?></div>
                    <div class="stat-label"><span>address:</span> <?php echo htmlspecialchars($client['address'] ?? ''); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
   
    
    <div class="card">
        <h3>Sales</h3>
        <?php if (!empty($listSales)): ?>
            <?php foreach ($listSales as $sale): ?>
                <div class="card">
                    <p>============</p>
                    <div class="stat-label"><?php echo htmlspecialchars($sale['id'] ?? ''); ?><span>.</span></div>
                    <p>============</p>
                    <div class="stat-label"><span>Client ID:</span> <?php echo htmlspecialchars($sale['client_id'] ?? ''); ?></div>
                    <div class="stat-label"><span>payment:</span> <?php echo htmlspecialchars($sale['payment_type'] ?? ''); ?></div>
                    <div class="stat-label"><span>date:</span> <?php echo htmlspecialchars($sale['sale_date'] ?? ''); ?></div>
                    <div class="stat-label"><span>Total:</span> <?php echo htmlspecialchars($sale['total'] ?? ''); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    
    <div class="card">
        <h3>Suppliers</h3>
        <div id="suppliers-container"></div>
    </div>

    
    <div class="card">
        <h3>Orders </h3>
        <div id="orders-container"></div>
    </div>
</div>

<script>
    const nodeUrl = "http://localhost:3001/api";

    async function loadNodeData() {
        try {
           
            const loginResponse = await fetch(nodeUrl + "/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: "test@admin.com", password: "admin1234" })
            });
            const login = await loginResponse.json();

            if (!loginResponse.ok) {
                document.getElementById("message").textContent = "Node Login failed: " + login.error;
                return;
            }

            const token = login.token;

            
            const suppliersResponse = await fetch(nodeUrl + "/suppliers", {
                headers: { "Authorization": "Bearer " + token }
            });
            const suppliers = await suppliersResponse.json();

            const supContainer = document.getElementById("suppliers-container");
            if (suppliersResponse.ok && Array.isArray(suppliers)) {
                
                document.getElementById("total-suppliers").textContent = suppliers.length;

                supContainer.innerHTML = "";
                suppliers.forEach(sup => {
                    supContainer.innerHTML += `
                        <div class="card">
                            <p>============</p>
                            <div class="stat-label">${sup.name}</div>
                            <p>------------</p>
                            <div class="stat-label"><span>NIF:</span> ${sup.tax_id || ''}</div>
                            <div class="stat-label"><span>contact:</span> ${sup.contact || ''}</div>
                            <div class="stat-label"><span>address:</span> ${sup.address || ''}</div>
                        </div>`;
                });
            } else {
                supContainer.innerHTML = "Could not load suppliers.";
                document.getElementById("total-suppliers").textContent = "0";
            }

            
            const ordersResponse = await fetch(nodeUrl + "/orders", {
                headers: { "Authorization": "Bearer " + token }
            });
            const orders = await ordersResponse.json();

            const ordContainer = document.getElementById("orders-container");
            if (ordersResponse.ok && Array.isArray(orders)) {
                
                document.getElementById("total-orders").textContent = orders.length;

                ordContainer.innerHTML = "";
                orders.forEach(order => {
                    ordContainer.innerHTML += `
                        <div class="card">
                            <p>============</p>
                            <div class="stat-label">Order #${order.id}</div>
                            <p>------------</p>
                            <div class="stat-label"><span>date:</span> ${order.order_date || ''}</div>
                            <div class="stat-label"><span>Status:</span> ${order.status || ''}</div>
                        </div>`;
                });
            } else {
                ordContainer.innerHTML = "Could not load orders.";
                document.getElementById("total-orders").textContent = "0";
            }

        } catch (error) {
            console.error("Error fetching Node data:", error);
        }
    }

    loadNodeData();
</script>

<?php require_once "footer.php"; ?>