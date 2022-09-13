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

        .carousel-item {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .carousel-img {
            width: 100%;
            height: 60vh;
            object-fit: contain;
        }
    </style>
@endsection

@section('content')
    @include('front.header')

    <!-- Product section-->
    <section class="py-5">
        <!-- <nav aria-label="breadcrumb" style="padding: 0 7vw;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav> -->
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    @if (count($product->images) > 0)
                    <div class="carousel slide mb-5 mb-md-0" id="carousel" data-bs-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($product->images as $index => $image)
                            <li data-bs-target="#carousel" data-bs-slide-to="{{ $index }}" @if($index==0) class="active" @endif></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach ($product->images as $index => $image)
                            <div class="carousel-item @if($index==0) active @endif">
                                <img src="{{ $image->url }}" class="d-block carousel-img" alt="{{ $product->name . ($index+1) }}">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @else
                    <img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." />
                    @endif
                </div>
                <div class="col-md-6">
                    @if (count($product->categories) > 0)
                    <div class="small mb-1">
                        @foreach ($product->categories as $category)
                        <div class="badge border border-dark bg-dark me-1 mb-1">{{ $category->name }}</div>
                        @endforeach
                    </div>
                    @endif
                    <h1 class="display-5 fw-bolder">{{ $product->name }}</h1>
                    <div class="fs-5 mb-5">
                        @if ($product->discount > 0)
                        <span class="text-decoration-line-through text-muted">NT${{ $product->price }}</span>
                        <span>NT${{ $product->price - $product->discount }}</span>
                        @else
                        <span>NT${{ $product->price }}</span>
                        @endif
                    </div>
                    @if (is_null($product->detail))
                    <p class="lead text-black-50">尚無介紹。</p>
                    @else
                    <p class="lead">{!! nl2br(e($product->detail)) !!}</p>
                    @endif
                    @if ($product->stock > 0)
                    <div class="d-flex">
                        <input class="form-control text-center me-3" id="inputQuantity" type="number" value="1" style="max-width: 3rem" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="button">
                            <i class="bi-cart-fill me-1"></i>
                            {{ __('Add to cart') }}
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection