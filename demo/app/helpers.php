<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

if (!function_exists('get_exchange_rate')) {
    /**
     * Get the exchange rate from USD to the target currency.
     *
     * @param string $toCurrency The target currency code (e.g., 'INR', 'EUR').
     * @return float|null The exchange rate or null if there's an error.
     */
    function get_exchange_rate($toCurrency)
    {
        try {
            // Create an instance of Guzzle client
            $client = new Client();

            // Set the API URL and your API key (replace 'your_api_key' with your actual key)
            $apiUrl = 'https://v6.exchangerate-api.com/v6/6d1a993f56a5046e676afcff/latest/USD';
            
            // Make the API request
            $response = $client->get($apiUrl);

            // Decode the JSON response
            $data = json_decode($response->getBody()->getContents(), true);

            // Check if the request was successful and contains the required data
            if (isset($data['conversion_rates'][$toCurrency])) {
                return $data['conversion_rates'][$toCurrency]; // Return the exchange rate
            }

            return null; // Return null if the currency is not found
        } catch (Exception $e) {
            // Handle any errors (e.g., API failure)
            return null;
        }
    }
}


function CampaignWhats($user_name, $phone_number, $image_url, $template_name, $campaign_content)
{


    $token = 'b61672fd01991fba1b131eb377051f3996ab1da67be47cb37c604839a9a4a010286758f6062b0c24919079e95c5ab05a750d4dd0b221ec7638d40179ed0905ff';
    $phoneNumber = '91'.$phone_number; // e.g., +1234567890
    // dd($user_name, $phoneNumber, $image_url, $template_name, $campaign_content);

    $url = "https://backend.askeva.io/v1/message/send-message?token=$token";

    $data = [
        "to" => $phoneNumber,
        "type" => "template",
        "template" => [
            "language" => [
                "policy" => "deterministic",
                "code" => "en"
            ],
            "name" => $template_name,
            "components" => [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $image_url
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $user_name
                        ],
                        [
                            "type" => "text",
                            "text" => $campaign_content // <- add this second parameter
                        ]
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    
    curl_close($ch);

    return $response;
    
}

function ProjectAmount($user_name, $phone_number, $image_url, $template_name, $advan_amount, $full_amount)
{

    $token = 'b61672fd01991fba1b131eb377051f3996ab1da67be47cb37c604839a9a4a010286758f6062b0c24919079e95c5ab05a750d4dd0b221ec7638d40179ed0905ff';
    $phoneNumber = '91'.$phone_number; // e.g., +1234567890
    // dd($user_name, $phone_number, $image_url, $template_name, $advan_amount, $full_amount);

    $url = "https://backend.askeva.io/v1/message/send-message?token=$token";

    $data = [
        "to" => $phoneNumber,
        "type" => "template",
        "template" => [
            "language" => [
                "policy" => "deterministic",
                "code" => "en"
            ],
            "name" => $template_name,
            "components" => [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $image_url
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $user_name
                        ],
                        [
                            "type" => "text",
                            "text" => $advan_amount // <- add this second parameter
                        ],
                        [
                            "type" => "text",
                            "text" => $full_amount // <- add this second parameter
                        ]
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    // dd($response);
    curl_close($ch);

    return $response;
    
}

function whatsapp_sent_recur($comments, $status)
{

    $token = 'b61672fd01991fba1b131eb377051f3996ab1da67be47cb37c604839a9a4a010286758f6062b0c24919079e95c5ab05a750d4dd0b221ec7638d40179ed0905ff';
    $phoneNumber = '9197892329293'; // e.g., +1234567890
    $user_name = "Webbitech";
    // dd($user_name, $phone_number, $image_url, $template_name, $advan_amount, $full_amount);

    $url = "https://backend.askeva.io/v1/message/send-message?token=$token";

    $data = [
        "to" => $phoneNumber,
        "type" => "template",
        "template" => [
            "language" => [
                "policy" => "deterministic",
                "code" => "en"
            ],
            "name" => "ny_bills",
            "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $user_name
                        ],
                        [
                            "type" => "text",
                            "text" => $comments // <- add this second parameter
                        ]
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    // dd($response);
    curl_close($ch);

    return $response;

}