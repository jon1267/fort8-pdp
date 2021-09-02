@extends('admin.admin')

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <!-- /.col-md-6 -->
                <!-- class="col-10 mx-auto"  -->
                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header d-flex align-items-baseline ">
                            <h5 class="m-0"> Запрос выплат аукциона </h5>
                            <a href="{{ route('admin.payment.create') }}" class="btn btn-primary ml-4">
                                Добавить запрос выплаты
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0">

                            <!--<table class="table table-bordered table-striped table-sm " id="table">-->
                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Клиент</th>
                                    <th>Сумма</th>
                                    <th>Карта</th>
                                    <th>Комментарий</th>
                                    <th>Оплата</th>
                                    <th>Дата</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{$payment->id}}</td>
                                        <td>{{$payment->client_id}}</td>
                                        <td>{{$payment->sum}}</td>
                                        <td>{{$payment->card}}</td>
                                        <td>{{$payment->comment}}</td>
                                        <td> @if($payment->paid) <i class="fas fa-check text-success ml-3"></i> @else <i class="fas fa-times text-danger ml-3"></i> @endif</td>
                                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.payment.edit', $payment) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать запрос выплаты"> <i class="fas fa-pen"></i> </a>
                                            {{--<form action="{{ route('admin.payment.destroy', $payment) }}" class="form-inline " method="POST" id="payment-delete-{{$payment->id}}">
                                                <div class="form-group">
                                                    <!-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку -->>
                                                    <a href="{{ route('admin.payment.edit', $payment) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать фоп"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить фоп"
                                                            onclick="confirmDelete('{{$payment->id}}', 'payment-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="m-3">
                                @if($payments->hasPages())
                                    {{ $payments->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
