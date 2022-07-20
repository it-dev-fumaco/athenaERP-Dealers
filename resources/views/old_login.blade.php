<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>ERP Inventory</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		{{--  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">  --}}
		
		<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
		<link rel="icon" href="erp.png" type="image/png"/>

		<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->  <!-- CSS -->
		<link rel="stylesheet" href="/login_css/css/fonts/fonts-login.css">
		<link rel="stylesheet" href="/login_css/assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/login_css/assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/login_css/assets/css/form-elements.css">
		<link rel="stylesheet" href="/login_css/assets/css/style.css">

		<!-- Favicon and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="login_css/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="login_css/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="login_css/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="login_css/assets/ico/apple-touch-icon-57-precomposed.png">
	</head>
	
	<body style="background-color:#245b91;">
		<div class="top-content">
			<div class="inner-bg">
				<div class="container">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2 text"><h1>&nbsp;</h1>
							<div class="description"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-sm-offset-3 form-box">
							<div class="form-top">
								<div class="form-top-left">
									<h2><strong>ERP</strong>Inventory</h2>
									@if($errors->any())
									{!! $errors->first() !!}
									@endif
								</div>
								<div class="form-top-right">
									<i class="fa fa-lock"></i>
								</div>
							</div>
							<div class="form-bottom">
								<form role="form" method="POST" action="/login_user">
									@csrf
									<div class="form-group">
										<input type="text" placeholder="Username..." value="{{ old('email') }}" class="form-username form-control" name="email">
									</div>
									<div class="form-group">
										<input type="password" placeholder="Password..." class="form-password form-control"  name="password">
									</div>
									<button type="submit" style="background-color: #3c8dbc;" class="btn btn-primary" name="login">SIGN IN</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   </body>
</html>