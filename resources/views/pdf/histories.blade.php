<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>
    <style>
        .table {
            margin-top: 10px;
            border-collapse: collapse;
        }

        .table tr th{
            /* background: rgb(219, 219, 219); */
            text-align: left;
            padding: 6px 5px;
            border-bottom: 1px solid black;
        }

        .table tr td{
            padding: 3px 5px;
        }

        .bill {
            text-align: right !important;
        }

        .border-none {
            border-top: 1px solid black;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center">LAPORAN TRANSAKSI PEMBAYARAN</h3>
    <hr>
    <p>Tanggal Cetak {{ date('d F Y') }}</p>
    <table class="table" width="100%">
        <tr>
            <th width="35px">No.</th>
            <th>Tgl Transaksi</th>
            <th>No Faktur</th>
            <th>Nama Kasir</th>
            <th>Nama Pelanggan</th>
            <th>Diskon</th>
            <th class="bill">Bayar</th>
            <th>No Mesin</th>
            <th>Cara Bayar</th>
            <th>Jam</th>
        </tr>
        @foreach ($credits as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                <td>{{ $row->piutang->no_faktur }}</td>
                <td>{{ $row->piutang->name_cathier }}</td>
                <td>{{ $row->piutang->name }}</td>
                <td>0</td>
                <td class="bill">{{ currency_IDR($row['price']) }}</td>
                <td>001</td>
                <td>Cash</td>
                <td>{{ date('H:i:s', strtotime($row['created_at'])) }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="border-none"></td>
            <td class="border-none"></td>
            <td class="border-none" style="text-align: center" colspan="3">TOTAL</td>
            <td class="border-none">0</td>
            <td class="bill border-none">{{ currency_IDR($data['pendapatan']) }}</td>
            <td class="border-none"></td>
            <td class="border-none"></td>
            <td class="border-none"></td>
        </tr>
    </table>
</body>
</html>