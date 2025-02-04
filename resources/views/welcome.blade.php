<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Coalition - Interview - Test</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    </head>
    <body>
        <section class="container mt-5">
            <h1>Coalition Interview Test</h1>
            <form id="product-form">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" required>
                </div>
                <div class="mb-3">
                    <label for="price_per_item" class="form-label">Price per Item</label>
                    <input type="number" step="0.01" class="form-control" id="price_per_item" name="price_per_item" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>

            <h2 class="mt-5">Product List</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity in Stock</th>
                        <th>Price per Item</th>
                        <th>Datetime Submitted</th>
                        <th>Total Value Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    @php $totalValueSum = 0; @endphp
                    @foreach($products as $product)
                        @php $totalValue = $product->quantity_in_stock * $product->price_per_item; @endphp
                        @php $totalValueSum += $totalValue; @endphp
                    <tr data-id="{{ $product->id }}">
                        <td class="editable" data-field="name">{{ $product->name }}</td>
                        <td class="editable" data-field="quantity_in_stock">{{ $product->quantity_in_stock }}</td>
                        <td class="editable" data-field="price_per_item">{{ $product->price_per_item }}</td>
                        <td>{{ $product->created_at }}</td>
                        <td>{{ $totalValue }}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary edit-btn">Edit</button>
                            <button class="btn btn-sm btn-success save-btn" style="display: none;">Save</button>
                            <button class="btn btn-sm btn-danger cancel-btn" style="display: none;">Cancel</button>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"><strong>Total</strong></td>
                        <td colspan="2"><strong>{{ $totalValueSum }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </section>
        <script src="{{ mix('js/app.js') }}"></script>
        <script>
             document.addEventListener('DOMContentLoaded', function () {
                const productForm = document.getElementById('product-form');
                const productTableBody = document.getElementById('product-table-body');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                productForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(productForm);
                const data = Object.fromEntries(formData.entries());

                fetch('{{ route('products.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(product => {
                    const totalValue = product.quantity_in_stock * product.price_per_item;
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-id', product.id);
                    newRow.innerHTML = `
                        <td class="editable" data-field="name">${product.name}</td>
                        <td class="editable" data-field="quantity_in_stock">${product.quantity_in_stock}</td>
                        <td class="editable" data-field="price_per_item">${product.price_per_item}</td>
                        <td>${product.created_at}</td>
                        <td>${totalValue}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary edit-btn">Edit</button>
                            <button class="btn btn-sm btn-success save-btn" style="display: none;">Save</button>
                            <button class="btn btn-sm btn-danger cancel-btn" style="display: none;">Cancel</button>
                        </td>
                    `;
                    productTableBody.insertBefore(newRow, productTableBody.firstChild);
                    updateTotalValueSum(totalValue);
                    productForm.reset();
                }) .catch(error => console.error('Error:', error));
                });
            }
        </script>
    </body>
</html>
