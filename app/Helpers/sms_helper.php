<?php

if (!function_exists('send_sms_api')){
    function send_sms_api($phone, $message) {
        $api_key = 'bdc11a9a7ea64640';
        $sender_id = 'LYAMAHORO S';
        $secret_key = 'NDM1ODU5YjA1NDIyNWQ4ZTBkYjkzOGQyYjRlMzBlNzM3YTU3NDBmM2RmZTI3NTY4NWU2YTUxMmQ3MmVjZThkMw==';

        // NORMALIZE PHONE NUMBER
        $phone = trim($phone);
        $phone = str_replace('+', '', $phone);
        if(substr($phone, 0, 1) == '0') {
            $phone = '255' . substr($phone, 1);
        }
        //API PAYLOAD
        $postData = [
            'source_addr' => $sender_id,
            'schedule_time' => '',
            'encoding' => 0,
            'message' => $message,
            'recipients' => [
                [
                    'recipient_id' => 1,
                    'dest_addr' => $phone
                ]
            ]
        ];
        //API URL
        $url = 'https://apisms.beem.africa/v1/send';
        //CURL INITIALIZATION
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' .
                base64_encode($api_key . ':' . $secret_key),
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($postData)
        ]);

        // EXECUTE REQUEST
        $response = curl_exec($ch);
        //HANDLE CURL ERRORS
        if(curl_errno($ch)){
            $error = curl_error($ch);
            curl_close($ch);
            return json_encode([
                'successful' => false,
                'error' => $error
            ]);
        }
        curl_close($ch);
        //RETURN API RESPONSE
        return $response;
    }
}