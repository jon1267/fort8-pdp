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
                            <h5 class="m-0">Категории</h5>
                            <a href="{{ route('admin.category.create') }}" class="btn btn-primary ml-4">
                                Добавить категорию
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0 ">

                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Категория на русском</th>
                                    <th>Категория на украинском</th>
                                    <!--<th>Создано</th>-->
                                    <!--<th>Обновлено</th>-->
                                    <th>Действия</th>
                                </tr>

                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->name_ua}}</td>
                                        {{-- <td>{{ $category->createdBy->name ?? ''}}</td>
                                        <td>{{ $category->updatedBy->name ?? ''}}</td>--}}
                                        <td>

                                            <form action="{{ route('admin.category.destroy', $category) }}" class="form-inline " method="POST" id="category-delete-{{$category->id}}">
                                                <div class="form-group">
                                                    {{-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку --}}
                                                    <a href="{{ route('admin.category.edit', $category) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать категорию"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить категорию"
                                                            onclick="confirmDelete('{{$category->id}}', 'category-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                {{ $categories->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
