@extends('layout', [
    'namePage' => 'Page Not Found',
    'activePage' => 'error_page',
])

@section('content')
    <main style="min-height: 500px;">
        <div class="container-fluid mt-5">
            <div class="row mt-5">
                <div class="col-md-8 offset-md-2">
                    <div class="row m-5">
                        <div class="col-md-4">
                            <h2 class="headline text-warning" style="font-size: 4rem; text-align: right;"> 404</h2>
                        </div>
                        <div class="col-md-8">
                            <p style="color:#001F3F !important; font-size:1.3rem !important; margin: 0;">Oops! Page not found.</p>
                            <p style="color:#58595A !important; font-size:0.95rem !important; margin: 0;">The page your are looking for might have been removed, had its name changed or is temporarily unavailable.</p><br>
                            <p style="color:#58595A !important; font-size:0.95rem !important; margin: 0;">Please contact support at it@fumaco.local or it@fumaco.com</p>
                            <a href="/" class="btn btn-primary mt-3" style="background-color: #001F3F; border: none">RETURN TO HOMEPAGE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>
        body{
            background-color: #F4F6F9;
        }
    </style>
@endsection