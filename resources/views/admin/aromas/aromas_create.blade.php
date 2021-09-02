@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($aroma)) ? 'Обновление данных' : 'Введите данные' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($aroma)) ? route('admin.aroma.update', $aroma) : route('admin.aroma.store') }}" method="post">
                                @csrf
                                @if(isset($aroma))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group">
                                    <label for="name">Название аромата на русском</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите название категории на русском языке"
                                           value="{{(isset($aroma->name)) ? $aroma->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name_ua">Название аромата на украинском</label>
                                    <input class="form-control @error('name_ua') is-invalid @enderror" type="text"
                                           id="name_ua" name="name_ua" placeholder="Введите название категории на украинском"
                                           value="{{(isset($aroma->name_ua)) ? $aroma->name_ua : old('name_ua')}}">
                                    @error('name_ua')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить аромат </button>
                                    <a href="{{ route('admin.aroma.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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

