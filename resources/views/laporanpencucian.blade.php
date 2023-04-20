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
    <title>Laporan Pencucian</title>
</head>
<body>
    <h3 class="customHeader">{{ $judul }}</h3>
    <h3 class="customHeader">{{ $subJudul }}</h3>

    <table style="width:100%; margin-left:auto; margin-right:auto;">
            <thead>
                <tr>
                    <th style="width: 50px">No</th>
                    <th style="width: 120px">Tanggal Pencucian</th>
                    <th style="width: 100px">Nama Kendaraan</th>
                    <th style="width: 150px">Pencuci</th>
                    <th>Pembayaran</th>
                    <th>Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1 @endphp
                @foreach($files as $file)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$file->tgl_pencucian}}</td>
                    <td>{{$file->kendaraan->nama}}</td>

                    @php
                    echo '<td>';
                        foreach($file->karyawan_pencucis as $pencuci){
                            echo $pencuci->nama . '<br/>';
                        }
                    echo '</td>';
                    @endphp

                    <td>Rp{{$file->pembayaran ?? 0}}</td>
                    <td>Rp{{$file->keuntungan}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: center"><b>Total Pendapatan</b></td>
                    <td><b>Rp{{ $totalPendapatan }}</b></td>
                </tr>
            </tfoot>
    </table>
</body>
</html>