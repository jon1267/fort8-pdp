@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-8">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($fop)) ? 'Обновление данных фоп' : 'Введите данные фоп' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($fop)) ? route('admin.fop.update', $fop) : route('admin.fop.store') }}" method="post">
                                @csrf
                                @if(isset($fop))
                                    @method('PUT')
                                    <input type="hidden" name="updated_by_id" value="{{ $userId }}">
                                @else
                                    <input type="hidden" name="created_by_id" value="{{ $userId }}">
                                @endif

                                <div class="form-group">
                                    <label for="name">Наименование фоп</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                           id="name" name="name" placeholder="Введите наименование фоп"
                                           value="{{(isset($fop->name)) ? $fop->name : old('name')}}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="api_key">API KEY</label>
                                    <input class="form-control @error('api_key') is-invalid @enderror" type="text"
                                           id="api_key" name="api_key" placeholder="Введите API KEY фоп"
                                           value="{{(isset($fop->api_key)) ? $fop->api_key : old('api_key')}}">
                                    @error('api_key')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="city_sender">City sender </label>
                                    <input class="form-control @error('city_sender') is-invalid @enderror" type="text"
                                           id="city_sender" name="city_sender" placeholder="city sender"
                                           value="{{(isset($fop->city_sender)) ? $fop->city_sender : old('city_sender')}}">
                                    @error('city_sender')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sender">Sender </label>
                                    <input class="form-control @error('sender') is-invalid @enderror" type="text"
                                           id="sender" name="sender" placeholder="sender"
                                           value="{{(isset($fop->sender)) ? $fop->sender : old('sender')}}">
                                    @error('sender')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sender_address">Sender address </label>
                                    <input class="form-control @error('sender_address') is-invalid @enderror" type="text"
                                           id="sender_address" name="sender_address" placeholder="sender address"
                                           value="{{(isset($fop->sender_address)) ? $fop->sender_address : old('sender_address')}}">
                                    @error('sender_address')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_sender">Contact sender</label>
                                    <input class="form-control @error('contact_sender') is-invalid @enderror" type="text"
                                           id="contact_sender" name="contact_sender" placeholder="contact sender"
                                           value="{{(isset($fop->contact_sender)) ? $fop->contact_sender : old('contact_sender')}}">
                                    @error('contact_sender')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="senders_phone">Senders phone</label>
                                    <input class="form-control @error('senders_phone') is-invalid @enderror" type="text"
                                           id="senders_phone" name="senders_phone" placeholder="senders phone"
                                           value="{{(isset($fop->senders_phone)) ? $fop->senders_phone : old('senders_phone')}}">
                                    @error('senders_phone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-5">
                                    <!--<label>Активен (не активен) </label>-->
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" name="active" type="checkbox" id="active"
                                               {{ (old('active') == 1) ? ' checked ' : '' }}
                                               @isset($fop) @if($fop->active == 1) checked @endif @endisset
                                        >
                                        <label class="custom-control-label" for="active">Active</label>
                                    </div>
                                </div>

                                <div class="form-group col-5">
                                    <!--<label>Payment control</label>-->
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" name="payment_control" type="checkbox" id="payment_control"
                                               {{ (old('payment_control') == 1) ? ' checked ' : '' }}
                                               @isset($fop) @if($fop->payment_control == 1) checked @endif @endisset
                                        >
                                        <label class="custom-control-label" for="payment_control">Payment control</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Payment method</label>
                                    <input class="form-control @error('payment_method') is-invalid @enderror" type="text"
                                           id="payment_method" name="payment_method" placeholder="payment method"
                                           value="{{(isset($fop->payment_method)) ? $fop->payment_method : old('payment_method')}}">
                                    @error('payment_method')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить фоп </button>
                                    <a href="{{ route('admin.fop.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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

