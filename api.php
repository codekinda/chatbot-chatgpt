<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $response = [];
//echo"Dreams sent!";
$message = htmlspecialchars(trim($_POST["chat"]));
$reply = "";
if(empty($message)){
$response["error"] = "Enter your dream";
} else{
    //Integration of the API
    $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ['role' => 'system', 'content' => 'You are a dream interpretation expert.'],
                ['role' => 'user', 'content' => "A user submitted the following dream: \"$message\" . Provide a detailed interpretation"]
            ],
            'max_tokens' => 150,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer Paste_API_Key_Here',
        ]);
        $result = curl_exec($ch);
       //var_dump($result);
        if(curl_errno($ch)){
            $response["error"] = 'Errror: ' . curl_error($ch);
        }else{
            $result = json_decode($result, true);
            if(isset($result['choices'][0]['message']['content'])){
                $reply = trim($result['choices'][0]['message']['content']);
            }else{
                $response["error"] = "Invalid API Response: " . json_encode($result);
            }
        }
curl_close($ch);
}
$response["reply"] = $reply;

//Convert to json
header('Content-Type: application/json');
echo json_encode($response);
exit;
}
?>