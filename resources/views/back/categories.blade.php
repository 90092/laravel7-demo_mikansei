@extends('back.layouts')

@section('style')
    <style>
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Category List') }}</h1>
        <button type="button" class="btn btn-primary btn-icon-split my-1" data-toggle="modal" data-target="#editModal">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">{{ __('Add Category') }}</span>
        </button>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card mb-4 shadow">
                <div class="card-body" id="category_list">
                    @if (count($categories) > 0)
                    <table class="table table-borderless table-striped w-100 text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:70%">{{ __('Category Name') }}</th>
                                <th>{{ __('Function') }}</th>
                            </tr>
                        </thead>
                        @foreach ($categories as $categoryChunk)
                        <tbody class="d-none">
                            @foreach ($categoryChunk as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button class="btn text-primary" title="{{ __('Edit') }}" data-toggle="modal" data-target="#editModal" data-cid="{{ $category->id }}" data-name="{{ $category->name }}">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button class="btn text-danger" title="{{ __('Delete') }}" data-toggle="modal" data-target="#deleteModal" data-cid="{{ $category->id }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endforeach
                    </table>
                    @else
                    尚無分類。
                    @endif
                </div>
            </div>
        </div>
        @if (count($categories) > 0)
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="edit_form">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('Category Name') }}</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary text-end">{{ __('Submit') }}</button>
                            <button type="button" class="btn btn-secondary text-end" data-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-danger"><h5 class="modal-title">{{ __('Warning') }}</h5></div>
                <div class="modal-body">{{ __('Are you sure to delete this category?') }}</div>
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
        let editModal = document.getElementById('editModal');
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
            if ($('#category_list').find('tbody').length > 0) {
                $('#category_list').find('tbody')[now_page].classList.remove('d-none');
            }

            $('input').change(function () {
                this.value = $.trim(this.value);
            });
        });

        $('#editModal').on('show.bs.modal', function (event) {
            let cid = $(event.relatedTarget).data('cid');
            if (cid) {
                $('#name').val($(event.relatedTarget).data('name'));
                $('#edit_form').attr('onsubmit', 'return editCategory(' + cid + ');');
                $('button[type="submit"]').text('{{ __("Modify") }}');
            } else {
                $('#name').val('');
                $('#edit_form').attr('onsubmit', 'return addCategory();');
                $('button[type="submit"]').text('{{ __("Add") }}');
            }
            $("[id$='_error']").hide();
        });

        function addCategory() {
            $("[id$='_error']").hide();
            $.ajax({
                url: '{{ route("admin.add_category") }}',
                method: 'post',
                data: {
                    'name': $('#name').val(),
                    'api_token': '{{ auth("admin")->user()->api_token }}'
                },
                success: function (result) {
                    if (result.status == 'success') {
                        $(editModal).modal('hide');
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("The category has been added.") }}';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            addr.searchParams.set('p', $('#category_list tbody').index($('#category_list tbody:visible')));
                            location.href = addr.toString();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);

                    if (jqXHR.status == 422) {
                        let errors = jqXHR.responseJSON.errors;
                        if ('name' in errors) {
                            $('#name_error').text(errors.name).show();
                        }
                    } else {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                        $(alertModal).modal('show');
                    }
                }
            });
            return false;
        }

        function editCategory(cid) {
            $("[id$='_error']").hide();
            $.ajax({
                url: '{{ route("admin.edit_category") }}',
                method: 'post',
                data: {
                    'cid': cid,
                    'name': $('#name').val(),
                    'api_token': '{{ auth("admin")->user()->api_token }}'
                },
                success: function (result) {
                    if (result.status == 'success') {
                        $(editModal).modal('hide');
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("The category has been updated.") }}';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            addr.searchParams.set('p', $('#category_list tbody').index($('#category_list tbody:visible')));
                            location.href = addr.toString();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);

                    if (jqXHR.status == '422') {
                        let errors = jqXHR.responseJSON.errors;
                        if ('cid' in errors) {
                            $(editModal).modal('hide');
                            alertModal.getElementsByClassName('card-text')[0].innerText = '欲修改的分類不存在，請重新整理頁面。';
                            $(alertModal).modal('show');
                        }
                        if ('name' in errors) {
                            $('#name_error').text(errors.name).show();
                        }
                    } else {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                        $(alertModal).modal('show');
                    }
                }
            });
            return false;
        }

        $('#deleteModal').on('show.bs.modal', function (event) {
            let cid = $(event.relatedTarget).data('cid');
            this.querySelector('button.btn-danger').setAttribute('onclick', 'deleteCategory(' + cid + ')');
        });

        function deleteCategory(cid) {
            $.ajax({
                url: '{{ route("admin.delete_category") }}',
                method: 'post',
                data: {
                    'cid': cid,
                    'api_token': '{{ auth("admin")->user()->api_token }}'
                },
                success: function (result) {
                    if (result.status == 'success') {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("Deleted successfully.") }}';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            addr.searchParams.set('p', $('#category_list tbody').index($('#category_list tbody:visible')));
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
            let visible_page = $('#category_list').find('tbody:visible');
            let prev_page = visible_page.prev('tbody');
            if (prev_page.length > 0) {
                visible_page.addClass('d-none');
                prev_page.removeClass('d-none');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('#next_btn').parent().removeClass('disabled');

                if (prev_page.prev('tbody').length == 0) {
                    $('#prev_btn').parent().addClass('disabled');
                }
            } else {
                alertModal.getElementsByClassName('card-text')[0].innerText = '已經是第一頁。';
                $(alertModal).modal('show');
            }
        }

        function nextPage() {
            let visible_page = $('#category_list').find('tbody:visible');
            let next_page = visible_page.next('tbody');
            if (next_page.length > 0) {
                visible_page.addClass('d-none');
                next_page.removeClass('d-none');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('#prev_btn').parent().removeClass('disabled');

                if (next_page.next('tbody').length == 0) {
                    $('#next_btn').parent().addClass('disabled');
                }
            } else {
                alertModal.getElementsByClassName('card-text')[0].innerText = '已經是最後一頁。';
                $(alertModal).modal('show');
            }
        }
    </script>
@endsection