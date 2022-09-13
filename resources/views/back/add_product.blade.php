@extends('back.layouts')

@section('style')
    <style>
        .img-content {
            display: inline-block;
            position: relative;
        }

        .img-thumbnail {
            max-width: 200px;
            max-height: 200px;
        }

        .img-upload {
            width: 150px;
            height: 150px;
            display: inline-flex;
            vertical-align: middle;
        }

        .img-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 1;
        }

        .upload-image-icon {
            position: absolute;
            top: calc(50% - 0.7rem);
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
        }

        .upload-plus-icon {
            position: absolute;
            top: 28%;
            right: 34%;
        }

        .upload-text {
            position: absolute;
            top: calc(50% + 0.75rem);
            left: 50%;
            transform: translate(-50%, -50%);
            white-space: nowrap;
        }

        .upload-remove-icon {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>
    <!-- Content Row -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 offset-sm-1 col-form-label">{{ __('Product Name') }} <sup class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" id="name" value="" required>
                                <div class="invalid-feedback" id="name_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 offset-sm-1 col-form-label">{{ __('Price') }} <sup class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="price" id="price" value="0" min="0" required>
                                <div class="invalid-feedback" id="price_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="discount" class="col-sm-2 offset-sm-1 col-form-label">{{ __('Discount') }}</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="discount" id="discount" value="0" min="0">
                                <div class="invalid-feedback" id="discount_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="stock" class="col-sm-2 offset-sm-1 col-form-label">{{ __('Stock') }} <sup class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="stock" id="stock" value="0" min="0" required>
                                <div class="invalid-feedback" id="stock_error"></div>
                            </div>
                        </div>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-2 offset-sm-1 float-sm-left pt-0">{{ __('Category') }}</legend>
                            <div class="col-sm-8">
                                @foreach ($categories as $index => $category)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="categories[]" id="category{{ $index }}" value="{{ $category->id }}">
                                    <label class="form-check-label" for="category{{ $index }}">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </fieldset>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 offset-sm-1" for="description">{{ __('Product Description') }}</label>
                            <div class="col-sm-10 offset-sm-1">
                                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                <div class="invalid-feedback" id="description_error"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 offset-sm-1 col-form-label">{{ __('Product Images') }}</label>
                            <div class="col-sm-10 offset-sm-1" id="upload_img">
                                <div class="img-content m-1" title="{{ __('Upload Image') }}">
                                    <input type="file" accept="image/jpeg,image/png" name="pic" class="img-upload-input" multiple>
                                    <span class="img-upload img-thumbnail">
                                        <i class="fa fa-image upload-image-icon"></i>
                                        <i class="fa fa-plus upload-plus-icon"></i>
                                        <div class="upload-text">{{ __('Upload Image') }}</div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                <button type="button" class="btn btn-secondary" onclick="javascript:history.back()">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                    </form>
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
        let alertModal = document.getElementById('alertModal');
        let images = [];

        $(function () {
            $('input[type="text"]').change(function () {
                this.value = $.trim(this.value);
            });
            $('input[type="number"]').change(function () {
                if (this.value == '') {
                    this.value = 0;
                }
            });
        });

        $('input[type="file"]').on('change', function() {
            if (images.length + this.files.length > 10) {
                alertModal.getElementsByClassName('card-text')[0].innerText = '產品圖片不可超過十張，請重新操作。';
                $(alertModal).modal('show');
                $(this).val('');
                return false;
            }

            let container = $('#upload_img');
            let repeated = [];
            for (let i=0; i<this.files.length; i++) {
                let file = this.files[i];
                if (images.some(img => img.name == file.name)) {
                    repeated.push(file.name);
                } else {
                    images.push(file);
                    let img = $('<div class="img-content m-1"><i class="fa fa-times-circle upload-remove-icon"></i></div>');
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        img.append('<img src="' + e.target.result + '" class="img-thumbnail" data-name="' + file.name + '">');
                        container.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
            if (repeated.length > 0) {
                alert(repeated.join('、') + ' 已有選取相同名稱的檔案。');
            }
            $(this).val('');
        });

        $('#upload_img').on('click', '.upload-remove-icon', function() {
            let img = $(this).closest('.img-content');
            let file_name = $(this).siblings('img').data('name');
            images.forEach(function (image, i) {
                if (image.name == file_name) {
                    images.splice(i, 1);
                }
            });
            img.remove();
        });

        $('form').on('submit', function() {
            event.preventDefault();
            $("[id$='_error']").hide();
            let formData = new FormData(this);
            images.forEach(function (image) {
                formData.append('images[]', image);
            });
            $.ajax({
                url: "{{ route('admin.add_product') }}",
                method: 'post',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (result.status == 'success') {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("Added successfully.") }}';
                        $(alertModal).modal('show');
                        $(alertModal).on('hidden.bs.modal', function (event) {
                            location.href = "{{ route('admin.products') }}";
                        });
                    } else {
                        alertModal.getElementsByClassName('card-text')[0].innerText = result.message;
                        $(alertModal).modal('show');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    if (jqXHR.status == 413) {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '檔案過大，請移除或壓縮檔案後重新嘗試。';
                        $(alertModal).modal('show');
                    } else if (jqXHR.status == 419) {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '頁面過期，請重新整理。';
                        $(alertModal).modal('show');
                    } else if (jqXHR.status == 422) {
                        let errors = jqXHR.responseJSON.errors;
                        if ('pid' in errors) {
                            alertModal.getElementsByClassName('card-text')[0].innerText = '欲修改的產品不存在，請重新整理頁面。';
                            $(alertModal).modal('show');
                        }
                        for (const key in errors) {
                            $('#' + key + '_error').text(errors[key]).show();
                        }
                        $('html, body').animate({ scrollTop: 0 });
                    } else {
                        alertModal.getElementsByClassName('card-text')[0].innerText = '{{ __("An error occurred, please try again.") }}';
                        $(alertModal).modal('show');
                    }
                }
            });
        });
    </script>
@endsection