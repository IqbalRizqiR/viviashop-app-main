<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Penjualan</th>
        <th>Pembelian</th>
        <th>Pengurangan</th>
        <th>Pendapatan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($laporan as $lap)
        <tr>
            <td>{{ $lap['DT_RowIndex'] }}</td>
            <td>{{ $lap['tanggal'] }}</td>
            <td>{{$lap['penjualan']}}</td>
            <td>{{$lap['pembelian']}}</td>
            <td>{{$lap['pengeluaran']}}</td>
            <td>{{$lap['pendapatan']}}</td>
            <td colspan="2">Total : </td>
        </tr>
    @endforeach
    </tbody>
</table>
