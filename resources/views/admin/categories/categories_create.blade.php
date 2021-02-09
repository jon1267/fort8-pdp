@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-10">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($category)) ? 'Обновление данных' : 'Введите данные' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($category)) ? route('admin.category.update', $category) : route('admin.category.store') }}"
                                   method="post" enctype="multipart/form-data">
                                @csrf
                                @if(isset($category))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group">
                                    <label for="name">Название категории на русском</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите название категории на русском языке"
                                           value="{{(isset($category->name)) ? $category->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mt-5">
                                    <label for="name_ua">Название категории на украинском</label>
                                    <input class="form-control @error('name_ua') is-invalid @enderror" type="text"
                                           id="name_ua" name="name_ua" placeholder="Введите название категории на украинском"
                                           value="{{(isset($category->name_ua)) ? $category->name_ua : old('name_ua')}}">
                                    @error('name_ua')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group  mt-5">
                                    <label for="exampleInputFile">Иконка мобильная</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('header_mobile') is-invalid @enderror"
                                                   id="header_mobile" name="header_mobile" aria-describedby="customFileInput">
                                            <label class="custom-file-label" for="customFileInput">Изображение иконки мобильной</label>
                                        </div>
                                    </div>
                                    @error('header_mobile')
                                    <!-- нада style="display: inline-block", т.к. custom-file-input где-то ставит d-none -->
                                    <span class="invalid-feedback" style="display: inline-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div>
                                        @if(isset($category) && (!empty($category->header_mobile)))
                                            <div class="form-group mt-2" id="old-image-div">
                                                <label for="old_img" >Старое изображение</label>
                                                <a href="#" onclick="event.preventDefault(); imageDelete('Category', 'header_mobile' ,'{{ $category->id }}', 'deleted_image')"
                                                   id="delete-image-button" class="badge badge-danger ml-2" title="удалить старое изображение" >&nbsp;x&nbsp;</a>
                                                <div>
                                                    <img src="{{asset('/storage/images/settings/' . $category->header_mobile) }}" width="60" alt="Image">
                                                </div>
                                            </div>
                                            <input type="hidden" id="deleted_image_model" name="deleted_image[model]" value="">
                                            <input type="hidden" id="deleted_image_field" name="deleted_image[field]" value="">
                                            <input type="hidden" id="deleted_image_id" name="deleted_image[id]" value="">
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group  mt-5">
                                    <label for="exampleInputFile">Иконка десктопная</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('header_desktop') is-invalid @enderror"
                                                   id="header_desktop" name="header_desktop" aria-describedby="customFileInput">
                                            <label class="custom-file-label" for="customFileInput">Изображение иконки десктопной</label>
                                        </div>
                                    </div>
                                    @error('header_desktop')
                                    <!-- нада style="display: inline-block", т.к. custom-file-input где-то ставит d-none -->
                                    <span class="invalid-feedback" style="display: inline-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div>
                                        @if(isset($category) && (!empty($category->header_desktop)))
                                            <div class="form-group mt-2" id="old-image-div">
                                                <label for="old_img" >Старое изображение</label>
                                                <a href="#" onclick="event.preventDefault(); imageDelete('Category', 'header_desktop' ,'{{ $category->id }}', 'another_image')"
                                                   id="delete-image-button" class="badge badge-danger ml-2" title="удалить старое изображение" >&nbsp;x&nbsp;</a>
                                                <div>
                                                    <img src="{{asset('/storage/images/settings/' . $category->header_desktop) }}" width="60" alt="Image">
                                                </div>
                                            </div>
                                            <input type="hidden" id="another_image_model" name="deleted_image1[model]" value="">
                                            <input type="hidden" id="another_image_field" name="deleted_image1[field]" value="">
                                            <input type="hidden" id="another_image_id" name="deleted_image1[id]" value="">

                                        @endif
                                    </div>

                                </div>

                                <div class="form-group mt-5">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="slider_show" name="slider_show"
                                               value="1" {{ (isset($category) && $category->slider_show == 1) ? ' checked ' : '' }}>
                                        <label class="custom-control-label" for="slider_show" >Показывать (не показывать) слайдер</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить категорию </button>
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
                                </div>
                            </form>
                            <!-- -->

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
