<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice-{{ $noInvoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .print-button {
            margin-top: 20px;
            width: 100px;
            height: 40px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-align: center;
            font-size: 15px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice">
        <h1>Invoice</h1>
        <p><strong>Nama:</strong> {{ $name }}</p>
        <p><strong>Tanggal:</strong> {{ $date }}</p>
        <p><strong>Invoice Number:</strong> {{ $noInvoice }}</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Quantity</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product['no'] }}</td>
                        <td>{{ $product['deskripsi'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ 'Rp. ' . number_format((int) str_replace(',', '', $product['harga_satuan'])) }}</td>
                        <td>{{ 'Rp. ' . number_format((int) str_replace(',', '', $product['total'])) }}
                        </td>
                    </tr>
                @endforeach

                <!-- Total row inside the loop -->
                <tr>
                    <td colspan="4" style="text-align: end"><strong>Total:</strong></td>
                    <td>{{ 'Rp. ' . number_format($allTotal, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Remove the total outside the loop -->
        <!-- <p style="text-align: end"><strong>Total:</strong> {{ 'Rp. ' . number_format($allTotal, 2, '.', ',') }}</p> -->

        <button onclick="printInvoice()" class="print-button">Print</button>
    </div>

    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</body>

</html>


</html>
