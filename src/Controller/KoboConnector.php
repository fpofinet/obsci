<?php
namespace App\Controller;
class KoboConnector {
    private  string $token;
    
    public function __construct(string $token,) {
        $this->token = $token;
    }

    public function findAll(string $link): array 
    {
        $data=array();
        $options = [
            CURLOPT_URL => $link,
            CURLOPT_HTTPHEADER => [
                "Authorization: Token {$this->token}",
                "Content-Type: application/json",
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        if (!curl_errno($curl)) {
            $data = json_decode($response, true);
            curl_close($curl);
            return $data;
        } else {
            echo 'Erreur cURL : ' . curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }

    public function downloadImg(string $file):?string
    {
        $localFileName = uniqid().'.jpg';
        $ch = curl_init($file);
        $options = [
            CURLOPT_HTTPHEADER => [
                "Authorization: Token {$this->token}",
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ];
        curl_setopt_array($ch, $options);
        $imageContent = curl_exec($ch);
        curl_close($ch);
        if ($imageContent !== false) {
            file_put_contents("fichier/".$localFileName, $imageContent);
            return $localFileName;
        } else {
            echo 'Erreur lors du téléchargement de l\'image.';
            return null;
        }
    }
}
?>