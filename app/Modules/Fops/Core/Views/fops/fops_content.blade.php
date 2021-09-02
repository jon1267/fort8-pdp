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
                            <h5 class="m-0"> Фопы </h5>
                            <a href="{{ route('admin.fop.create') }}" class="btn btn-primary ml-4">
                                Добавить фопа
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0">

                            <!--<table class="table table-bordered table-striped table-sm " id="table">-->
                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование ФОП</th>
                                    <th>API KEY</th>
                                    <th>Телефон</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($fops as $fop)
                                    <tr>
                                        <td>{{$fop->id}}</td>
                                        <td>{{$fop->name}}</td>
                                        <td>{{$fop->api_key}}</td>
                                        <td>{{$fop->senders_phone}}</td>
                                        <td>

                                            <form action="{{ route('admin.fop.destroy', $fop) }}" class="form-inline " method="POST" id="fop-delete-{{$fop->id}}">
                                                <div class="form-group">
                                                    {{-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку --}}
                                                    <a href="{{ route('admin.fop.edit', $fop) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать фоп"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить фоп"
                                                            onclick="confirmDelete('{{$fop->id}}', 'fop-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                @if($fops->hasPages())
                                    {{ $fops->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

