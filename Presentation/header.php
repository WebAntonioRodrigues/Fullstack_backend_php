<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?> - Car Parts Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Stencil:opsz,wght@10..72,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Wallpoet&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">


</head>
<body>
    <div class="sidebar">
        <div class="logo">AutoLux internal</div>
       

        <a href="index.php" class="<?php if ($activePage === "home") { echo "active"; } ?>">Dashboard</a>

        <div class="menu-title">Sales</div>
        
        <a href="clients.php" class="<?php if ($activePage === "clients") { echo "active"; } ?>">Clients</a>
        <a href="sale.php" class="<?php if ($activePage === "sale") { echo "active"; } ?>">New sale</a>
        <a href="materials.php" class="<?php if ($activePage === "materials") { echo "active"; } ?>">Catalog</a>

        <div class="menu-title">Procurement</div>

        <a href="suppliers.php" class="<?php if ($activePage === "suppliers") { echo "active"; } ?>">Suppliers</a>
        <a href="order.php" class="<?php if ($activePage === "order") { echo "active"; } ?>">New supplier order</a>
    </div>

    <div class="main">
        <div class="topbar">
            <h1><?php echo $pageTitle; ?></h1>
        </div>
        <div class="content">
        