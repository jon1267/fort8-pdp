@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-8">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($client)) ? 'Обновление данных клиента' : 'Введите данные клиента' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($client)) ? route('admin.client.update', $client) : route('admin.client.store') }}" method="post">
                                @csrf
                                @if(isset($client))
                                    @method('PUT')
                                    {{--<input type="hidden" name="updated_by_id" value="{{ $userId }}">--}}
                                @else
                                {{--<input type="hidden" name="created_by_id" value="{{ $userId }}">--}}
                                @endif

                                <div class="form-group">
                                    <label for="first_name">Имя</label>
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text"
                                           id="first_name" name="first_name" placeholder="Введите имя клиента"
                                           value="{{(isset($client->first_name)) ? $client->first_name : old('first_name')}}">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Фамилия</label>
                                    <input class="form-control @error('last_name') is-invalid @enderror" type="text"
                                           id="last_name" name="last_name" placeholder="Введите фамилию клиента"
                                           value="{{(isset($client->last_name)) ? $client->last_name : old('last_name')}}">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="text"
                                           id="email" name="email" placeholder="Email клиента"
                                           value="{{(isset($client->email)) ? $client->email : old('email')}}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Телефон</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                           id="phone" name="phone" placeholder="Телефон клиента"
                                           value="{{(isset($client->phone)) ? $client->phone : old('phone')}}">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group ">
                                    <!--<label>Активен (не активен) </label>-->
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" name="active" type="checkbox" id="active"
                                               {{ (old('active') == 1) ? ' checked ' : '' }}
                                               @isset($client) @if($client->active == 1) checked @endif @endisset
                                        >
                                        <label class="custom-control-label" for="active">Активан</label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="sum">Сумма</label>
                                    <input class="form-control @error('sum') is-invalid @enderror" type="text"
                                           id="sum" name="sum" placeholder="Сумма"
                                           value="{{(isset($client->sum)) ? $client->sum : old('sum')}}">
                                    @error('sum')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить клиента </button>
                                    <a href="{{ route('admin.client.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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


