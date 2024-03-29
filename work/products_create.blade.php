@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-10">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($product)) ? 'Обновление данных' : 'Введите данные товара' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($product)) ? route('admin.product.update', $product) : route('admin.product.store') }}"
                                   method="post" enctype="multipart/form-data">
                                @csrf
                                @if(isset($product))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group ml-2">
                                    <label for="vendor">Поставщик (производитель?)</label>
                                    <input class="form-control @error('vendor') is-invalid @enderror" type="text"
                                           id="vendor" name="vendor" placeholder="Введите поставщика товара"
                                           value="{{(isset($product->vendor)) ? $product->vendor : old('vendor')}}">
                                    @error('vendor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mt-5 ml-2">
                                    <label for="name">Наименование товара</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите наименование товара"
                                           value="{{(isset($product->name)) ? $product->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex mt-5">
                                    <div class="form-group col-6">
                                        <label for="description">Описание товара на русском</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" rows="2"
                                            id="description" name="description" placeholder="Описание товара на русском"
                                            >{{(isset($product->description)) ? $product->description : old('description')}}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="description_ua">Описание товара на украинском</label>
                                        <textarea class="form-control @error('description_ua') is-invalid @enderror" rows="2"
                                            id="description_ua" name="description_ua" placeholder="Описание товара на украинском"
                                            >{{(isset($product->description_ua)) ? $product->description_ua : old('description_ua')}}</textarea>
                                        @error('description_ua')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="d-flex mt-5">

                                    <div class="form-group col-6">
                                        <label for="exampleInputFile">Изображение товара</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('img') is-invalid @enderror" id="img" name="img" aria-describedby="customFileInput">
                                                <label class="custom-file-label" for="customFileInput">Выберите изображение</label>
                                            </div>
                                        </div>
                                        @error('img')
                                            <!-- нада style="display: inline-block", т.к. custom-file-input где-то ставит d-none -->
                                            <span class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        <div>
                                            @if(isset($product) && (!empty($product->img)))
                                                <div class="form-group mt-2" id="old-image-div">
                                                    <label for="old_img" >Старое изображение</label>
                                                    <a href="#" onclick="event.preventDefault(); imageDelete('Product', 'img' ,'{{ $product->id }}', 'deleted_image')" id="delete-image-button" class="badge badge-danger ml-2" title="удалить старое изображение" >&nbsp;x&nbsp;</a>
                                                    <div>
                                                        <img src="{{asset('/storage/images/product/' . $product->img) }}" width="60" alt="Image">
                                                    </div>
                                                </div>
                                                <input type="hidden" id="deleted_image_model" name="deleted_image[model]" value="">
                                                <input type="hidden" id="deleted_image_field" name="deleted_image[field]" value="">
                                                <input type="hidden" id="deleted_image_id" name="deleted_image[id]" value="">
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-group col-6">
                                        <label>Категории товара</label>
                                        @foreach($categories as $category)
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" name="categories[]"
                                                       type="checkbox" value="{{ $category->id }}" id="{{ $category->name }}"
                                                       {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? ' checked' : '' }}
                                                       @isset($product) @if(in_array($category->id, $product->categories->pluck('id')->toArray())) checked @endif @endisset
                                                >
                                                <label class="custom-control-label" for="{{ $category->name }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('categories')
                                        <span class="invalid-feedback" style="display: inline-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <!-- пока ароматы и бренды в строку -->
                                <div class="d-flex mt-5">
                                    <div class="form-group col-6">
                                        <label for="brand_id"> Бренд </label>
                                        <select class="form-control @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id">
                                            <option disabled selected value="" >Выберите Бренд</option>
                                            @foreach($brands as $brand)
                                                @if(old('brand_id') == $brand->id)
                                                    <option value="{{ old('brand_id') }}" selected >{{ $brand->name }}</option>
                                                @else
                                                    <option value="{{ $brand->id }}" {{ (isset($product->brand_id) && $product->brand_id == $brand->id ) ? 'selected' : '' }}> {{ $brand->name }} </option>
                                                @endif

                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="aroma_id"> Аромат </label>
                                        <select class="form-control @error('aroma_id') is-invalid @enderror" name="aroma_id" id="aroma_id">
                                            <option disabled selected value="" >Выберите аромат</option>
                                            @foreach($aromas as $aroma)
                                                @if(old('aroma_id') == $aroma->id)
                                                    <option value="{{ old('aroma_id') }}" selected >{{ $aroma->name }}</option>
                                                @else
                                                    <option value="{{ $aroma->id }}"  {{ (isset($product->aroma_id) && $product->aroma_id == $aroma->id ) ? 'selected' : '' }} >{{ $aroma->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('aroma_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group mt-5 ml-2">

                                    <div >

                                       <label for="variants-table" >Таблица вариантов </label>
                                       <span class="ml-2 small text-gray ">необходим хотя-бы один вариант</span>
                                        <a href="#" class="btn btn-light mb-2 ml-3" id="add-variants-button"> Добавить варианты</a>

                                        @if(isset($product) && !empty($product->productVariants))
                                            <!-- это закидываем в JS для построения таблицы вариантов -->
                                            <input type="hidden" id="old-variants-count"  name="old-variants-count" value="{{count($product->productVariants)}}">
                                        @else
                                            <input type="hidden" id="old-variants-count"  name="old-variants-count" value="0">
                                        @endif

                                        <table class="table table-bordered table-responsive-md" id="variants-table">
                                            <thead>
                                            <tr class="text-center">
                                                <th scope="col">Название</th>
                                                <th scope="col">Объем ml</th>
                                                <th scope="col">Артикул</th>
                                                <th scope="col">Цена Укр.</th>
                                                <th scope="col">Цена Рус.</th>
                                                <th scope="col">Актив_Укр.</th>
                                                <th scope="col">Актив_Рус.</th>
                                                <th scope="col"><i class="fas fa-cogs" title="Действия"></i></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if(!empty($product->productVariants))
                                                @foreach($product->productVariants as $variant)
                                                    <tr class="text-center" id="old_variant_tr_{{$loop->index}}" >
                                                        <td><input type="text" id="name_{{$loop->index}}" name="variants[{{$loop->index}}][name]" value="{{ $variant->name }}" class="form-control"></td>
                                                        <td><input type="text" id="volume_{{$loop->index}}" name="variants[{{$loop->index}}][volume]" value="{{ $variant->volume }}" class="form-control"></td>
                                                        <td><input type="text" id="art_{{$loop->index}}" name="variants[{{$loop->index}}][art]" value="{{ $variant->art }}" class="form-control"></td>
                                                        <td><input type="text" id="price_ua_{{$loop->index}}" name="variants[{{$loop->index}}][price_ua]" value="{{ $variant->price_ua }}" class="form-control"></td>
                                                        <td><input type="text" id="price_ru_{{$loop->index}}" name="variants[{{$loop->index}}][price_ru]" value="{{ $variant->price_ru }}" class="form-control"></td>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="active_ua_{{$loop->index}}" name="variants[{{$loop->index}}][active_ua]" value="1" {{ $variant->active_ua ? 'checked' : ''  }} >
                                                                <label for="active_ua_{{$loop->index}}" class="custom-control-label"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="active_ru_{{$loop->index}}" name="variants[{{$loop->index}}][active_ru]" value="1" {{ $variant->active_ru ? 'checked' : ''  }}  >
                                                                <label for="active_ru_{{$loop->index}}" class="custom-control-label"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" id="old_delete_button_{{ $loop->index }}" class="old_delete_button btn btn-danger btn-sm" title="удалить вариант" >&nbsp;X&nbsp;</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>

                                        @error('variants')
                                        <span class="invalid-feedback" style="display: inline-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div id="add-variants-div" class="add-variants-div"></div>

                                <div class="d-flex mt-5">
                                    <div class="form-group col-4">
                                        <label>Ноты</label>
                                        <select class="select2  @error('notes') is-invalid @enderror" multiple="multiple" data-placeholder="Выберите ноту" name="notes[]"  style="width: 100%;">

                                            @foreach($notes as $note)
                                                <option value="{{ $note->id }}"
                                                        @isset($product) @if(in_array($note->id, $product->notes->pluck('id')->toArray())) selected @endif @endisset
                                                >
                                                    {{ $note->name_ru }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-4">
                                        <label>Ноты 2</label>
                                        <select class="select2  @error('notes2') is-invalid @enderror" multiple="multiple" data-placeholder="Выберите ноту" name="notes2[]"  style="width: 100%;">
                                            @foreach($notes as $note)
                                                <option value="{{ $note->id }}"
                                                        @isset($product) @if(in_array($note->id, $product->notes2->pluck('id')->toArray())) selected @endif @endisset
                                                >
                                                    {{ $note->name_ru }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('notes2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-4">
                                        <label>Ноты 3</label>
                                        <select class="select2  @error('notes3') is-invalid @enderror" multiple="multiple" data-placeholder="Выберите ноту" name="notes3[]"  style="width: 100%;">
                                            @foreach($notes as $note)
                                                <option value="{{ $note->id }}"
                                                        @isset($product) @if(in_array($note->id, $product->notes3->pluck('id')->toArray())) selected @endif @endisset
                                                >
                                                    {{ $note->name_ru }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('notes3')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group mt-3 ml-2">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить товар</button>
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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
