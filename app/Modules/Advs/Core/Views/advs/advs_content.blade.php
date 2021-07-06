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
                            <h5 class="m-0">Таблица advs </h5>
                            <a href="{{ route('admin.adv.create') }}" class="btn btn-primary ml-4">
                                Добавить запись
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0">

                            <!--<table class="table table-bordered table-striped table-sm " id="table">-->
                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Pixels</th>
                                    <th>Pixel plugin</th>
                                    <th>Sort</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($advs as $adv)
                                    <tr>
                                        <td>{{$adv->id}}</td>
                                        <td>{{$adv->name}}</td>
                                        <td>{{ Str::limit($adv->pixels, 150) }}</td>
                                        <td>{{$adv->pixel_plugin}}</td>
                                        <td>{{$adv->sort}}</td>
                                        <td>

                                            <form action="{{ route('admin.adv.destroy', $adv) }}" class="form-inline " method="POST" id="adv-delete-{{$adv->id}}">
                                                <div class="form-group">
                                                    {{-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку --}}
                                                    <a href="{{ route('admin.adv.edit', $adv) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать запись"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить запись"
                                                            onclick="confirmDelete('{{$adv->id}}', 'adv-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                @if($advs->hasPages())
                                    {{ $advs->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

