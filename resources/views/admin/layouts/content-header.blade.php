<!-- Content Header (Page header) Заголовок правой части + хлебные крошки -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            @if(\Illuminate\Support\Facades\Route::currentRouteName() === 'admin.index')
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Управление сайтом</h1>
                </div>
            @else
                <div class="col-md-8 col-sm-12 mx-auto">
                    <h1 class="m-0 text-dark">{{ $title ?? 'Управление сайтом' }}</h1>
                </div>
            @endif
        </div>
    </div>
</div>

