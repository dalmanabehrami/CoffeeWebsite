<?php
include 'database/db_connection.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<section class="products" id="products">
    <div class="products-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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