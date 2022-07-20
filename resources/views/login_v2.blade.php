


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>ERP Inventory - Login</title>
    <link rel="icon" type="image/png" href="/icon/favicon.png"/>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="{{ asset('/updated/plugins/fontawesome-free/css/all.min.css') }}">
	</head>
	
	<body>
		
  <div class="bg-box">
    <div class="bg1"></div>
    <div class="bg2"></div>
  </div>
  <div class="form">
    <h3 style="margin-bottom: 3%; margin-top: 0; text-align: center;">Athena<span style="font-weight: 900;">ERP</span> Inventory</h3>
    <div style="text-align: center; color: #CE1E09; font-size: 12pt; margin: 8px;">
      @if($errors->any())
      {!! $errors->first() !!}
      @endif
    </div>
    <form role="form" method="POST" action="/login_user">
      @csrf
    <label for="account">Username</label>
    <div class="input-box">
      <input type="text" placeholder="Username" value="{{ old('email') }}" name="email" spellcheck="false" required/>
    </div>
    <label for="password">Password</label>
    <div class="input-box">
      <input type="password" placeholder="Password" name="password" spellcheck="false" required>
    </div>
    <button type="submit" style="background-color: #3c8dbc;" class="send-btn" name="login">SIGN IN</button>
  </div>


  
  <style>
    @font-face { font-family: 'Montserrat'; src: url({{ asset('font/Montserrat/Montserrat-Bold.ttf') }}); } 
    *:not(i):not(.fa){
      font-family: 'Montserrat' !important;
    }

    * {
      box-sizing: border-box;
    }
    
    html {
      font-size: calc(100vw / 1600 * 100);
    }
    @media (max-width: 768px) {
      html {
        font-size: calc(130vw / 768 * 100);
      }
      body{
        padding: 10px !important;
      }
      .form{
        width: 500px !important;
      }
      .form label, .form input{
        font-size: 0.23rem !important;
      }
    }

    @media (max-width: 575.98px) {
      body{
        padding: 10px !important;
      }
      .form label, .form input{
        font-size: 0.23rem !important;
      }
    }
		@media (max-width: 767.98px) {
      body{
        padding: 10px !important;
      }
      .form label, .form input{
        font-size: 0.23rem !important;
      }
    }

    
    body {
      margin: 0;
      min-height: 100vh;
      padding: 0.5rem 1rem;
      display: flex;
      align-items: center;
      font-size: 0.2rem;
      position: relative;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;
      z-index: 1;
    }
    
    .bg-box {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #0f6eb5;
      z-index: -1;
      display: flex;
      overflow: hidden;
    }
    .bg-box div {
      flex: 1;
      margin: 0 0.3rem;
      background-repeat: no-repeat;
      background-size: 100% auto;
      border-radius: 0.5rem;
      -webkit-border-radius: 45px;
    }
    .bg-box .bg1 {
      background-image: url({{ asset('/img/img1.png') }});
      background-position: top center;
      -webkit-animation: fadeinBottom 1s both;
              animation: fadeinBottom 1s both;
    }
    .bg-box .bg2 {
      background-image: url({{ asset('/img/img2.png') }});
      background-position: bottom center;
      -webkit-animation: fadeinTop 1s 0.3s both;
              animation: fadeinTop 1s 0.3s both;
    }
    
    .form {
      margin: auto;
      width: 5rem;
      padding: 0.4rem;
      color: #07417d;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 1rem 0 1rem 0;
      -webkit-backdrop-filter: blur(10px);
              backdrop-filter: blur(10px);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }
    .form label {
      display: block;
      font-size: 0.16rem;
      color:  #f4f6f7 ;
    }
    .form input {
      -webkit-appearance: none;
         -moz-appearance: none;
              appearance: none;
      display: block;
      width: 100%;
      margin: 0.1rem 0 0.2rem;
      border: none;
      border-radius: 0.3rem;
      padding: 0.1rem 0.3rem;
      padding-left: 0.5rem;
      font-family: inherit;
    }
    .form input:focus, .form input:focus-within {
      outline: none;
      box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.5);
    }

    .form .input-box {
      position: relative;
    }

    .form .prefix {
      position: absolute;
      color: lightgrey;
      top: 0;
      bottom: 0;
      left: 0.2rem;
      margin: auto;
    }
    .form .switch-btn {
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      padding: 0.2rem;
      margin: auto;
      cursor: pointer;
    }
    .form .send-btn {
      display: inline-block;
      padding: 0.1rem 0.3rem;
      color: #fff;
      background-color: #286aaf;
      border-radius: 0.3rem;
      cursor: pointer;
      border-collapse: collapse;
    }
    .form .send-btn ion-icon {
      vertical-align: middle;
    }
    
    @-webkit-keyframes fadeinBottom {
      from {
        opacity: 0;
        transform: translateY(3rem);
      }
      to {
        opacity: 1;
        transform: translateY(1rem);
      }
    }
    
    @keyframes fadeinBottom {
      from {
        opacity: 0;
        transform: translateY(3rem);
      }
      to {
        opacity: 1;
        transform: translateY(3.5rem);
      }
    }
    @-webkit-keyframes fadeinTop {
      from {
        opacity: 0;
        transform: translateY(-3rem);
      }
      to {
        opacity: 1;
        transform: translateY(-1rem);
      }
    }
    @keyframes fadeinTop {
      from {
        opacity: 0;
        transform: translateY(-3rem);
      }
      to {
        opacity: 1;
        transform: translateY(-3.5rem);
      }
    }
      </style>
    
    

   </body>
</html>