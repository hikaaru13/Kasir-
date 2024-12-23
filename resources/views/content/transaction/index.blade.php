@extends('layouts.app')

@section('title', ucfirst('transaction') . ' Management')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Data Transaction</h6>
                        <div>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('laporan.index') }}'">
                                Lihat Laporan
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                                Export to Excel
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionModal"
                                id="addTransactionButton">
                                Tambah Transaction
                            </button>
                        </div>
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
                                    <th>Transaction ID</th>
                                    <th>Customer Name</th>
                                    <th>Qty</th>
                                    <th>Method Payment</th>
                                    <th>Product</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $item)
                                    <tr>
                                        <td>{{ $item->transaction_id }}</td>
                                        <td>{{ $item->customer_name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ ucfirst($item->method_payment) }}</td>
                                        <td>{{ $item->product->name }} [{{ number_format($item->product->price, 2) }}]</td>
                                        <td>{{ $item->total }}</td>
                                        <td>{{ $item->created_at->format('d F Y') }} : {{ $item->created_at->format('H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info editTransactionButton"
                                                data-id="{{ $item->transaction_id }}"
                                                data-customer_name="{{ $item->customer_name }}"
                                                data-qty="{{ $item->qty }}"
                                                data-method_payment="{{ $item->method_payment }}"
                                                data-total="{{ $item->total }}"
                                                data-product_id="{{ $item->product_id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('transaction.delete', $item->transaction_id) }}" method="POST"
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

    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="transactionForm" method="POST" action="{{ route('transaction.save') }}">
                    @csrf
                    <!-- <input type="hidden" id="modalMethod" name="_method"> -->
                    <input type="hidden" id="transactionId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modalCustomerName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="modalCustomerName" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalQty" class="form-label">Qty</label>
                            <input type="number" class="form-control" id="modalQty" name="qty" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalMethodPayment" class="form-label">Method Payment</label>
                            <select class="form-control" id="modalMethodPayment" name="method_payment" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modalProduct" class="form-label">Product</label>
                            <select class="form-control" id="modalProduct" name="product_id" required>
                                <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->product_id }}">
                                        {{ $product->name }} [{{ number_format($product->price, 2) }}]
                                    </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="mb-3">
                            <label for="modalTotal" class="form-label">Total</label>
                            <input type="hidden" class="form-control" id="modalTotal" name="total" required>
                            <div id="totalDisplay" class="form-control bg-light" readonly>Rp 0</div>
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

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="exportForm" method="POST" action="{{ route('transactions.export') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="end_date">
                        </div>
                        <div class="mb-3">
                            <label for="methodPayment" class="form-label">Payment Method</label>
                            <select class="form-control" id="methodPayment" name="method_payment">
                                <option value="">-- All Payment Methods --</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productId" class="form-label">Product</label>
                            <select class="form-control" id="productId" name="product_id">
                                <option value="">-- All Products --</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">
                                    {{ $product->name }} [{{ number_format($product->price, 2) }}]
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            $('#modalProduct, #modalQty').on('change', function () {
                calculateTotal();
            });

            function calculateTotal() {
                const productSelect = $('#modalProduct');
                const qtyInput = $('#modalQty');
                const totalInput = $('#modalTotal');

                if (productSelect.val() && qtyInput.val()) {
                    const selectedProduct = productSelect.find('option:selected');
                    const productPrice = parseFloat(selectedProduct.text().match(/\[(.*?)\]/)[1].replace(/,/g, ''));
                    const qty = parseInt(qtyInput.val());

                    const total = productPrice * qty;
                    totalInput.val(total);
                    
                    // Optional: Format and display total with currency formatting
                    $('#totalDisplay').text('Rp ' + total.toLocaleString('id-ID'));
                } else {
                    totalInput.val('');
                    $('#totalDisplay').text('Rp 0');
                }
            }

            $('#addTransactionButton').on('click', function () {
                $('#transactionModalLabel').text('Tambah Transaction');
                $('#transactionForm').attr('action', '{{ route('transaction.save') }}');
                $('#modalMethod').val('');
                $('#modalSubmitButton').text('Tambah Transaction');
                clearModalFields();
                calculateTotal(); // Add this line
            });

            $('.editTransactionButton').on('click', function () {
                const data = $(this).data();
                $('#transactionModalLabel').text('Edit Transaction');
                $('#transactionForm').attr('action', '{{ route('transaction.save') }}');
                // Remove the hidden method input
                $('#modalMethod').remove();
                // Instead, add a hidden input to indicate this is an edit
                $('#transactionForm').append('<input type="hidden" name="_edit" value="1">');
                $('#modalSubmitButton').text('Save Changes');
                populateModalFields(data);
                $('#transactionModal').modal('show');
                calculateTotal();
            });

            $('#startDate').on('change', function () {
                $('#endDate').attr('min', $(this).val());
            });

            function clearModalFields() {
                $('#transactionId').val('');
                $('#modalCustomerName').val('');
                $('#modalQty').val('');
                $('#modalMethodPayment').val('');
                $('#modalTotal').val('');
                $('#modalProduct').val('');
            }

            function populateModalFields(data) {
                $('#transactionId').val(data.id);
                $('#modalCustomerName').val(data.customer_name);
                $('#modalQty').val(data.qty);
                $('#modalMethodPayment').val(data.method_payment);
                $('#modalTotal').val(data.total);
                $('#modalProduct').val(data.product_id);
            }
        });
    </script>
@endsection
