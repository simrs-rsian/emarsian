<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WaBroadcastProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Mendaftarkan helper sebagai singleton agar bisa digunakan di seluruh aplikasi
        $this->app->singleton('WaBroadcastHelper', function () {
            return new class {

                public function sendBatchMessages(array $messages)
                {
                    // dd($messages);
                    $token = "pvoJxG7w4QRQvYxex8hQ";
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
                        CURLOPT_POSTFIELDS => [
                            'data' => json_encode($messages)
                        ],
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
