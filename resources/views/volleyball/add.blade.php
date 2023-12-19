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
                                            新增排球球場
                                        </h1>
                                        <div class="page-header-subtitle">利用視覺化的介面讓供應商新增球場</div>
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
                                <form action="/space/volleyball/addSpace" method="post">
                                    @csrf <!-- CSRF 令牌字段 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <label for="mapclick">1. 點選球場位置</label>
                                        </div>
                                        <div class="card-body">
                                            <div id="map" style="width: 100%; height: 500px;"></div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            2. 座標
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- 經度 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="latitude">經度</label>
                                                        <input class="form-control form-control-solid" id="latitude" name="latitude" type="text" placeholder="點選地圖取得座標">
                                                    </div>
                                                </div>

                                                <!-- 緯度 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="longitude">緯度</label>
                                                        <input class="form-control form-control-solid" id="longitude" name="longitude" type="text" placeholder="點選地圖取得座標">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            3. 球場資訊
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- 球場名稱 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="spaceName">球場名稱</label>
                                                        <input class="form-control" id="spaceName" name="name" type="text" placeholder="排球場" value="排球場">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address">地址</label>
                                                        <input class="form-control" id="address" name="address" type="text" placeholder="地址" value="地址">
                                                    </div>
                                                </div>
                                                <!-- 球場容量 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="eachtime">每場時長 (1小時為單位)</label>
                                                        <input class="form-control" id="eachtime" name="eachtime" type="number" placeholder="spaceEachtime" value="2">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="spaceCapacity">球場總容量</label>
                                                        <input class="form-control" id="spaceCapacity" name="capacity" type="number" placeholder="spaceCapacity" value="50">
                                                    </div>
                                                </div>
                                                <!-- 球場容量 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="in_game">每次列隊的長度</label>
                                                        <input class="form-control" id="in_game" name="in_game" type="number" placeholder="spaceIn_game" value="2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- 球種 排球= 2-->
                                    <input type="hidden" id="type" name="type" value="2">
                                    <input type="hidden" id="pic" name="pic" value="https://admin.chillmonkey.tw/assets/img/court/default.png">

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
                <!-- 地图容器 -->

                <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
                <script>
                    var map;
                    var marker; // 全局变量来存储 Marker

                    function initMap() {
                        map = L.map('map');

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© SportU'
                        }).addTo(map);

                        // 使用浏览器的 Geolocation API 获取用户当前位置
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var currentLat = position.coords.latitude;
                                var currentLng = position.coords.longitude;

                                // 设置地图中心为当前位置
                                map.setView([currentLat, currentLng], 13);

                                // 在当前位置创建一个 Marker
                                marker = L.marker([currentLat, currentLng]).addTo(map);
                            }, function() {
                                // 用户拒绝共享位置或发生错误
                                alert("无法获取您的位置信息。");
                                // 设置一个默认中心点
                                map.setView([25.0330, 121.5654], 13);
                            });
                        } else {
                            // 浏览器不支持 Geolocation
                            alert("您的浏览器不支持地理定位。");
                            // 设置一个默认中心点
                            map.setView([25.0330, 121.5654], 13);
                        }

                        // 地图点击事件
                        map.on('click', function(e) {
                            var coord = e.latlng;

                            if (marker) {
                                // 更新 Marker 的位置
                                marker.setLatLng(coord);
                            } else {
                                // 创建一个新的 Marker
                                marker = L.marker([coord.lat, coord.lng]).addTo(map);
                            }

                            // 将地图中心移动到点击位置
                            map.setView(coord, map.getZoom());

                            // 將點擊的經緯度填入輸入框
                            document.getElementById('latitude').value = coord.lat;
                            document.getElementById('longitude').value = coord.lng;

                            // 在這裡添加代碼將點位發送到 Amazon Location Service
                        });

                    }

                    window.onload = initMap;


                </script>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
    </body>
</html>
