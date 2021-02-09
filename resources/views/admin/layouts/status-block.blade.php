<script>
    @if(session('status'))
        toastr.success("{{ session('status') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>

{{-- это свиталерт2, работает, и заработало по инклюду этого файла в гл. шаблон
    (а не в той вьюхе, где возникает событие...)
<script>
    @if(session('status'))
    Swal.fire({
        toast: true,
        type: 'success',
        icon: 'success',
        position: 'top-end',
        title: "{{ session('status') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @endif

    @if(session('error'))
    Swal.fire({
        toast: true,
        type: 'error',
        icon: 'error',
        position: 'top-end',
        title: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @endif
</script>--}}

{{-- код рабочий, но сообщения не исчезают (это 1-ый тупой бутстрапов вариант...)
@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
--}}
