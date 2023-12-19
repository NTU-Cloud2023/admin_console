<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ env('TITLE_NAME') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="/css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="nav-fixed">
        @include('layouts.nav')
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    @include('layouts.sidenav')
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <!-- Header for Volleyball Court Overview -->
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                        <div class="container-xl px-4">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                        <h1 class="page-header-title">
                                            <div class="page-header-icon"><i data-feather="filter"></i></div>
                                            排球場總覽
                                        </h1>
                                        <div class="page-header-subtitle">在一個頁面裡管理您的球場。</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">排球場總覽</div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>球場名稱</th>
                                            <th>球場種類</th>
                                            <th>經度</th>
                                            <th>緯度</th>
                                            <th>球場容量</th>
                                            <th>目前使用者數</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                            <th>ID</th>
                                            <th>球場名稱</th>
                                            <th>球場種類</th>
                                            <th>經度</th>
                                            <th>緯度</th>
                                            <th>球場容量</th>
                                            <th>目前使用者數</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($courts as $court)
                                        <tr>
                                            <td>{{ $court->id }}</td>
                                            <td>{{ $court->name }}</td>
                                            <td>{{ $court->ballType->game_name }}</td>
                                            <td>{{ $court->latitude }}</td>
                                            <td>{{ $court->longitude }}</td>
                                            <td>{{ $court->capacity }}</td>
                                            <td>{{ $court->headcount}}</td>
                                            <td>
                                                <a href="/space/volleyball/edit/{{ $court->id }}" class="btn btn-datatable btn-icon btn-transparent-dark me-2"><i data-feather="tool"></i></a>
                                                <a href="/space/volleyball/delete/{{ $court->id }}" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="return confirm('Are you sure you want to delete this item?');"><i data-feather="trash-2"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="footer-admin mt-auto footer-light">
                    @include('layouts.footer')
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="/js/datatables/datatables-simple-demo.js"></script>
    </body>
</html>
