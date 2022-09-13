@extends('back.layouts')

@section('style')
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .dataTables_wrapper label {
            margin-top: .5rem;
        }

        .table th, .table td {
            vertical-align: middle !important;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">{{ __('Order List') }}</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Member Name') }}</th>
                            <th>{{ __('Order Detail') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Shipping Address') }}</th>
                            <th>{{ __('Contact Number') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->member_name }}</td>
                            <td><button class="btn btn-info" data-toggle="modal" data-target="#popupModal" data-number="{{ $order->id }}">{{ __('Check') }}</button></td>
                            <td>NT${{ $order->fee }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Order Detail') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">尚無商品紀錄。</div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <!-- Page level plugins -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        const orders = @json($orders);

        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh-HANT.json'
                }
            });
        });

        $('#popupModal').on('show.bs.modal', function (event) {
            let modal_body = $(this).find('.modal-body');
            let button = $(event.relatedTarget);
            let order_number = button.data('number');
            let order = orders.find(function (item) {
                return item.id == order_number;
            });
            let html = '';
            order.detail.forEach(function (item, index) {
                if (index > 0) {
                    html += '<hr>';
                }
                html += '<div class="row align-items-center"><div class="col-sm-4"><img class="card-img" src="https://dummyimage.com/300x250/dee2e6/6c757d.jpg" alt="..." /><div>' + item.name + '</div></div>';
                if (item.discount > 0) {
                    html += '<div class="col-sm-4 my-2"><del class="text-gray-500">NT$' + item.price + '</del> NT$' + item.price + '</div>';
                } else {
                    html += '<div class="col-sm-4 my-2">NT$' + item.price + '</div>';
                }
                html += '<div class="col-sm-4 my-2">' + item.amount + '</div></div>';
            });
            modal_body.html(html);
        });
    </script>
@endsection