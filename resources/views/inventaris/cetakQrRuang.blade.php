<html>
  <div class="card">
    <div class="card-content">
      <div class="card-header">
        <table style="width: 100%;">
          <tr>
            <td style=" width: 5%; vertical-align: top; font-size: 12px;">
              <img src="{{ $logo }}" width="60px">
            </td>
            <td style=" vertical-align: top; font-size: 13px;">
               <center>
                <b style="font-size: 14;">{{ $settings->name }} </b><br>
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
        <table style="width: 100%;">
          <tr>
            <td>
              <center>
                <h4 style="text-align: center;">QR Code Ruang {{ $ruangs->nama_ruang }} (Kd. {{ $ruangs->id_ruang }})</h4>
                <p style="text-align: center;">Scan QR Code ini untuk melihat data inventaris ruang ini</p>
                <img src="{{ $QrRuangs }}" alt="QR Code" width="80px" height="auto">
              </center>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</html>