@extends('front.layouts')

@section('css')
    <style>
        .card-img-top {
            height: 150px;
            display: flex;
            background: rgba(0, 0, 0, 0.03) url(https://dummyimage.com/450x300/dee2e6/6c757d.jpg) no-repeat center / cover;
        }

        .card-img-top > img {
            margin: auto;
        }

        .pagination > li > a
        {
            background-color: transparent;
            color: #212529;
        }

        .pagination > li > a:focus,
        .pagination > li > a:hover,
        .pagination > li > span:focus,
        .pagination > li > span:hover
        {
            color: #5a5a5a;
            background-color: #eee;
            border-color: #ddd;
        }

        .pagination > li > a:focus,
        .pagination > li > span:focus
        {
            box-shadow: 0 0 0 0.25rem rgba(33, 37, 41, 0.5);
        }

        .pagination > .active > a
        {
            color: white;
            background-color: var(--bs-body-color) !Important;
            border: solid 1px var(--bs-body-color) !Important;
        }

        .pagination > .active > a:hover
        {
            background-color: var(--bs-body-color) !Important;
            border: solid 1px var(--bs-body-color);
        }

        .page-item.active .page-link
        {
            pointer-events: none;
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

    <!-- Section-->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-header">{{ __('Categories') }}</div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><a href="javascript:changeCategory('All')">{{ __('All') }}</a></li>
                                @foreach ($categories as $category)
                                <li class="mb-1"><a href="javascript:changeCategory('{{ $category->name }}')">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4">
                        @foreach ($products as $product)
                        <div class="col mb-4" category="{{ $product->category_names }}">
                            <div class="card h-100">
                                @if ($product->discount > 0)
                                <!-- Sale badge-->
                                <div class="badge bg-dark text-white border position-absolute" style="top: 0.5rem; right: 0.5rem">{{ __('Sale') }}</div>
                                @endif
                                <!-- Product image-->
                                <div class="card-img-top" 
                                    @if (count($product->images) > 0)
                                    style="background-image: url({{ $product->images[0]->url }});"
                                    @endif
                                ></div>
                                <!-- Product details-->
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder">{{ $product->name }}</h5>
                                        @if (count($product->categories) > 0)
                                        <!-- Product categories-->
                                        <div class="d-flex justify-content-center small flex-wrap mb-2">
                                            @foreach ($product->categories as $category)
                                            <div class="badge border border-dark text-dark me-1 mb-1">{{ $category->name }}</div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <!-- Product price-->
                                        @if ($product->discount > 0)
                                        <span class="text-muted text-decoration-line-through">NT${{ $product->price }}</span>
                                        NT${{ $product->price - $product->discount }}
                                        @else
                                        NT${{ $product->price }}
                                        @endif
                                    </div>
                                </div>
                                <!-- Product actions-->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    @if ($product->stock > 0)
                                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="/item/{{ $product->id }}">檢視詳細</a></div>
                                    @else
                                    <div class="text-center"><button class="btn btn-dark mt-auto" disabled>完售</button></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div @if(count($products)>0) class="d-none" @endif id="no_goods">暫無商品。</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function () {
            if (location.hash) {
                changeCategory(location.hash.substring(1));
            }
        });

        function changeCategory(category) {
            $('.col-lg-9 > .row > .col.mb-4').show();
            if (category !== 'All') {
                $('.col-lg-9 > .row > .col.mb-4').not('[category*="\\\"' + category + '\\\""]').hide();
            }

            if ($('.col-lg-9 > .row > .col.mb-4:visible').length > 0) {
                $('#no_goods').addClass('d-none');
            } else {
                $('#no_goods').removeClass('d-none');
            }
        }
    </script>
@endsection