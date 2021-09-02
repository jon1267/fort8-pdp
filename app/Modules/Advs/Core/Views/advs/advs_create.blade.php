@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($adv)) ? 'Обновление данных ' : 'Новая запись' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($adv)) ? route('admin.adv.update', $adv) : route('admin.adv.store') }}" method="post">
                                @csrf
                                @if(isset($adv))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group">
                                    <label for="name">Наименование </label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите наименование"
                                           value="{{(isset($adv->name)) ? $adv->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="pixels">Pixels </label>
                                    <textarea class="form-control @error('pixels') is-invalid @enderror" rows="3"
                                              id="pixels" name="pixels" placeholder="pixels content"
                                    >{{(isset($adv->pixels)) ? $adv->pixels : old('pixels')}}</textarea>
                                    @error('pixels')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-5">
                                    <!--<label>Pixel plugin </label>-->
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" name="pixel_plugin" type="checkbox" id="pixel_plugin"
                                               {{ (old('pixel_plugin') == 1) ? ' checked ' : '' }}
                                               @isset($adv) @if($adv->pixel_plugin == 1) checked @endif @endisset
                                        >
                                        <label class="custom-control-label" for="pixel_plugin">Pixel plugin</label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="sort">Sort</label>
                                    <input class="form-control @error('sort') is-invalid @enderror" type="text"
                                           id="sort" name="sort" placeholder="sort"
                                           value="{{(isset($adv->sort)) ? $adv->sort : old('sort')}}">
                                    @error('sort')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить запись </button>
                                    <a href="{{ route('admin.adv.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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

