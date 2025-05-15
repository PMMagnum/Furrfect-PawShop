let cart = [];
let total = 0;

document.getElementById('barcode-form').addEventListener('submit', function (e) {
  e.preventDefault();
  let barcode = document.getElementById('barcode-input').value;
  fetchProduct(barcode);
});

function fetchProduct(barcode) {
  fetch(`fetch_product.php?barcode=${barcode}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        addToCart(data.product);
      } else {
        alert(data.message);
      }
    });
}

function addToCart(product) {
  let existingProduct = cart.find(item => item.id === product.id);
  if (existingProduct) {
    existingProduct.quantity++;
  } else {
    cart.push({ ...product, quantity: 1 });
  }
  updateCart();
}

function updateCart() {
  let cartTableBody = document.getElementById('cart-table').querySelector('tbody');
  cartTableBody.innerHTML = '';
  total = 0;
  cart.forEach(item => {
    total += item.price * item.quantity;
    cartTableBody.innerHTML += `
      <tr>
        <td>${item.name}</td>
        <td>${item.quantity}</td>
        <td>₱${item.price}</td>
        <td><button onclick="removeFromCart(${item.id})">Remove</button></td>
      </tr>
    `;
  });
  document.getElementById('total').textContent = total.toFixed(2);
}

function removeFromCart(productId) {
  cart = cart.filter(item => item.id !== productId);
  updateCart();
}

function printReceipt() {
  let receiptContent = 'Receipt:\n';
  cart.forEach(item => {
    receiptContent += `${item.name} x${item.quantity} - ₱${item.price * item.quantity}\n`;
  });
  receiptContent += 'Total: ₱' + total.toFixed(2);
  alert(receiptContent);
}
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = `delete_product.php?id=${productId}`;
    }
}