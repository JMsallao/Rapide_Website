<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">





                    <h2>Your Cart (<?php echo count($cart_items); ?> items)</h2>

                    <?php if (count($cart_items) > 0): ?>
                    <!-- Form for selecting items to delete or book -->
                    <form id="cart-form" action="bukingPerodiKulong.php" method="POST">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                <?php
                // Calculate total price for this item
                $unit_price = $item['price'];
                $quantity = $item['quantity'];
                $total_price_per_item = $unit_price * $quantity;
                $grandTotal += $total_price_per_item;
                ?>
                                <tr id="cart-item-<?php echo $item['id']; ?>">
                                    <td>
                                        <!-- Checkbox for selecting the item -->
                                        <input type="checkbox" name="cart_ids[]" value="<?php echo $item['id']; ?>">
                                    </td>
                                    <td><?php echo $item['service_name']; ?></td>
                                    <!-- Display the service name -->
                                    <td>₱<?php echo number_format($unit_price, 2); ?></td>
                                    <!-- Unit price -->
                                    <td>
                                        <!-- Quantity Selector -->
                                        <div class="quantity-selector">
                                            <button type="button" class="minus-btn"
                                                onclick="updateQuantity(-1, <?php echo $item['id']; ?>)">-</button>
                                            <input type="number" id="quantity_<?php echo $item['id']; ?>"
                                                value="<?php echo $quantity; ?>" min="1" readonly>
                                            <button type="button" class="plus-btn"
                                                onclick="updateQuantity(1, <?php echo $item['id']; ?>)">+</button>
                                        </div>
                                    </td>
                                    <td>₱<?php echo number_format($total_price_per_item, 2); ?></td>
                                    <!-- Total price for this item -->
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Booking and Delete Buttons -->
                        <div class="cart-summary">
                            <div><strong>Grand Total: ₱<?php echo number_format($grandTotal, 2); ?></strong>
                            </div>
                            <!-- Grand total -->
                            <button type="submit" class="action-btn">Proceed to Booking</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteSelectedItems()">Delete
                                Selected</button>
                        </div>
                    </form>

                    <?php else: ?>
                    <p>Your cart is empty.</p>
                    <?php endif; ?>
                </div>

                <script>
                // Update quantity via AJAX
                function updateQuantity(value, cartId) {
                    var quantityInput = document.getElementById('quantity_' + cartId);
                    var currentQuantity = parseInt(quantityInput.value);
                    var newQuantity = currentQuantity + value;

                    // Ensure the quantity is at least 1
                    if (newQuantity < 1) newQuantity = 1;

                    // Update the quantity input value
                    quantityInput.value = newQuantity;

                    // Send the new quantity to the server
                    fetch('../function/edit_ulit.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'cart_id=' + cartId + '&quantity=' + newQuantity
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'success') {
                                alert('Quantity updated successfully.');
                                window.location.reload(); // Reload the page to update the totals
                            } else {
                                alert('Error updating quantity.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Delete selected items
                function deleteSelectedItems() {
                    const form = document.getElementById('cart-form');
                    const formData = new FormData(form);

                    // Check if any item is selected
                    if (!formData.has('cart_ids[]')) {
                        alert('Please select at least one item to delete.');
                        return;
                    }

                    // Confirm before deleting
                    if (confirm('Are you sure you want to delete the selected items?')) {
                        fetch('../function/gigilMoko.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === 'success') {
                                    alert('Items successfully deleted!');
                                    window.location.reload(); // Reload the page to update the cart
                                } else {
                                    alert('Error deleting items.');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }
                </script>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>





























/* Global Styling */
body {
background-color: #f8f9fa;
font-family: Arial, sans-serif;
color: #333;
}


.button-container {
display: flex;
justify-content: flex-end;
gap: 10px;
margin-bottom: 15px;
}

/* Button Styles */
.btn-primary {
background-color: #e63946;
border: none;
}

.btn-secondary {
background-color: #f4a261;
border: none;
}

.descrip {
/* background-color: #333; */
/* height: 250px; */
}

/* Navbar */
.navbar {
background-color: #e63946;
padding: 10px 20px;
position: sticky;
top: 0;
z-index: 1000;
}

.navbar a {
color: #fff;
font-weight: bold;
text-decoration: none;
}

/* Category Navigation */
.category-nav {
overflow-x: scroll;
display: flex;
justify-content: center;

gap: 10px;
margin: 15px 0;
}

.category-nav a:hover {
background-color: red;
color: white;
}

.category-nav a {
padding: 8px 15px;
color: #e63946;
background-color: #ffe066;
border-radius: 20px;
font-weight: 500;
text-decoration: none;
}

.category-nav .active {
background-color: #e63946;
color: #fff;
}

/* Services Grid */
.services {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 20px;
}

.service-item {
background-color: #fff;
border: 1px solid #f4a261;
border-radius: 8px;
padding: 15px;
box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
transition: transform 0.3s, box-shadow 0.3s;
position: relative;
}

.service-item:hover {
transform: scale(1.02);
box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
}

.service-item img {
width: 100%;
height: 150px;
object-fit: cover;
border-radius: 5px;
}

.service-item-details h4 {
font-size: 1rem;
color: #333;
margin: 10px 0;
}

.service-item-details p {
font-size: 0.9rem;
color: #666;
margin-bottom: 5px;
}

.service-item-details h5 {
font-size: 1.1rem;
color: #e63946;
font-weight: bold;
}

/* Quantity Selector */
.quantity-selector {
display: flex;
align-items: center;
gap: 5px;
}

.quantity-selector button {
border: none;
background-color: #e63946;
color: #fff;
padding: 5px;
border-radius: 5px;
cursor: pointer;
}

.quantity-selector input {
width: 50px;
text-align: center;
border: 1px solid #ddd;
border-radius: 5px;
}

/* Add to Cart Button */
.add-to-cart-btn {
width: 100%;
background-color: #e63946;
color: #fff;
border: none;
padding: 10px;
border-radius: 5px;
font-weight: bold;
margin-top: 10px;
transition: background-color 0.3s;
}

.add-to-cart-btn:hover {
background-color: #b83238;
}

/* Alert */
.alert-success {
background-color: #e8f5e9;
color: #2e7d32;
font-size: 0.9rem;
margin-top: 10px;
}