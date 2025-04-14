<html>  
@foreach ($ruangsData as $data)
  <div class="card">
    <div class="card-content">
      <div class="card-header">
        <table style="width: 100%;">
          <tr>
            <td style="width: 5%; vertical-align: top; font-size: 12px;">
              <img src="{{ $logo }}" width="50px">
            </td>
            <td style="vertical-align: top; font-size: 13px;">
              <center>
                <b style="font-size: 18px;">{{ $settings->name }}</b><br>
                Alamat: {{ $settings->address }}<br>
                Tlpn: {{ $settings->phone }} email: {{ $settings->email }}<br>
                Website: https://rsiaisyiyahnganjuk.com
              </center>
            </td>
          </tr>
        </table>
        <hr>
      </div>
      <div class="card-body">
          <table style="width: 100%; margin-bottom: 20px;">
            <tr>
              <td>
                <center>
                  <h4 style="text-align: center;">QR Code Ruang {{ $data['ruang']->nama_ruang }} (Kd. {{ $data['ruang']->id_ruang }})</h4>
                  <p style="text-align: center;">Scan QR Code ini untuk melihat data inventaris ruang ini</p>
                  <img src="{{ $data['QrRuangs'] }}" alt="QR Code" width="150px" height="auto">
                </center>
              </td>
            </tr>
          </table>
      </div>
    </div>
  </div>
@endforeach
</html>