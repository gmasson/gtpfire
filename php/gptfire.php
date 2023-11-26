<?php
/*
	GPTFire 1.0
	Micro bilioteca PHP e JS para conectar com a API da OpenAI, com prompts pré-definidos
    
    https://github.com/gmasson/gtpfire

	Licença MIT

	Modelos disponíveis:
	- gpt-3.5-turbo (até 4.096 fichas)
	- gpt-3.5-turbo-16k (até 16.384 fichas)
	- gpt-4 (até 8.192 fichas)
	- gpt-4-32k (até 32.768 fichas)

	Atualmente, o GPT-4 está acessível para aqueles que fizeram pelo menos um pagamento bem-sucedido por meio de nossa plataforma de desenvolvedor.
	Veja mais em https://platform.openai.com/docs/models
*/

class GPTFire {
    private $apiKey;
    private $model;
    private $max_tokens;
    private $endpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct($apiKey, $model = 'gpt-3.5-turbo-16k', $max_tokens = 16000) {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->max_tokens = $max_tokens;
    }

    public function generate($prompt, $promptStart = '', $modo = 0.5) {
        $prompt = trim($prompt);
        $prompt = htmlspecialchars($prompt);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        );

        $modo = intval($modo);

        $data = array(
            'model' => $this->model,
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $promptStart
                ),
                array(
                    'role' => 'user',
                    'content' => $prompt
                )
            ),
            'max_tokens' => $this->max_tokens,
            'temperature' => $modo,
        );

        $jsonData = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "Erro na chamada à API da OpenAI: " . curl_error($ch);
            return null;
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['choices']) && !empty($responseData['choices'])) {
            $content = $responseData['choices'][0]['message']['content'];
            return $content;
        } else {
            return "Erro na resposta da API da OpenAI.";
        }
    }
}