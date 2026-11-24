<?php
include 'db.php';

// Add asset
if (isset($_POST['add_asset'])) {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    $allowedCategories = ['microscope', 'thermometer', 'syringe'];

    if ($name !== '' && in_array($category, $allowedCategories, true) && $quantity !== false && $quantity !== null && $quantity > 0) {
        $stmt = $conn->prepare("INSERT INTO jabee (name, category, quantity, status) VALUES (?, ?, ?, 'Active')");
        $stmt->bind_param("ssi", $name, $category, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}

// Delete asset
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM jabee WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}

// Archive asset
if (isset($_GET['archive'])) {
    $id = filter_input(INPUT_GET, 'archive', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $conn->prepare("UPDATE jabee SET status = 'Archived' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
?>
