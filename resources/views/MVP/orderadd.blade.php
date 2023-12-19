<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="MVP Order Management System" />
    <meta name="author" content="Your Company Name" />
    <title>{{ env('TITLE_NAME', 'MVP Order System') }}</title>
    <link href="/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
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
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="layout"></i></div>
                                        新增訂單
                                    </h1>
                                    <div class="page-header-subtitle">MVP測試，可以從這邊新增一個訂單</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-xl px-4">
                    <div class="card mt-n10">
                        <div class="card-header">操作</div>
                        <div class="card-body">
                            <!-- Display return messages -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form action="/mvp/orderadd" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="userId" class="form-label">用戶 ID</label>
                                    <input type="text" class="form-control" id="userId" name="userId" placeholder="2" value="2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="courtId" class="form-label">球場 ID</label>
                                    <input type="text" class="form-control" id="courtId" name="courtId" placeholder="7" value="7" required>
                                </div>
                                <div class="mb-3">
                                    <label for="timestamp" class="form-label">時間戳記</label>
                                    <input type="text" class="form-control" id="timestamp" name="timestamp" placeholder="1701799200" value="1701799200" required>
                                </div>
                                <button class="btn btn-primary" type="submit">新增訂單</button>
                            </form>
                            <hr>
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
</body>
</html>
