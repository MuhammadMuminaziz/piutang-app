<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Histori</title>
    <style>
        .table {
            margin-top: 10px;
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

        .bill {
            text-align: right !important;
        }

        .border-none {
            border-bottom: 0px !important;
        }
    </style>
</head>
<body>
    <table width="50%">
        <tr>
            <td>Id Customer</td>
            <td>:</td>
            <td>{{ $user['id_customer'] }}</td>
        </tr>
        <tr>
            <td>Nama Customer</td>
            <td>:</td>
            <td>{{ $user['name'] }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $user['address'] }}</td>
        </tr>
        <tr>
            <td>Piutang</td>
            <td>:</td>
            <td>{{ currency_IDR($user['price']) }}</td>
        </tr>
        <tr>
            <td>Sisa Piutang</td>
            <td>:</td>
            <td>{{ currency_IDR($user['bill']) }}</td>
        </tr>
    </table>
    <table class="table" width="100%">
        <tr>
            <th width="40px">No.</th>
            <th>Tanggal</th>
            <th class="bill">Bayar</th>
        </tr>
        @foreach ($user->credits as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d F Y', strtotime($data['created_at'])) }}</td>
                <td class="bill">{{ currency_IDR($data['price']) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>