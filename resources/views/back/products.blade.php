@extends('back.layouts')

@section('style')
    <style>
        .badge {
            font-size: 0.8em;
        }

        @media (max-width: 767.5px) {
            .table-header {
                display: none;
            }

            .card-body hr:first-of-type {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Product List') }}</h1>
        <a href="{{ route('admin.add_product') }}" class="btn btn-primary btn-icon-split my-1">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">{{ __('Add Product') }}</span>
        </a>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card mb-4 shadow">
                <div class="card-body" id="product_list">
                    @if (count($products) > 0)
                    <div class="row align-items-center mb-4 text-center table-header">
                        <div class="col-md-2">{{ __('Product Name') }}</div>
                        <div class="col-md-3">{{ __('Category') }}</div>
                        <div class="col-md-4">{{ __('Product Description') }}</div>
                        <div class="col-md-2">{{ __('Price') }}</div>
                        <div class="col-md-1"></div>
                    </div>
                    @foreach ($products as $productChunk)
                    <span class="d-none">
                        @foreach ($productChunk as $product)
                        <hr>
                        <div class="row align-items-center mb-4 text-center">
                            <div class="col-md-2 my-2">
                                @if (count($product->images) > 0)
                                <img class="img-fluid mb-2" src="{{ $product->images[0]->url }}" alt="..." />
                                @else
                                <img class="img-fluid mb-2" src="https://dummyimage.com/300x250/dee2e6/6c757d.jpg" alt="..." />
                                @endif
                                <div>{{ $product->name }}</div>
                            </div>
                            <div class="col-md-3 my-2">
                                @if (count($product->categories) > 0)
                                    @foreach ($product->categories as $category)
                                    <span class="badge badge-primary">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                無
                                @endif
                            </div>
                            <div class="col-md-4 my-2">{!! nl2br(e($product->detail)) !!}</div>
                            <div class="col-md-2 my-2">
                                @if ($product->discount > 0)
                                <del class="text-gray-500">NT${{ $product->price }}</del> NT${{ $product->price - $product->discount }}
                                @else
                                NT${{ $product->price }}
                                @endif
                            </div>
                            <div class="col-md-1 my-2">
                                <a href="{{ route('admin.edit_product', ['pid' => $product->id]) }}" class="btn text-primary" title="{{ __('Edit') }}">
                                    <i class="far fa-edit"></i>
                                </a>
                                <button class="btn text-danger" title="{{ __('Delete') }}" data-toggle="modal" data-target="#checkModal" data-pid="{{ $product->id }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </span>
                    @endforeach
                    @else
                    尚無產品。
                    @endif
                </div>
            </div>
        </div>
        @if (count($products) > 0)
        <div class="col-12 mb-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="javascript:prevPage()" id="prev_btn">{{ __('Previous Page') }}</a></li>
                    <li class="page-item"><a class="page-link" href="javascript:nextPage()" id="next_btn">{{ __('Next Page') }}</a></li>
                </ul>
            </nav>
        </div>
        @endif
    </div>

    <!-- Check Modal-->
    <div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-danger"><h5 class="modal-title">{{ __('Warning') }}</h5></div>
                <div class="modal-body">{{ __('Are you sure to delete this product?') }}</div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">{{ __('Delete') }}</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
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
@endsection

@section('javascript')
    <script type="text/javascript">
        const total_page = {{ $page_count }};
        let alertModal = document.getElementById('alertModal');
        let addr = new URL(location.href);

        $(function () {
            let now_page = 0;
            if (addr.searchParams.has('p')) {
                now_page = addr.searchParams.get('p');
            }
            if (now_page >= total_page) {
                $('#next_btn').parent().addClass('disabled');
                now_page = total_page;
            }
            if (now_page == 0) {
                $('#prev_btn').parent().addClass('disabled');
            }
            if ($('#product_list').children('span').length > 0) {
                $('#product_list').children('span')[now_page].classList.remove('d-none');
            }
        });

        $('#checkModal').on('show.bs.modal', function (event) {
            let pid = $(event.relatedTarget).data('pid');
            this.querySelector('button.btn-danger').setAttribute('onclick', 'deleteProduct(' + pid + ')');
        });

        function deleteProduct(pid) {
            $.ajax({
                url: '{{ route("admin.delete_product") }}',
                method: 'post',
                data: {
                    'pid': pid,
                    'api_token': '{{ auth("admin")->user()->api_token }}'
                },
                success: function (result) {
                    if (result.status == 'success') {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("Deleted successfully.") }}';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            addr.searchParams.set('p', $('#product_list > span').index($('#product_list > span:visible')));
                            location.href = addr.toString();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);

                    alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                    $(alertModal).modal('show');
                }
            });
        }

        function prevPage() {
            let visible_page = $('#product_list').children('span:visible');
            let prev_page = visible_page.prev('span');
            if (prev_page.length > 0) {
                visible_page.addClass('d-none');
                prev_page.removeClass('d-none');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('#next_btn').parent().removeClass('disabled');

                if (prev_page.prev('span').length == 0) {
                    $('#prev_btn').parent().addClass('disabled');
                }
            } else {
                alertModal.getElementsByClassName('card-text')[0].innerText = '已經是第一頁。';
                $(alertModal).modal('show');
            }
        }

        function nextPage() {
            let visible_page = $('#product_list').children('span:visible');
            let next_page = visible_page.next('span');
            if (next_page.length > 0) {
                visible_page.addClass('d-none');
                next_page.removeClass('d-none');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('#prev_btn').parent().removeClass('disabled');

                if (next_page.next('span').length == 0) {
                    $('#next_btn').parent().addClass('disabled');
                }
            } else {
                alertModal.getElementsByClassName('card-text')[0].innerText = '已經是最後一頁。';
                $(alertModal).modal('show');
            }
        }
    </script>
@endsection