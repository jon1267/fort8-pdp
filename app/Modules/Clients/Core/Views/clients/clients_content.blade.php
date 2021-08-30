@extends('admin.admin')

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <!-- /.col-md-6 -->
                <!-- class="col-10 mx-auto"  -->
                <div class="col-10">
                    <div class="card ">
                        <div class="card-header d-flex align-items-baseline ">
                            <h5 class="m-0"> Клиенты аукциона </h5>
                            <a href="{{ route('admin.client.create') }}" class="btn btn-primary ml-4">
                                Добавить клиента аукциона
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0">

                            <!--<table class="table table-bordered table-striped table-sm " id="table">-->
                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Активен</th>
                                    <th>Сумма</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{$client->id}}</td>
                                        <td>{{$client->first_name}}</td>
                                        <td>{{$client->last_name}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->phone}}</td>
                                        <td> @if($client->active) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</td>
                                        <td>{{$client->sum}}</td>
                                        <td>
                                            <!-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку -->
                                            <a href="{{ route('admin.client.edit', $client) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать фоп"> <i class="fas fa-pen"></i> </a>


                                            {{--<form action="{{ route('admin.client.destroy', $client) }}" class="form-inline " method="POST" id="client-delete-{{$client->id}}">
                                                <div class="form-group">
                                                    <!-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку -->>
                                                    <a href="{{ route('admin.client.edit', $client) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать фоп"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить фоп"
                                                            onclick="confirmDelete('{{$client->id}}', 'client-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                @if($clients->hasPages())
                                    {{ $clients->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
