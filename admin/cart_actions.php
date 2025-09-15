<?php
session_start();
header('Content-Type: application/json');

include '../database/db_connection.php';

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

function renderCartHTML() {
    ob_start();
    include '../includes/cart_items_partial.php';
    return ob_get_clean();
}

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
    exit;
}

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addOrUpdateCartItemInDB($conn, $cartId, $productId, $quantity, $price) {
    $stmtCheck = $conn->prepare("SELECT quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmtCheck->bind_param("ii", $cartId, $productId);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $newQuantity = $row['quantity'] + $quantity;
        $stmtUpdate = $conn->prepare("UPDATE cart_items SET quantity = ?, price = ? WHERE cart_id = ? AND product_id = ?");
        $stmtUpdate->bind_param("idii", $newQuantity, $price, $cartId, $productId);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        $stmtInsert = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtInsert->bind_param("iiid", $cartId, $productId, $quantity, $price);
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    $stmtCheck->close();
}

function updateCartItemInDB($conn, $cartId, $productId, $quantity, $price) {
    $stmtUpdate = $conn->prepare("UPDATE cart_items SET quantity = ?, price = ? WHERE cart_id = ? AND product_id = ?");
    $stmtUpdate->bind_param("idii", $quantity, $price, $cartId, $productId);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}

function deleteCartItemInDB($conn, $cartId, $productId) {
    $stmtDelete = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmtDelete->bind_param("ii", $cartId, $productId);
    $stmtDelete->execute();
    $stmtDelete->close();
}

function &getCartItemReference($productId) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            return $item;
        }
    }
    $null = null;
    return $null;
}

$cartId = $_SESSION['user_id'] ?? 1;

switch ($action) {
    case 'add':
        $id = $input['id'] ?? null;
        $quantity = max(1, intval($input['quantity'] ?? 1));

        if ($id === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
            exit;
        }

        $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $name = $row['name'];
            $price = round($row['price'] * 1.10, 2);
            $image = $row['image'] ?? 'assets/images/default-product.png';

            if (!str_starts_with($image, 'assets/images/') && !str_starts_with($image, 'http')) {
                $image = 'assets/images/' . ltrim($image, '/');
            }

            $refItem =& getCartItemReference($id);
            if ($refItem !== null) {
                $refItem['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => floatval($price),
                    'quantity' => $quantity,
                    'image' => $image
                ];
            }

            addOrUpdateCartItemInDB($conn, $cartId, $id, $quantity, $price);

            echo json_encode([
                'status' => 'success',
                'message' => 'Product added',
                'cart_html' => renderCartHTML()
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        }

        $stmt->close();
        break;

    case 'update':
        $index = $input['index'] ?? null;
        $quantity = max(1, intval($input['quantity'] ?? 1));

        if ($index === null || !isset($_SESSION['cart'][$index])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid index']);
            exit;
        }

        $_SESSION['cart'][$index]['quantity'] = $quantity;

        $productId = $_SESSION['cart'][$index]['id'];
        $price = $_SESSION['cart'][$index]['price'];
        updateCartItemInDB($conn, $cartId, $productId, $quantity, $price);

        echo json_encode([
            'status' => 'success',
            'message' => 'Quantity updated',
            'cart_html' => renderCartHTML()
        ]);
        break;

    case 'delete':
        $index = $input['index'] ?? null;

        if ($index === null || !isset($_SESSION['cart'][$index])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid index']);
            exit;
        }

        $productId = $_SESSION['cart'][$index]['id'];

        array_splice($_SESSION['cart'], $index, 1);

        deleteCartItemInDB($conn, $cartId, $productId);

        echo json_encode([
            'status' => 'success',
            'message' => 'Product removed',
            'cart_html' => renderCartHTML()
        ]);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
?>