<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Chatting</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/app.css">
</head>
<body>
	<div id="jumbotron">
		<h1 class="text-center">Laravel Chat App</h1>
	</div>
	<hr>
	<div class="container m-8">
		<div class="row m-8 p-5">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<div id="messageOutput"></div>
						<hr>
						<form id="chatForm">
							<div class="form-group mb-3">
								<input type="text" class="form-control" id="messageElement" placeholder="type here">
							</div>
							<button type="submit" class="btn btn-success">Send</button>
						</form>
					</div>
				</div>
			</div>
		</div> 
	</div>
	<script src="./js/app.js"></script>
</body>
</html>