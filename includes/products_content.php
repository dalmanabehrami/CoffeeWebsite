<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'database/db_connection.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<section class="products" id="products">
    <div class="products-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Merr çdo fushë në mënyrë të sigurt
                $id = array_key_exists('id', $row) ? $row['id'] : null;
                $name = array_key_exists('name', $row) ? htmlspecialchars($row['name']) : 'No name';
                $price = array_key_exists('price', $row) ? number_format($row['price'], 2) : '0.00';
                $image = array_key_exists('image', $row) ? htmlspecialchars($row['image']) : 'default.jpg';

                if ($id === null) {
                    // Pa id nuk ka kuptim të shfaqet produkti, kalojmë
                    continue;
                }
                $id = $row['id'];
                $name = htmlspecialchars($row['name']);
                $price = number_format($row['price'], 2);
                $image = htmlspecialchars($row['image']);
                if (!str_starts_with($image, 'assets/images/') && !str_starts_with($image, 'http')) {
                    $image = 'assets/images/' . ltrim($image, '/');
                }

                echo '<div class="box" 
                        data-id="' . $id . '" 
                        data-name="' . $name . '" 
                        data-price="' . $price . '" 
                        data-image="' . $image . '">';

                echo '<img src="' . $image . '" alt="Kjo eshte ' . $name . '">';
                echo '<h3>' . $name . '</h3>';
                echo '<div class="content">';
                echo '<span>' . $price . '&euro;</span><br>';
                echo '<button class="btn" onclick="addToCart(this)">Add</button> ';
                echo '<button class="btn" onclick="deleteFromCartItems(' . $id . ')">Remove from cart</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p style='color:#fff;'>No products available.</p>";
        }
        ?>
    </div>
</section>
