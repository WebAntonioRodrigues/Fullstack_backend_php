<?php
require_once "../Sales_backend/db.php";
require_once "../Sales_backend/crud/materials_crud.php";

$brand = $_GET["brand"] ?? "";
$category = $_GET["category"] ?? "";
$minPrice = $_GET["min_price"] ?? "";
$maxPrice = $_GET["max_price"] ?? "";

$materials = getFilteredMaterials($pdo, $brand, $category, $minPrice, $maxPrice);
$brands = getBrands($pdo);
$categories = getCategories($pdo);

$pageTitle = "Catalog";
$activePage = "materials";
require_once "header.php";
?>

<div class="card">
    <form method="get" class="filters">
        <div class="form-group">
            <label>Brand</label>
            <select name="brand">
                <option value="">All</option>
                <?php foreach ($brands as $b) { ?>
                    <option value="<?php echo $b["brand"]; ?>" <?php if ($brand === $b["brand"]) { echo "selected"; } ?>>
                        <?php echo $b["brand"]; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Part type</label>
            <select name="category">
                <option value="">All</option>
                <?php foreach ($categories as $c) { ?>
                    <option value="<?php echo $c["category"]; ?>" <?php if ($category === $c["category"]) { echo "selected"; } ?>>
                        <?php echo $c["category"]; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Min price</label>
            <input type="number" step="0.01" name="min_price" value="<?php echo $minPrice; ?>">
        </div>

        <div class="form-group">
            <label>Max price</label>
            <input type="number" step="0.01" name="max_price" value="<?php echo $maxPrice; ?>">
        </div>

        <button type="submit" class="btn">Filter</button>
        <a href="materials.php" class="btn btn-secondary">Clear</a>
    </form>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Ref</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $material) { ?>
                <tr>
                    <td><?php echo $material["material_ref"]; ?></td>
                    <td><?php echo $material["name"]; ?></td>
                    <td><?php echo $material["brand"]; ?></td>
                    <td><?php echo $material["category"]; ?></td>
                    <td><?php echo $material["sale_price"]; ?> €</td>
                    <td><?php echo $material["current_stock"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require_once "footer.php"; ?>