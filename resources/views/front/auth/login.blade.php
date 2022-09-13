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
                <div class="col-sm-10">
                    <div class="card mx-5">
                        <div class="card-header">{{ __('Login') }}</div>
                        <div class="card-body">
                            <form method="POST" onsubmit="login()">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Login') }}
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

        function login() {
            event.preventDefault();
            let formData = new FormData(event.target);
            $.ajax({
                url: '{{ route("login") }}',
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (result) {
                    alertModal.getElementsByClassName('card-text')[0].innerText = '登入成功。';
                    $(alertModal).modal('show');
                    alertModal.addEventListener('hidden.bs.modal', function (event) {
                        location.href = '{{ route("list") }}';
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);

                    if (jqXHR.status == '422') {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '帳號密碼不正確。';
                        $(alertModal).modal('show');
                    } else {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                        $(alertModal).modal('show');
                    }
                }
            });
        }
    </script>
@endsection