@extends('layout', [
    'namePage' => 'Import from E-commerce Website',
    'activePage' => 'import_from_ecommerce',
])

@section('content')
    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if(session()->has('success'))
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    {!! session()->get('success') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                    {!! session()->get('error') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title mb-3">Import images from E-Commerce Website</h6>
                        <form action="/import_images" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="customFile" name="import_zip" required>
                                <label class="custom-file-label" for="customFile">Choose File</label>
                            </div>
                            <button class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function (){
            $(".custom-file-input").change(function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
@endsection