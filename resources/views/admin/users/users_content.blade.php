@extends('admin.admin')

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            {{--<div class="row">@include('admin.layouts.status-block')</div>--}}

            <div class="row">
            <!-- /.col-md-6 -->
                <!-- class="col-10 mx-auto"  -->
                <div class="col-md-8 col-sm-12 mx-auto">
                    <div class="card ">
                        <div class="card-header d-flex align-items-baseline ">
                            <h5 class="m-0">Пользователи</h5>
                            <a href="{{ route('admin.user.create') }}" class="btn btn-primary ml-4">
                                Добавить пользователя
                            </a>
                        </div>
                        <div class="card-body table-responsive p-0 ">

                            <table class="table table-hover " id="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>

                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->role}}</td>
                                        <td>

                                            <form action="{{ route('admin.user.destroy', $user) }}" class="form-inline " method="POST" id="user-delete-{{$user->id}}">
                                                <div class="form-group">
                                                    {{-- ссылка независима, к форме не привязана, просто чтоб кнопы были в строку --}}
                                                    <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary btn-sm mr-1" title="Редактировать пользователя"> <i class="fas fa-pen"></i> </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" href="#" role="button" title="Удалить пользователя"
                                                            onclick="confirmDelete('{{$user->id}}', 'user-delete-')" >
                                                        <i class="fas fa-trash" ></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div class="mt-3">
                                {{ $users->links() }}
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
{{--<script>
    function confirmDeleteUser(id) {
        //console.log(id);
        event.preventDefault();
        Swal.fire({
            title: 'Вы уверены?',
            icon: 'warning',
            text: "Вы точно хотите это удалить?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, удалить!'
        }).then((result) => {
            //console.log(result.value);
            if(result.value) {
                document.getElementById('user-delete-'+id).submit()
            }
        })
    }
</script>--}}
