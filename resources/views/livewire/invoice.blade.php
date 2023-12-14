@section('css')
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .desc-invoice {
            text-align: left;
            font-size: 22px;
            margin-top: 10px;
        }

        .card-invoice {
            border-radius: 10px;
        }

        th,
        td {
            font-size: 20px;
        }

        label {
            font-size: 20px;
        }

        hr {
            border: 1px solid black;
        }
    </style>
@endsection

<div class="container mt-5 mb-5">
    <div class="card shadow card-invoice">
        <div class="card-body p-4">
            <form action="">
                <div class="row">
                    <div class="col">
                        <h1>Invoice</h1>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input wire:model="name" type="text" class="form-control" id="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <h1 class="desc-invoice">Tanggal : <strong>{{ $date }}</strong></h1>
                            <h1 class="desc-invoice">Invoice Number : <strong>{{ $noInvoice }}</strong></h1>
                        </div>
                    </div>
                    {{-- <div class="col">
                        <h1 class="desc-invoice">Tanggal : <strong>{{ $date }}</strong></h1>
                        <h1 class="desc-invoice">Invoice Number : <strong>{{ $noInvoice }}</strong></h1>
                    </div> --}}
                </div>
                <hr>
                <div class="row mt-4">
                    <div class="col">
                        <h3 class="fw-bold">Products</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>Quantity</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                    <th style="opacity: 0">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr wire:key="{{ $key }}">
                                        <td>{{ $product['no'] }}</td>
                                        <td>
                                            <input wire:model="products.{{ $key }}.deskripsi" type="text"
                                                class="form-control">
                                            @error("products.$key.deskripsi")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input wire:model="products.{{ $key }}.quantity" type="number"
                                                class="form-control" wire:keyup="getTotal({{ $key }})">
                                            @error("products.$key.quantity")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input wire:model="products.{{ $key }}.harga_satuan"
                                                type="number" class="form-control"
                                                wire:keyup="getTotal({{ $key }})">
                                            @error("products.$key.harga_satuan")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            {{ isset($product['total']) ? 'Rp. ' . number_format($product['total'], 2, '.', ',') : '' }}
                                        </td>
                                        <td style="text-align: center">
                                            <button wire:click="removeRow({{ $key }})" type="button"
                                                class="btn bi bi-x-lg"></button>
                                        </td>
                                    </tr>
                                @endforeach
                                @error('products')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if ($products)
                                    <tr>
                                        <td colspan="4">
                                            <div class="row justify-content-end">
                                                <div class="col-3">
                                                   <label class="fw-bold">Tipe Diskon: &nbsp;</label>
                                                    <select wire:model="diskonType" class="form-select"
                                                        wire:change="resetDiskonType">
                                                        <option value="">--pilih diskon--</option>
                                                        <option value="percent">Percent
                                                        </option>
                                                        <option value="nominal">Nominal
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($diskonType === 'percent')
                                                <div class="col d-flex justify-content-end mt-3">
                                                    <p class="fw-bold">Diskon In Percent : &nbsp;</p>
                                                    <div class="col-1">
                                                        <input type="number" min="0" max="100"
                                                            class="form-control" wire:model="diskonInPercent"
                                                            wire:keyup="getOverallTotal" />
                                                    </div>
                                                    <i class="bi bi-percent mt-2 ms-2"></i>
                                                </div>
                                            @elseif ($diskonType === 'nominal')
                                                <div class="col d-flex justify-content-end mt-3">
                                                    <p class="fw-bold">Diskon In Nominal : &nbsp;</p>
                                                    <div class="col-2 d-flex">
                                                        <input type="number" min="0" class="form-control"
                                                            wire:model="diskonInNominal" wire:keyup="getOverallTotal" />
                                                    </div>
                                                </div>
                                            @else
                                            @endif
                                        </td>
                                        <td style="text-align: end"><strong>Total:</strong></td>
                                        <td>{{ 'Rp. ' . number_format($allTotal, 2, '.', ',') }}</td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-grid">
                        <button wire:click="addRow" type="button" class="btn btn-lg btn-secondary "><i
                                class="bi bi-plus-square me-2"></i>Add Row </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col d-flex justify-content-end">
            <button wire:click="print" type="button" class="btn btn-lg btn-primary"><i
                    class="bi bi-printer-fill me-2"></i>Buat</button>
        </div>
    </div>
</div>
