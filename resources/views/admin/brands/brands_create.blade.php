@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-8">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($brand)) ? 'Обновление данных' : 'Введите данные' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($brand)) ? route('admin.brand.update', $brand) : route('admin.brand.store') }}" method="post">
                                @csrf
                                @if(isset($brand))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group">
                                    <label for="name">Наименование бренда</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите наименование бренда"
                                           value="{{(isset($brand->name)) ? $brand->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить бренд </button>
                                    <a href="{{ route('admin.brand.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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

