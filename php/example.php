<?php
$apiKey = (filter_input(INPUT_POST, "apiKey", FILTER_UNSAFE_RAW)) ? filter_input(INPUT_POST, "apiKey", FILTER_UNSAFE_RAW) : '' ;
$temper = (filter_input(INPUT_POST, "temper", FILTER_UNSAFE_RAW)) ? filter_input(INPUT_POST, "temper", FILTER_UNSAFE_RAW) : '' ;
$prompt = (filter_input(INPUT_POST, "prompt", FILTER_UNSAFE_RAW)) ? filter_input(INPUT_POST, "prompt", FILTER_UNSAFE_RAW) : '' ;
?>
<!DOCTYPE html>
<html>
<head>
	<title>IA</title>
	<meta charset="utf-8">
	
	<!-- CSS / Bootstrap 5.3.0 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

	<style type="text/css">
		section {
			padding: 2em 0;
		}
		#resposta {
			background: #dedede;
			border: 1px solid #ccc;
		}
	</style>
</head>
<body>
	<section>
		<div class="container">
			<h1>IA</h1>
			<form action="" method="post">
				<div class="form-group mt-3">
					<label for="apiKey" class="form-label">Chave da API:</label>
					<input type="text" id="apiKey" name="apiKey" class="form-control" value="<?php echo $apiKey; ?>">
					<p><small>Saiba como gerar sua chave de api na OpenAI <a href="https://help.openai.com/en/articles/4936850-where-do-i-find-my-api-key" target="_blank">clicando aqui</a>.</small></p>
				</div>
				<div class="form-group mt-3">
					<label for="temper">Temperatura:</label>
					<select name="temper" id="temper" class="form-control">
						<option value="0.5">Neutro</option>
						<option value="0.8">Criativo</option>
						<option value="0.2">Especialista</option>
					</select>
				</div>
				<div class="form-group mt-3">
					<label for="prompt">Prompt:</label>
					<textarea class="form-control" id="prompt" name="prompt" rows="5"><?php echo $prompt; ?></textarea>
				</div>
				<button type="submit" class="btn btn-primary" id="gerar-btn">Enviar</button>
			</form>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="form-group">
				
				<h3>Resposta:</h3>
<textarea class='form-control' id='resposta' name='resposta' rows='18'>
<?php
include 'gptfire.php';

// Verifica se o formulário foi enviado
if ($apiKey != "") {
	$gptFire = new GPTFire($apiKey);
	$resposta = $gptFire->generate($prompt, '' , $temper);
	echo $resposta;
} else {
	echo "Preencha as informações acima e clique em 'Enviar' para gerar uma resposta da IA";
}
?>
</textarea>
				<div class='mt-3'>
					<button class='btn btn-sm btn-light' id='copy-btn'>Copiar resposta</button>
				</div>
			</div>
		</div>
	</section>

	<script type="text/javascript">
		const gerarBTN = document.getElementById('gerar-btn');
		const resposta = document.getElementById('resposta');
		const copyBTN = document.getElementById('copy-btn');

		gerarBTN.addEventListener('click', () => {
			resposta.value = "Carregando ...";
		});

		copyBTN.addEventListener("click", () => {
			resposta.select();
			document.execCommand("copy");
		});
	</script>

</body>
</html>