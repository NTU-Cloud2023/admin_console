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
                                            修改排球球場
                                        </h1>
                                        <div class="page-header-subtitle">利用視覺化的介面讓供應商修改球場</div>
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
                                <form action="/space/volleyball/edit/{{ $court->id }}" method="post" enctype="multipart/form-data">
                                    @csrf <!-- CSRF 令牌字段 -->
                                    @method('PUT')
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
                                                        <input class="form-control" id="latitude" name="latitude" type="text" value="{{ $court->latitude }}">
                                                    </div>
                                                </div>

                                                <!-- 緯度 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="longitude">緯度</label>
                                                        <input class="form-control" id="longitude" name="longitude" type="text" value="{{ $court->longitude }}">
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
                                                        <input class="form-control" id="spaceName" name="name" type="text" value="{{ $court->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address">地址</label>
                                                        <input class="form-control" id="address" name="address" type="text" value="{{ $court->address }}">
                                                    </div>
                                                </div>
                                                <!-- 球場容量 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="eachtime">每場時長 (1小時為單位)</label>
                                                        <input class="form-control" id="eachtime" name="eachtime" type="number" value="{{ $court->eachtime }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="spaceCapacity">球場總容量</label>
                                                        <input class="form-control" id="spaceCapacity" name="capacity" type="number" value="{{ $court->capacity }}">
                                                    </div>
                                                </div>
                                                <!-- 球場容量 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="in_game">每次列隊的長度</label>
                                                        <input class="form-control" id="in_game" name="in_game" type="number" value="{{ $court->in_game }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            4. 上傳球場照片
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="courtImage">球場照片</label>
                                                <input class="form-control" id="courtImage" name="courtImage" type="file" accept="image/*">
                                            </div>
                                            <!-- If there's an existing image, you might want to display it here -->
                                            @if ($court->pic)
                                                <div class="mb-3">
                                                    <img src="{{ $court->pic }}" alt="Court Image" style="max-width: 100%;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>


                                    <!-- 球種 排球= 2-->
                                    <input type="hidden" id="type" name="type" value="2">
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-success" type="submit">更新</button>
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
                    var marker; // Global variable to store the Marker

                    function initMap() {
                        map = L.map('map');

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© SportU'
                        }).addTo(map);

                        // Set default location from the database
                        var defaultLat = {{ $court->latitude }};
                        var defaultLng = {{ $court->longitude }};

                        // Set map view to default location
                        map.setView([defaultLat, defaultLng], 13);

                        // Place a marker on the default location
                        marker = L.marker([defaultLat, defaultLng]).addTo(map);

                        // Map click event
                        map.on('click', function(e) {
                            var coord = e.latlng;

                            if (marker) {
                                // Update the Marker's position
                                marker.setLatLng(coord);
                            } else {
                                // Create a new Marker
                                marker = L.marker([coord.lat, coord.lng]).addTo(map);
                            }

                            // Move map center to clicked position
                            map.setView(coord, map.getZoom());

                            // Fill the clicked latitude and longitude in the input fields
                            document.getElementById('latitude').value = coord.lat;
                            document.getElementById('longitude').value = coord.lng;
                        });
                    }

                    window.onload = initMap;
                </script>


                </script>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
    </body>
</html>
