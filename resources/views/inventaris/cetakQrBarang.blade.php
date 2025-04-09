<html>
  <div class="card">
    <div class="card-content">
      <div class="card-header">
        <table style="width: 100%;">
          <tr>
            <td style=" width: 5%; vertical-align: top; font-size: 12px;">
              <img src="{{ $logo }}" width="50px">
            </td>
            <td style=" vertical-align: top; font-size: 13px;">
               <center>
                <b style="font-size: 18;">{{ $settings->name }} </b><br>
                Alamat : {{ $settings->address }} <br>
                Tlpn : {{ $settings->phone }} email : {{ $settings->email }} <br>
                Website : https://rsiaisyiyahnganjuk.com
              </center>
            </td>
          </tr>
        </table>
        <hr>
      </div>
      <div class="card-body">
        <br><br>
        <table style="width: 100%;">
          <tr>
            <td>
              <center>
                <img src="{{ $QrBarangs }}" alt="QR Code" width="150px" height="auto">
              </center>
            </td>
            <td>
              <center>
                <h4 style="text-align: center;">QR Code Barang Di {{ $ruangs->nama_ruang }} (Kd. {{ $ruangs->id_ruang }}) <br></h4>
                Nama Barang : {{ $inventaris->nama_barang }}<br>
                Kode Barang : {{ $inventaris->kode_barang }}<br>
                No. Inventaris : {{ $inventaris->no_inventaris }}<br>
              </center>
            </td>
          </tr>
        </table>
        
        <p style="text-align: center;">Scan QR Code ini untuk melihat data inventaris ruang ini</p>
      </div>
    </div>
  </div>
</html>