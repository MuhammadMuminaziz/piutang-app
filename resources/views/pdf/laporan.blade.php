<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
    <style>
        .table {
            border-collapse: collapse;
        }
        
        .table tr th{
            background: rgb(219, 219, 219);
            text-align: left;
            padding: 3px 5px;
        }

        .table tr td{
            padding: 3px 5px;
        }

        .border-none {
            border-top: 1px solid black;
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
        <tr>
            <td class="border-none"></td>
            <td class="border-none"></td>
            <td class="border-none" style="text-align: center" colspan="2">TOTAL</td>
            <td class="bill border-none">{{ currency_IDR($data['pendapatan']) }}</td>
        </tr>
    </table>
</body>
</html>