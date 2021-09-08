@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">Редактирование настроек</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ route('admin.settings.update', $setting) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="updated_by_id" value="{{ $userId }}">

                                <div class="form-group">
                                    <label for="analytic_code">Код аналитики</label>
                                    <textarea class="form-control @error('analytic_code') is-invalid @enderror" rows="3"
                                              id="analytic_code" name="analytic_code" placeholder="Описание товара на русском"
                                    >{{(isset($setting->analytic_code)) ? $setting->analytic_code : old('analytic_code')}}</textarea>
                                    @error('analytic_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-8 mt-5">
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
                                        @if(isset($setting) && (!empty($setting->header_mobile)))
                                            <div class="form-group mt-2" id="old_div_deleted_image">
                                                <label for="old_img" >Старое изображение</label>
                                                <a href="#" onclick="event.preventDefault(); imageDelete('Setting','header_mobile','{{$setting->id}}', 'deleted_image')" id="delete-image-button" class="badge badge-danger ml-2" title="удалить старое изображение" >&nbsp;x&nbsp;</a>
                                                <div>
                                                    <img src="{{asset('/storage/images/settings/' . $setting->header_mobile) }}" width="60" alt="Image">
                                                </div>
                                                <input type="hidden" id="deleted_image_model" name="deleted_image[model]" value="">
                                                <input type="hidden" id="deleted_image_field" name="deleted_image[field]" value="">
                                                <input type="hidden" id="deleted_image_id" name="deleted_image[id]" value="">
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group col-8 mt-5">
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
                                        @if(isset($setting) && (!empty($setting->header_desktop)))
                                            <div class="form-group mt-2" id="old_div_deleted_image1">
                                                <label for="old_img" >Старое изображение</label>
                                                <a href="#" onclick="event.preventDefault(); imageDelete('Setting','header_desktop','{{$setting->id}}', 'deleted_image1')" id="delete-image-button" class="badge badge-danger ml-2" title="удалить старое изображение" >&nbsp;x&nbsp;</a>
                                                <div>
                                                    <img src="{{asset('/storage/images/settings/' . $setting->header_desktop) }}" width="60" alt="Image">
                                                </div>
                                                <input type="hidden" id="deleted_image1_model" name="deleted_image1[model]" value="">
                                                <input type="hidden" id="deleted_image1_field" name="deleted_image1[field]" value="">
                                                <input type="hidden" id="deleted_image1_id" name="deleted_image1[id]" value="">
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group col-12 mt-5">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="slider_show" name="slider_show"
                                               value="1" {{ (isset($setting) && $setting->slider_show == 1) ? ' checked ' : '' }}>
                                        <label class="custom-control-label" for="slider_show" >Показывать (не показывать) слайдер</label>
                                    </div>
                                </div>

                                <div class="form-group col-12 mt-5">
                                    <label for="auction_comment_price">Вознаграждение за комментарий</label>
                                    <input class="form-control @error('auction_comment_price') is-invalid @enderror" type="text"
                                           id="auction_comment_price" name="auction_comment_price"
                                           value="{{(isset($setting->auction_comment_price)) ? $setting->auction_comment_price : old('auction_comment_price')}}">
                                    @error('auction_comment_price')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 mt-5">
                                    <label for="auction_register_price">Вознаграждение за регистрацию</label>
                                    <input class="form-control @error('auction_register_price') is-invalid @enderror" type="text"
                                           id="auction_register_price" name="auction_register_price"
                                           value="{{(isset($setting->auction_register_price)) ? $setting->auction_register_price : old('auction_register_price')}}">
                                    @error('auction_register_price')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 mt-5">
                                    <label for="auction_partner_price">Вознаграждение за привлечение клиента</label>
                                    <input class="form-control @error('auction_partner_price') is-invalid @enderror" type="text"
                                           id="auction_partner_price" name="auction_partner_price"
                                           value="{{(isset($setting->auction_partner_price)) ? $setting->auction_partner_price : old('auction_partner_price')}}">
                                    @error('auction_partner_price')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 mt-5">
                                    <label for="auction_product_text_ru">Аукцион дополнительное описание рус.</label>
                                    <textarea class="form-control @error('auction_product_text_ru') is-invalid @enderror" rows="1"
                                              id="auction_product_text_ru" name="auction_product_text_ru" placeholder="Описание товара на русском"
                                    >{{(isset($setting->auction_product_text_ru)) ? $setting->auction_product_text_ru : old('auction_product_text_ru')}}</textarea>
                                    @error('auction_product_text_ru')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 mt-5">
                                    <label for="auction_product_text_ua">Аукцион дополнительное описание укр.</label>
                                    <textarea class="form-control @error('auction_product_text_ua') is-invalid @enderror" rows="1"
                                              id="auction_product_text_ua" name="auction_product_text_ua" placeholder="Описание товара на украинском"
                                    >{{(isset($setting->auction_product_text_ua)) ? $setting->auction_product_text_ua : old('auction_product_text_ua')}}</textarea>
                                    @error('auction_product_text_ua')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить настройки </button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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
