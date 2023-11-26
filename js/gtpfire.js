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


class gtpFire {
	constructor(apiKey, model = 'gpt-3.5-turbo-16k', max_tokens = 16000) {
		this.apiKey = apiKey;
		this.model = model;
		this.max_tokens = max_tokens;
		this.endpoint = 'https://api.openai.com/v1/chat/completions';
	}

	generate(prompt, promptStart, temperature = 0.5) {
		prompt = prompt.trim();
		prompt = prompt.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

		const headers = {
			'Content-Type': 'application/json',
			'Authorization': 'Bearer ' + this.apiKey
		};

		temperature = parseInt(temperature);

		const data = {
			'model': this.model,
			'messages': [
				{
					'role': 'user',
					'content': promptStart
				},
				{
					'role': 'user',
					'content': prompt
				}
			],
			'max_tokens': this.max_tokens,
			'temperature': temperature
		};

		const jsonData = JSON.stringify(data);

		return fetch(this.endpoint, {
			method: 'POST',
			headers: headers,
			body: jsonData
		})
		.then(response => response.json())
		.then(responseData => {
			console.log("Resposta da API da OpenAI: ", responseData);
			console.log(responseData.choices);
			if (responseData.choices && responseData.choices.length > 0) {
				const content = responseData.choices[0].message.content;
				console.log("Resposta gerada com sucesso");
				return content;
			} else {
				console.log("Erro na resposta da API da OpenAI: ", error);
				return "Erro na resposta da API da OpenAI.";
			}
		})
		.catch(error => {
			console.log("Erro na chamada à API da OpenAI: " + error);
			return null;
		});
	}
}