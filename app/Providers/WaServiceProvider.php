<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class WaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Mendaftarkan helper sebagai singleton agar bisa digunakan di seluruh aplikasi
        $this->app->singleton('WaHelper', function () {
            return new class {

                public function sendMessage($phone, $pesan)
                {
                    $token = "pvoJxG7w4QRQvYxex8hQ";
                    // dd($phone, $pesan);
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.fonnte.com/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_CONNECTTIMEOUT => 30,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array(
                            'target' => $phone,
                            'message' => $pesan,
                            'countryCode' => '62',
                            'typing' => false,
                            'delay' => '2',
                        ),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: ' . $token,
                        ),
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_VERBOSE => true,
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    // dd($response);
                    return $response;
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
