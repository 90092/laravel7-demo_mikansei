<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>後臺 - {{ __('Register') }}</title>

        <!-- Custom fonts for this template-->
        <link href="{{ asset('css/fontawesome-free.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
        <style>
            .register-image {
                position: absolute;
                top: calc(50% - 3rem);
                left: calc(50% - 3rem);
                font-size: 6rem;
            }
        </style>
    </head>
    <body class="bg-gradient-primary">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-gradient-dark" style="position: relative;">
                            <i class="fa fa-image register-image text-white"></i>
                        </div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Create an account') }}!</h1>
                                </div>
                                <form class="user" method="POST" onsubmit="register()">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="name" id="name" placeholder="{{ __('Name') }}" required>
                                        <span class="invalid-feedback" id="name-error" role="alert" aria-hidden="true"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="{{ __('E-Mail Address') }}" required>
                                        <span class="invalid-feedback" id="email-error" role="alert" aria-hidden="true"></span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="{{ __('Password') }}" required>
                                            <span class="invalid-feedback" id="password-error" role="alert" aria-hidden="true"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Register') }}
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('admin.login') }}">{{ __('Already have an account') }}? {{ __('Login') }}!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade text-center" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card-body">
                            <p class="card-text">{{ __('An error occurred, please try again.') }}</p>
                        </div>
                        <button type="button" class="btn btn-secondary text-end" data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
        <script type="text/javascript">
            let alertModal = document.getElementById('alertModal');

            $(function () {
                $('input').change(function () {
                    this.value = $.trim(this.value);
                });
            });

            function register() {
                event.preventDefault();
                $('input').removeClass('is-invalid');
                let formData = new FormData(event.target);
                $.ajax({
                    url: '{{ route("admin.register") }}',
                    method: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (result) {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '註冊成功，導向登入頁面。';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            location.href = '{{ route("admin.login") }}';
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == '422') {
                            let errors = jqXHR.responseJSON.errors;
                            for (let index in errors) {
                                console.log(index, errors[index]);
                                $('#' + index).addClass('is-invalid');
                                $('#' + index + '-error').html('<strong>' + errors[index] + '</strong>');
                            }
                        } else {
                            alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                            $(alertModal).modal('show');
                        }
                    }
                });
            }
        </script>
    </body>
</html>