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
                            <h5 class="m-0">Ароматы</h5>
                            <a href="{{ route('admin.aroma.create') }}" class="btn btn-primary ml-4">
                                Добавить аромат
                            </a>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered table-striped table-sm " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Аромат на русском</th>
                                    <th>Аромат на украинском</th>
                                    <th>Создано</th>
                                    <th>Обновлено</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($aromas as $aroma)
                                    <tr>
                                        <td>{{$aroma->id}}</td>
                                        <td>{{$aroma->name}}</td>
                                        <td>{{$aroma->name_ua}}</td>
                                        <td>{{ $aroma->createdBy->name ?? ''}}</td>
                                        <td>{{ $aroma->updatedBy->name ?? ''}}</td>
                                        <td>

                                            <form action="{{ route('admin.aroma.destroy', $aroma) }}" class="form-inline " method="POST" id="aroma-delete-{{$aroma->id}}">
                                                <div class="form-group">
                                                    {{-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку --}}
                                                    <a href="{{ route('admin.aroma.edit', $aroma) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать аромат"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить аромат"
                                                            onclick="confirmDelete('{{$aroma->id}}', 'aroma-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                @if($aromas->hasPages())
                                    {{ $aromas->links() }}
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

