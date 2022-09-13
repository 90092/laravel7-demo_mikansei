@extends('front.layouts')

@section('css')
    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        .form-inline .form-control {
            display: inline-block;
            width: auto;
            vertical-align: middle;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px;
        }

        .form-row > .col {
            padding-left: 5px;
            padding-right: 5px;
        }

        label {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('content')
    @include('front.header')

    <!-- Product section-->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card my-5">
                        <div class="card-header">{{ __('Register') }}</div>
                        <div class="card-body">
                            <form method="POST" onsubmit="register()">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        <span class="invalid-feedback" id="name-error" role="alert" aria-hidden="true"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        <span class="invalid-feedback" id="email-error" role="alert" aria-hidden="true"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">

                                        <span class="invalid-feedback" id="password-error" role="alert" aria-hidden="true"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade text-center" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-body">
                        <p class="card-text">{{ __('An error occurred, please try again.') }}</p>
                    </div>
                    <button type="button" class="btn btn-secondary text-end" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
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
                url: '{{ route("register") }}',
                method: 'post',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    console.log(result);
                    // alertModal.getElementsByClassName('card-text')[0].innerText = result.message;
                    // $(alertModal).modal('show');
                    // alertModal.addEventListener('hidden.bs.modal', function (event) {
                    //     location.href = '{{ route("login") }}';
                    // });
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
@endsection