<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('template/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('template/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- menu-open по списку <li> делает меню открытым -->
                <li class="nav-item has-treeview">
                    <!-- active по ссылке <a> подсвечивает ссылку как активную -->
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Пользователь
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <!-- active по ссылке <a> подсвечивает ссылку как активную -->
                            <a href="{{ route('admin.user.index') }}" class="nav-link">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Пользователи</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-unlock nav-icon"></i>
                                <p>Роли</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- -->

                <li class="nav-item">
                    <a href="{{ route('admin.category.index') }}" class="nav-link">
                        <!--<i class="fas fa-bullhorn nav-icon"></i>-->
                        <i class="fas fa-cat nav-icon"></i>
                        <p>Категории</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.aroma.index') }}" class="nav-link">
                        <!--<i class="fas fa-wine-bottle"></i>-->
                        <i class="fab fa-java nav-icon"></i>
                        <p>Ароматы</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.brand.index') }}" class="nav-link">
                        <!--<i class="far fa-copyright"></i></i>-->
                        <i class="far fa-copyright nav-icon"></i>
                        <p>Бренды</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.product.index') }}" class="nav-link">
                        <!--<i class="fas fa-bullhorn nav-icon"></i>-->
                        <i class="fas fa-apple-alt nav-icon"></i>
                        <p>Товары</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.edit', 1 ) }}" class="nav-link">
                        <i class="fas fa-cog nav-icon"></i>
                        <!--<i class="fas fa-tools nav-icon"></i>-->
                        <!--<i class="fas fa-wrench nav-icon"></i>-->
                        <p>Настройки</p>
                    </a>
                </li>

                <li class="nav-item">

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt nav-icon "></i>
                        <p>Выход</p>
                    </a>
                </li>
                <!-- -->
                <!--<li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Simple Link
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>-->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
