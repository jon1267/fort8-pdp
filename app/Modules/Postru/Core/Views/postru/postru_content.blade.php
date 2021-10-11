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
                            <h5 class="m-0"> Список реестров  </h5>
                            <!--<a href="#" class="btn btn-primary ml-4">
                                Добавить фопа
                            </a>-->
                        </div>
                        <div class="card-body table-responsive p-0">

                            <!--<table class="table table-bordered table-striped table-sm " id="table">-->
                            <table class="table table-hover " id="table">
                                <tr class="text-center">
                                    <th>Партия</th>
                                    <th>Посылки</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($registers as $register)
                                    <tr class="text-center">
                                        <td>{{$register->name}}</td>
                                        <td>{{count(json_decode($register->barcodes))}}</td>
                                        <td>{{$register->created_at}}</td>

                                        <td>
                                            <a href="{{ route('admin.registers.show', $register->name) }}" class="btn btn-primary btn-sm  {{ $register->checkin ?: 'disabled' }} "  title="Скачать реестр"> Скачать </a>
                                        </td>


                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                @if($registers->hasPages())
                                    {{ $registers->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
