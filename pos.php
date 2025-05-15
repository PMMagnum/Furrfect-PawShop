
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] === 'admin';

// Fetch existing products for the product display
$productStmt = $pdo->query("SELECT * FROM products ORDER BY name LIMIT 20");
$products = $productStmt->fetchAll();

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $barcode = $_POST['barcode'];

    // Insert new product into the database
    $stmt = $pdo->prepare("INSERT INTO products (name, price, barcode) VALUES (:name, :price, :barcode)");
    $stmt->execute([':name' => $name, ':price' => $price, ':barcode' => $barcode]);

    // Refresh the product list after insertion
    header("Location: pos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Furrfect Pawshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet" />

  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>

#grand-total {
    font-size: 32px;
    font-weight: bold;
    color: #2c3e50;
    text-align: right;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid #ddd;
}

    body {
        font-family: 'Fredoka', sans-serif;
        background: url('https://www.transparenttextures.com/patterns/paw-print.png') repeat, #fff8f0;
        background-size: 60px 60px;
    }

    header {
        background-color: #2c3e50; /* Dark Blue for header */
        color: white;
        padding: 20px 0;
    }

    .navbar {
        width: 80%;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h1 {
        font-size: 28px;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-info span {
        margin-right: 10px;
    }

    .logout-btn {
        background-color: #e74c3c; /* Red for logout button */
        color: white;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }

    .logout-btn:hover {
        background-color: #c0392b; /* Darker red for hover */
    }

    .pos-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        padding: 15px;
    }

    .header-section {
        background-color: #2c3e50; /* Dark Blue for header */
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section h1 {
        font-size: 24px;
    }

    .header-section a {
        color: white;
        text-decoration: none;
        margin-left: 15px;
        font-weight: bold;
    }

    .display-section {
        background-color: #000; /* Black background for the display */
        color: #00ff00; /* Lime Green for display text */
        padding: 15px;
        font-family: monospace;
        font-size: 24px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 10px;
    }

    .total-display {
        font-size: 28px;
        font-weight: bold;
        color: #00ff00; /* Lime Green */
        text-align: right;
    }

    .main-content {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin: 0 auto;
    }

    .left-panel {
        flex: 0 0 40%;
        display: flex;
        flex-direction: column;
        padding: 15px;
        background-color: #fffbea; /* Light Beige for left panel */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .numpad-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 5px;
        padding: 15px;
        background-color: #f7d9a4; /* Yellow Accent for numpad section */
    }

    .numpad-button {
        padding: 20px;
        font-size: 18px;
        text-align: center;
        background-color: white;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .numpad-button:hover {
        background-color: #dcdcdc; /* Light Gray for numpad button hover */
    }

    .product-button {
        padding: 20px 15px;
        text-align: center;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        height: 80px;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        background-color: #34495e; /* Dark Gray for product buttons */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background-color: #555; /* Slightly darker gray on hover */
    }

    .product-name {
        font-size: 12px;
        margin-bottom: 5px;
        color: #dcdcdc;
    }

    .product-price {
        font-size: 12px;
        color: #dcdcdc;
    }

    .right-panel {
        flex: 0 0 60%;
        display: flex;
        flex-direction: column;
        padding: 15px;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .cart-table th, .cart-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .footer-buttons {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        background-color: #fffbea; /* Light Beige for footer */
        border-top: 1px solid #ddd;
    }

    .action-button {
        padding: 12px 24px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 120px;
    }

    .pay-button {
        background-color: #e74c3c; /* Red for Pay button */
        color: white;
    }

    .clear-button {
        background-color: #f0f0f0; /* Light Gray for Clear button */
        color: #333;
    }

    .action-button:hover {
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .main-content {
            flex-direction: column;
            width: 90%;
        }

        .left-panel, .right-panel {
            width: 100%;
        }

        .numpad-section {
            grid-template-columns: repeat(3, 1fr);
        }

        #barcode-input {
            font-size: 16px;
        }
    }
  </style>
</head>
<body>
    <div class="pos-container">
        <div class="header-section">
            <h1>Furrfect Pawshop POS</h1>
            <div>
                Logged in as: <?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)
                <a href="logout.php">Logout</a>
                <?php if ($isAdmin): ?><a href="dashboard.php">Admin Dashboard</a><?php endif; ?>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <form id="barcode-form">
                    <input type="text" id="barcode-input" placeholder="Scan or type barcode..." class="form-control mb-3">
                </form>
                <div class="numpad-section">
                    <?php foreach ($products as $product): ?>
                        <button class="product-button" data-id="<?= $product['id'] ?>" data-name="<?= $product['name'] ?>" data-price="<?= $product['price'] ?>">
                            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                            <div class="product-price">₱<?= number_format($product['price'], 2) ?></div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="right-panel">
                <div class="cart-section">
                    <table class="cart-table" id="cart-table">
                        <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="total-display" id="grand-total">₱0.00</div>


                <!-- Admin Add Product Form -->
                <?php if ($isAdmin): ?>
                    <div class="add-product-form">
                        <h4>Add New Product</h4>
                        <form action="pos.php" method="POST">
                            <input type="text" name="name" placeholder="Product Name" class="form-control mb-3" required>
                            <input type="text" name="price" placeholder="Price" class="form-control mb-3" required>
                            <input type="text" name="barcode" placeholder="Barcode" class="form-control mb-3" required>
                            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-buttons">
            <button id="clear-cart" class="action-button clear-button">Clear</button>
            <button id="pay-button" class="action-button pay-button">Pay</button>
        </div>
    </div>

    <script>
        let cart = [];
        let total = 0;
        let cashHanded = 0;
        const cartTable = document.querySelector("#cart-table tbody");
        const totalDisplay = document.querySelector(".total-display");
        const clearButton = document.getElementById("clear-cart");
        const payButton = document.getElementById("pay-button");

        // Handle barcode input + AJAX fetch (MATIK DISPLAY SA KILID)
        document.getElementById("barcode-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const barcodeInput = document.getElementById("barcode-input");
    let barcode = barcodeInput.value.trim();

    // Clear immediately to prepare for next scan
    barcodeInput.value = "";

    if (!barcode) {
        barcodeInput.focus();
        return;
    }

    fetch('lookup_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(barcode)
    })
    .then(res => res.json())
    .then(product => {
        if (!product || !product.name) {
            alert("Product not found!");
            barcodeInput.focus();
            return;
        }

        const existingItem = cart.find(item => item.name === product.name);
        if (existingItem) {
            existingItem.qty++;
            existingItem.total = existingItem.qty * existingItem.price;
        } else {
            cart.push({
                name: product.name,
                price: parseFloat(product.price),
                qty: 1,
                total: parseFloat(product.price)
            });
        }

        total += parseFloat(product.price);
        refreshCartTable();
        updateTotal();

        // Optional: Add a slight delay to avoid scanner jitter
        setTimeout(() => {
            barcodeInput.focus();
        }, 50);
    })
    .catch(err => {
        console.error(err);
        alert("SUCCESS!");
        barcodeInput.focus();
    });
});



        // Update total display
        function updateTotal() {
    const grandTotalDisplay = document.getElementById("grand-total");
    if (totalDisplay) totalDisplay.textContent = `₱${total.toFixed(2)}`;
    if (grandTotalDisplay) grandTotalDisplay.textContent = `₱${total.toFixed(2)}`;
}


        // Refresh cart table
        function refreshCartTable() {
    cartTable.innerHTML = "";
    cart.forEach((item, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td>
                <button class="btn btn-sm btn-secondary qty-decrease" data-index="${index}">−</button>
                <span class="mx-2">${item.qty}</span>
                <button class="btn btn-sm btn-secondary qty-increase" data-index="${index}">+</button>
            </td>
            <td>₱${item.price.toFixed(2)}</td>
            <td>₱${item.total.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm remove-btn" data-index="${index}"><i class="fa fa-trash"></i></button></td>
        `;
        cartTable.appendChild(row);
    });

    // Add event listeners for +/− buttons
    document.querySelectorAll(".qty-increase").forEach(button => {
        button.addEventListener("click", () => {
            const index = parseInt(button.getAttribute("data-index"));
            cart[index].qty++;
            cart[index].total = cart[index].qty * cart[index].price;
            total += cart[index].price;
            refreshCartTable();
            updateTotal();
        });
    });

    document.querySelectorAll(".qty-decrease").forEach(button => {
        button.addEventListener("click", () => {
            const index = parseInt(button.getAttribute("data-index"));
            if (cart[index].qty > 1) {
                cart[index].qty--;
                cart[index].total = cart[index].qty * cart[index].price;
                total -= cart[index].price;
            } else {
                total -= cart[index].total;
                cart.splice(index, 1); // Remove item if quantity reaches 0
            }
            refreshCartTable();
            updateTotal();
        });
    });

    // Existing remove button logic
    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", () => {
            const index = parseInt(button.getAttribute("data-index"));
            total -= cart[index].total;
            cart.splice(index, 1);
            refreshCartTable();
            updateTotal();
        });
    });
}




        // Handle adding products to cart
        const productButtons = document.querySelectorAll(".product-button");
        productButtons.forEach(button => {
            button.addEventListener("click", () => {
                const name = button.getAttribute("data-name");
                const price = parseFloat(button.getAttribute("data-price"));
                const existingItem = cart.find(item => item.name === name);
                if (existingItem) {
                    existingItem.qty++;
                    existingItem.total = existingItem.qty * existingItem.price;
                } else {
                    cart.push({ name, price, qty: 1, total: price });
                }
                total += price;
                refreshCartTable();
                updateTotal();
            });
        });

        // Handle clearing the cart
        clearButton.addEventListener("click", () => {
            cart = [];
            total = 0;
            refreshCartTable();
            updateTotal();
        });

        // Handle payment
        payButton.addEventListener("click", () => {
            cashHanded = parseFloat(prompt("Enter cash handed:"));
            if (isNaN(cashHanded)) {
                alert("Invalid cash amount");
                return;
            }

            const change = (cashHanded - total).toFixed(2);
            if (change < 0) {
                alert("Insufficient cash handed.");
                return;
            }

            // Show receipt in a new window
            const receipt = `
                <div style="font-family: Arial, sans-serif; text-align: center;">
                    <h1>Furrfect Pawshop</h1>
                    <p><strong>Receipt</strong></p>
                    <p>Date: ${new Date().toLocaleString()}</p>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 20px; text-align: left;">
                        <thead>
                            <tr>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Item</th>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Qty</th>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Price</th>
                                <th style="border-bottom: 1px solid #ddd; padding: 8px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${cart.map(item => `
                                <tr>
                                    <td style="padding: 8px;">${item.name}</td>
                                    <td style="padding: 8px;">${item.qty}</td>
                                    <td style="padding: 8px;">₱${item.price.toFixed(2)}</td>
                                    <td style="padding: 8px;">₱${item.total.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                   <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; display: flex; flex-direction: column; align-items: flex-end;">
    <div style="display: flex; justify-content: space-between; width: 100%; max-width: 300px;">
        <p><strong>Total: </strong></p>
        <p>₱${total.toFixed(2)}</p>
    </div>
    <div style="display: flex; justify-content: space-between; width: 100%; max-width: 300px;">
        <p><strong>Cash Handed: </strong></p>
        <p>₱${cashHanded.toFixed(2)}</p>
    </div>
    <div style="display: flex; justify-content: space-between; width: 100%; max-width: 300px;">
        <p><strong>Change: </strong></p>
        <p>₱${change}</p>
    </div>
</div>



                </div>`;
            const newWindow = window.open();
            newWindow.document.write(receipt);
            newWindow.print();
        });
    </script>
    
</body>
</html>
