<?php
include 'db.php';

$filter = $_GET['filter'] ?? 'all';
$allowedFilters = ['all', 'microscope', 'thermometer', 'syringe'];

if (!in_array($filter, $allowedFilters, true)) {
    $filter = 'all';
}

if ($filter === 'all') {
    $sql = "SELECT * FROM jabee ORDER BY id DESC";
    $result = $conn->query($sql);
} else {
    $stmt = $conn->prepare("SELECT * FROM jabee WHERE category = ? ORDER BY id DESC");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-container">
        <header class="page-header">
            <div>
                <p class="eyebrow">JABEE LABORATORY</p>
                <h1>Laboratory Tracker</h1>
                <p class="subtitle">Manage and monitor your laboratory assets.</p>
            </div>
            <button type="button" class="primary-btn" id="addAssetModal">+ Add Asset</button>
        </header>

        <main>
            <section class="toolbar">
                <div>
                    <h2>Asset Inventory</h2>
                    <p class="section-description">View and manage your recorded equipment.</p>
                </div>
                <form method="GET" action="index.php" class="filter-form">
                    <label for="filter">Filter by category</label>
                    <select name="filter" id="filter" onchange="this.form.submit()">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Categories</option>
                        <option value="microscope" <?= $filter === 'microscope' ? 'selected' : '' ?>>Microscope</option>
                        <option value="thermometer" <?= $filter === 'thermometer' ? 'selected' : '' ?>>Thermometer</option>
                        <option value="syringe" <?= $filter === 'syringe' ? 'selected' : '' ?>>Syringe</option>
                    </select>
                </form>
            </section>

            <div class="table-card">
                <div class="table-wrapper">
                    <table id="assetTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td class="asset-name"><?= htmlspecialchars($row['name']) ?></td>
                                    <td><span class="category-tag"><?= htmlspecialchars(ucfirst($row['category'])) ?></span></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td>
                                        <span class="status-tag <?= strtolower($row['status']) === 'active' ? 'active' : 'archived' ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a class="action-link archive-link" href="action.php?archive=<?= urlencode($row['id']) ?>" onclick="return confirm('Archive this asset?')">Archive</a>
                                            <a class="action-link delete-link" href="action.php?delete=<?= urlencode($row['id']) ?>" onclick="return confirm('Delete this asset permanently?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <strong>No records found</strong>
                                    <span>Add an asset or choose another category.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <footer>Jabee Laboratory Tracker</footer>
    </div>

    <div class="modal-overlay" id="modalOverlay" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-header">
                <div>
                    <p class="eyebrow">NEW INVENTORY ITEM</p>
                    <h2 id="modalTitle">Add Asset</h2>
                </div>
                <button type="button" class="close-btn" id="closeModal" aria-label="Close">&times;</button>
            </div>

            <form id="assetForm" action="action.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="name">Asset name</label>
                    <input type="text" name="name" id="name" placeholder="Enter asset name">
                    <p class="error-message" id="nameError"></p>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">-- Select category --</option>
                        <option value="microscope">Microscope</option>
                        <option value="thermometer">Thermometer</option>
                        <option value="syringe">Syringe</option>
                    </select>
                    <p class="error-message" id="categoryError"></p>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" placeholder="Enter quantity">
                    <p class="error-message" id="quantityError"></p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancelModal">Cancel</button>
                    <button type="submit" class="primary-btn" name="add_asset">Save Asset</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
