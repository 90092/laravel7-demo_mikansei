@extends('front.layouts')

@section('css')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        a {
            color: var(--bs-body-color);
            text-decoration: none;
        }
        
        a:hover {
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    @include('front.header')

    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-3">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body bg-light">
                            <h5 class="card-title">{{ __('Cart') }}</h5>
                            <hr>
                            @for ($i=1; $i<4; $i++)
                            <div class="row align-items-center">
                                <div class="col-sm-4"><img class="card-img" src="https://dummyimage.com/300x250/dee2e6/6c757d.jpg" alt="..." /></div>
                                <div class="col-sm-3 my-2 text-center">NT$120</div>
                                <div class="col-sm-4 my-2">
                                    <div class="input-group mx-auto" style="max-width: 120px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="plus(this)" @if($i==3) disabled @endif>+</button>
                                        <input type="number" class="form-control text-center" name="p{{ $i }}" value="{{ $i }}" min="1" max="3">
                                        <button class="btn btn-outline-secondary" type="button" onclick="minus(this)" @if($i==1) disabled @endif>-</button>
                                    </div>
                                </div>
                                <div class="col-sm-1 my-2 text-center">
                                    <button type="button" class="btn-close" onclick="removeProduct(this)"></button>
                                </div>
                            </div>
                            <hr>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body bg-light">
                            <h5 class="card-title">{{ __('Shipping Info') }}</h5>
                            <hr>
                            <div class="mb-3 px-md-4">
                                <label for="address" class="form-label">{{ __('Shipping Address') }}</label>
                                <input type="text" class="form-control text-truncate" name="address" id="address" required>
                            </div>
                            <div class="mb-3 px-md-4">
                                <label for="phone" class="form-label">{{ __('Contact Number') }}</label>
                                <input type="text" class="form-control text-truncate" name="phone" id="phone" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-dark mt-auto">{{ __('Submit') }}</button>
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
            $('input[type="number"]').on('change', function (event) {
                changeAmount(this, this.value);
            });
        });

        function plus(item) {
            let elem = $(item).siblings('input[type="number"]')[0];
            changeAmount(elem, ++elem.value);
        }

        function minus(item) {
            let elem = $(item).siblings('input[type="number"]')[0];
            changeAmount(elem, --elem.value);
        }

        function changeAmount(elem, amount) {
            let max = parseInt(elem.getAttribute('max'));
            if (amount < 1) {
                amount = elem.defaultValue;
                alertModal.getElementsByClassName('card-text')[0].innerText = '商品數量不可低於 1。';
                $(alertModal).modal('show');
            } else if (amount > max) {
                amount = elem.defaultValue;
                alertModal.getElementsByClassName('card-text')[0].innerText = '超過庫存上限，請重新輸入。';
                $(alertModal).modal('show');
            }
            if (amount == 1) {
                $(elem).siblings()[0].disabled = false;
                $(elem).siblings()[1].disabled = true;
            } else if (amount == max) {
                $(elem).siblings()[0].disabled = true;
                $(elem).siblings()[1].disabled = false;
            } else {
                $(elem).siblings()[0].disabled = false;
                $(elem).siblings()[1].disabled = false;
            }
            elem.value = elem.defaultValue = parseInt(amount);
        }

        function removeProduct(elem) {
            let row = $(elem).closest('.row')[0];
            row.previousElementSibling.remove();
            row.remove();
            // remove json too
            // check if no item direct to home
        }
    </script>
@endsection