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
          height: 40px;
          text-align: center;
        }

        .customHeader{
            text-align: center;
        }
    </style>
    <title>Laporan Transaksi Kedai</title>
</head>
<body>
    <h3 class="customHeader">{{ $judul }}</h3>
    <h3 class="customHeader">{{ $subJudul }}</h3>

    <table style="width:100%; margin-left:auto; margin-right:auto;">
            <thead>
                <tr>
                    <th style="width: 50px">No</th>
                    <th style="width: 120px">Tanggal Penjualan</th>
                    <th>Nama Menu</th>
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1 @endphp
                @foreach($files as $file)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$file->tgl_penjualan}}</td>

                    @php
                    echo '<td>';
                        foreach($file->menu_kedai as $menu){
                            echo $menu->nama . '<br/>';
                        }
                    echo '</td>';
                    @endphp

                    @php
                    echo '<td>';
                        foreach($file->menu_kedai as $menu){
                            echo $menu->pivot->kuantitas . '<br/>';
                        }
                    echo '</td>';
                    @endphp

                    @php
                    echo '<td>';
                        foreach($file->menu_kedai as $menu){
                            echo 'Rp'. $menu->pivot->sub_total . '<br/>';
                        }
                    echo '</td>';
                    @endphp

                    <td>Rp{{$file->total_penjualan}}</td>
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