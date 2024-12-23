@extends('layouts.app')

@section('title', ucfirst('product') . ' Management')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Data Product</h6>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal"
                            id="addProductButton">
                            Tambah Product
                        </button>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>Product_id</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Variant</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $item)
                                    <tr>
                                        <td>{{ $item->product_id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->stock }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->variant }}</td>
                                        <td>{{ $item->category }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info editProductButton"
                                                data-id="{{ $item->product_id }}"
                                                data-product_id="{{ $item->product_id }}"
                                                data-name="{{ $item->name }}"
                                                data-stock="{{ $item->stock }}"
                                                data-price="{{ $item->price }}"
                                                data-variant="{{ $item->variant }}"
                                                data-category="{{ $item->category }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('product.delete', $item->product_id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productForm" method="POST" action="{{ route('product.save') }}">
                    @csrf
                    <!-- <input type="hidden" id="modalMethod" name="_method"> -->
                    <input type="hidden" id="productId" name="id">
                    <div class="modal-body">
                        <div class="mb-3" id="productIdField" style="display: none;">
                            <label for="modalProduct_id" class="form-label">Product_id</label>
                            <input type="text" class="form-control" id="modalProduct_id" name="product_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="modalName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalStock" class="form-label">Stock</label>
                            <input type="text" class="form-control" id="modalStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="modalPrice" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalVariant" class="form-label">Variant</label>
                            <input type="text" class="form-control" id="modalVariant" name="variant" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="modalCategory" name="category" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="modalSubmitButton"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#addProductButton').on('click', function() {
                $('#productModalLabel').text('Tambah Product');
                $('#productForm').attr('action', '{{ route('product.save') }}');
                // Remove this line
                // $('#modalMethod').val('');
                $('#modalSubmitButton').text('Tambah Product');
                $('#productIdField').hide();
                clearModalFields();
            });

            $('.editProductButton').on('click', function() {
                const data = $(this).data();
                $('#productModalLabel').text('Edit Product');
                $('#productForm').attr('action', '{{ route('product.save') }}');
                // Remove this line
                // $('#modalMethod').val('PUT');
                $('#modalSubmitButton').text('Save Changes');
                $('#productIdField').show();
                populateModalFields(data);
                $('#productModal').modal('show');
            });

            function clearModalFields() {
                // Remove the hidden input for productId
                $('#productId').val('');
                $('#modalProduct_id').val('');
                $('#modalName').val('');
                $('#modalStock').val('');
                $('#modalPrice').val('');
                $('#modalVariant').val('');
                $('#modalCategory').val('');
            }

            function populateModalFields(data) {
                // Populate productId for both hidden inputs
                $('#productId').val(data.id);
                $('#modalProduct_id').val(data.product_id);
                $('#modalName').val(data.name);
                $('#modalStock').val(data.stock);
                $('#modalPrice').val(data.price);
                $('#modalVariant').val(data.variant);
                $('#modalCategory').val(data.category);
            }
        });
    </script>
@endsection
