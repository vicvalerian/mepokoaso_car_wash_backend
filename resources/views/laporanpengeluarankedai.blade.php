<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table, th, td {
          border-collapse: collapse;
          border: 1px solid black;
          height: 30px;
          text-align: center;
        }

        .customHeader{
            text-align: center;
        }
    </style>
    <title>Laporan Pengeluaran Kedai</title>
</head>
<body>
    <h3 class="customHeader">{{ $judul }}</h3>
    <h3 class="customHeader">{{ $subJudul }}</h3>

    <table style="width:100%; margin-left:auto; margin-right:auto;">
            <thead>
                <tr>
                    <th style="width: 50px">No</th>
                    <th>Tanggal Pembelian</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang(pcs)</th>
                    <th>Harga Pembelian</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1 @endphp
                @foreach($files as $file)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$file->tgl_pembelian}}</td>
                    <td>{{$file->nama_barang}}</td>
                    <td>{{$file->jumlah_barang}}</td>
                    <td>Rp{{$file->harga_pembelian}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: center"><b>Total Pengeluaran</b></td>
                    <td><b>Rp{{ $totalPengeluaran }}</b></td>
                </tr>
            </tfoot>
    </table>
</body>
</html>