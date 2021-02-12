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
                            <h5 class="m-0">Товары</h5>
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary ml-4">
                                Добавить товар
                            </a>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered table-striped table-sm " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Производитель</th>
                                    <th>Наименование</th>
                                    <th>Описание на русском</th>
                                    <th>Описание на украинском</th>
                                    <th>Изображение</th>
                                    <th>Действия</th>
                                </tr>

                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->vendor}}</td>
                                        <td>{{$product->name}}</td>
                                        <td>{{ Str::limit($product->description) }}</td>
                                        <td>{{ Str::limit($product->description_ua) }}</td>
                                        <td>
                                            @if(!empty($product->img))
                                                {{-- <img src="{{asset('/storage/images/product/' . $product->img) }}" width="50"  alt="image">--}}
                                                <img src="{{asset($product->img) }}" width="50"  alt="image">
                                            @endif
                                        </td>

                                        <td>

                                            <form action="{{ route('admin.product.destroy', $product) }}" class="form-inline " method="POST" id="product-delete-{{$product->id}}">
                                                <div class="form-group">
                                                    <!-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку -->
                                                    <a href="{{ route('admin.product.edit', $product) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать товар"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить товар"
                                                            onclick="confirmDelete('{{$product->id}}', 'product-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
