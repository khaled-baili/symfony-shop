<?php
namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;
use phpDocumentor\Reflection\Types\This;

class Mail {
    private $api_key='f36e442796cf90059ff77886f31d0b96';
    private $api_key_secret='ee21bcdb0c0ae42471cd9fea16cf8fd9';

    public function send($to_email,$to_name,$subject,$content) {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "bailikhaled@gmail.com",
                        'Name' => "My-SHOP"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3498017,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variable' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}