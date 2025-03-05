<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\PhoneVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InfobipService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('INFOBIP_BASE_URL');
        $this->apiKey = env('INFOBIP_API_KEY');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'App ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function sendSms($to, $message)
    {
        try {
            $response = $this->client->post('/sms/2/text/advanced', [
                'json' => [
                    'messages' => [
                        [
                            'from' => 'InfoSMS',
                            'destinations' => [
                                ['to' => $to]
                            ],
                            'text' => $message
                        ]
                    ]
                ],
                // Thêm các thiết lập timeout để đảm bảo không bị chờ quá lâu
                'timeout' => 30, // Thời gian timeout (giây)
                'connect_timeout' => 10, // Thời gian chờ kết nối (giây)
            ]);

            // Trả về phản hồi dưới dạng JSON
            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Xử lý lỗi request HTTP
            if ($e->hasResponse()) {
                // Ghi log lỗi từ phản hồi của server
                $response = $e->getResponse();
                $errorBody = $response->getBody()->getContents();
                Log::error('SMS API Error: ' . $errorBody);
                return [
                    'success' => false,
                    'error' => 'Server responded with an error.',
                    'details' => json_decode($errorBody, true)
                ];
            } else {
                // Ghi log lỗi kết nối hoặc lỗi không có phản hồi
                Log::error('SMS API Connection Error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'error' => 'Connection error: ' . $e->getMessage()
                ];
            }
        } catch (\Exception $e) {
            // Ghi log các lỗi khác không phải từ Guzzle
            Log::error('Unexpected Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * send code verify to user
     *
     * @param integer $phoneNumber
     * @return void
     */
    public function sendVerificationCode(string $phoneNumber): void
    {
        $code = Str::random(6);

        PhoneVerification::where('phone_number', $phoneNumber)->delete();

        PhoneVerification::create([
            'phone_number' => $phoneNumber,
            'verification_code' => Hash::make($code),
        ]);

        $this->sendSms($phoneNumber, "Mã xác nhận số điện thoại của bạn là: $code");
    }

    /**
     * function verify code if true then remove code in database  
     *
     * @param integer $phoneNumber
     * @param string $code
     * @return boolean
     */
    public function verifyCode(int $phoneNumber, string $code): bool
    {
        $verification = PhoneVerification::where('phone_number', $phoneNumber)->first();

        if ($verification && Hash::check($code, $verification->verification_code)) {
            $created_at = Carbon::parse($verification->created_at);
            $now = Carbon::now();

            if ($created_at->diffInSeconds($now) <= 60) {
                $verification->delete();
                return true;
            } else {
                return false; 
            }
        }

        return false;
    }

}
