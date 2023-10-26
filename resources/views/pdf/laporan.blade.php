<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur</title>
    <style>
        .table {
            border-collapse: collapse;
        }
        
        .table tr th{
            background: rgb(219, 219, 219);
            text-align: left;
            padding: 0 5px;
        }

        .table tr td{
            padding: 0 5px;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center">LAPORAN PIUTANG</h3>
    <hr>
    <p>Tanggal Cetak {{ date('d F Y') }}</p>
    <table width="100%" class="table">
        <tr>
            <th class="text-start">No</th>
            <th class="text-start">ID Customer</th>
            <th class="text-start">Nama Pelanggan</th>
            <th class="text-start">Alamat</th>
            <th class="text-start">Piutang</th>
        </tr>
        @foreach ($piutangs as $piutang)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $piutang->id_customer }}</td>
                <td>{{ $piutang->name }}</td>
                <td>{{ $piutang->address }}</td>
                <td>{{ currency_IDR($piutang->bill) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>