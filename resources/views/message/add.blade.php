<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ env('TITLE_NAME') }}</title>
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
                                            測試傳訊介面
                                        </h1>
                                        <div class="page-header-subtitle">前端測試傳訊系統用</div>
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
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <form action="/mvp/messages" method="post">
                                    @csrf <!-- CSRF 令牌字段 -->
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- 傳送對象id -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="user_id">傳送對象id</label>
                                                        <input class="form-control" id="user_id" name="user_id" type="text" placeholder="2" value="2">
                                                    </div>
                                                </div>

                                                <!-- 訊息內容 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="message">訊息內容</label>
                                                        <input class="form-control" id="message" name="message" type="text" placeholder="測試訊息" value="測試訊息：來自【{{ session('userInfo.nickname') }}】的問候。">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-success" type="submit">確認送出</button>
                                    </div>
                                </form>
                                
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
