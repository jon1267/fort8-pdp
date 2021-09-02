@extends('admin.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header ">
                            <h5 class="m-0">{{ (isset($payment)) ? 'Обновление запроса на выплату' : 'Введите данные запроса на выплату' }}</h5>
                        </div>
                        <div class="card-body">

                            <!-- -->
                            <form  action="{{ (isset($payment)) ? route('admin.payment.update', $payment) : route('admin.payment.store') }}" method="post">
                                @csrf
                                @if(isset($payment))
                                    @method('PUT')
                                    {{--<input type="hidden" name="updated_by_id" value="{{ $userId }}">--}}
                                @else
                                    {{--<input type="hidden" name="created_by_id" value="{{ $userId }}">--}}
                                @endif

                                <div class="form-group">
                                    <label for="client_id">ID Клиента</label>
                                    <input class="form-control @error('client_id') is-invalid @enderror" type="text"
                                           id="client_id" name="client_id" {{ (isset($payment->client_id)) ? ' disabled ' : '' }} placeholder="Введите ID клиента"
                                           value="{{(isset($payment->client_id)) ? $payment->client_id : old('client_id')}}">
                                    @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sum">Сумма</label>
                                    <input class="form-control @error('sum') is-invalid @enderror" type="text"
                                           id="sum" name="sum" placeholder="Сумма"
                                           value="{{(isset($payment->sum)) ? $payment->sum : old('sum')}}">
                                    @error('sum')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="card">Номер карты</label>
                                    <input class="form-control @error('card') is-invalid @enderror" type="text"
                                           id="card" name="card" placeholder="Введите номер карты"
                                           value="{{(isset($payment->card)) ? $payment->card : old('card')}}">
                                    @error('card')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="comment">Комментарий</label>
                                    <input class="form-control @error('comment') is-invalid @enderror" type="text"
                                           id="comment" name="comment" placeholder="Введите фамилию клиента"
                                           value="{{(isset($payment->comment)) ? $payment->comment : old('comment')}}">
                                    @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form-group ">
                                    <!--<label>Активен (не активен) </label>-->
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" name="paid" type="checkbox" id="active"
                                               {{ (old('paid') == 1) ? ' checked ' : '' }}
                                               @isset($payment) @if($payment->paid == 1) checked @endif @endisset
                                        >
                                        <label class="custom-control-label" for="active">Оплачен</label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="far fa-save mr-2"></i>Сохранить запрос на выплату </button>
                                    <a href="{{ route('admin.payment.index') }}" class="btn btn-info ml-2"> <i class="fas fa-sign-out-alt mr-2"></i>Отмена</a>
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
