<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur</title>
    <style>
        .table {
            margin-top: 10px;
            border-collapse: collapse;
        }

        .table tr th{
            text-align: left;
            padding: 5px 5px;
            border-top: 1px solid black;
        }

        .table tr td{
            padding: 5px 5px;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
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
            <td>{{ $data['id_customer'] }}</td>
        </tr>
        <tr>
            <td>Nama Customer</td>
            <td>:</td>
            <td>{{ $data['name'] }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $data['address'] }}</td>
        </tr>
    </table>
    <table class="table" width="100%">
        <tr>
            <th>No.</th>
            <th>No. Faktur</th>
            <th>Tanggal Jt Tempo</th>
            <th class="bill">Piutang</th>
        </tr>
        <tr>
            <td>1</td>
            <td>{{ $data['no_faktur'] }}</td>
            <td>{{ $data['tempo'] }}</td>
            <td class="bill">{{ currency_IDR($data['bill']) }}</td>
        </tr>
        <tr>
            <td class="border-none"></td>
            <td class="bill border-none" colspan="2">TOTAL</td>
            <td class="bill border-none">{{ currency_IDR($data['bill']) }}</td>
        </tr>
    </table>
</body>
</html>