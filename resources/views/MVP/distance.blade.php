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
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                            球場導航
                                        </h1>
                                        <div class="page-header-subtitle">MVP測試，可以快速得知您與球場的導航</div>
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
                                <form id="distanceForm" action="/mvp/distance" method="post">
                                    @csrf <!-- CSRF 令牌字段 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <label for="mapclick">你現在的位置</label>
                                        </div>
                                        <div class="card-body">
                                            <div id="map" style="width: 100%; height: 500px;"></div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            你現在的座標
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
                                            球場資訊
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- 球場名稱 -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="spaceName">球場在DB裡的ID </label>
                                                        <input class="form-control" id="spaceName" name="id" type="number" placeholder="球場id" value="7">
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
                <!-- 地图容器 -->

                <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
                <script>
                    var map;
                    var marker;
                    var routeLayer;

                    function initMap() {
                        map = L.map('map').setView([25.0330, 121.5654], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        map.on('click', function(e) {
                            if (marker) {
                                marker.setLatLng(e.latlng);
                            } else {
                                marker = L.marker(e.latlng).addTo(map);
                            }
                            document.getElementById('latitude').value = e.latlng.lat;
                            document.getElementById('longitude').value = e.latlng.lng;
                        });

                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var lat = position.coords.latitude;
                                var lng = position.coords.longitude;
                                marker = L.marker([lat, lng]).addTo(map);
                                map.setView([lat, lng], 13);
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lng;
                            });
                        }
                    }

                    function plotRoute(routeCoordinates) {
                        if (routeLayer) {
                            map.removeLayer(routeLayer);
                        }
                        routeLayer = L.polyline(routeCoordinates, {color: 'red'}).addTo(map);
                        map.fitBounds(routeLayer.getBounds());
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        initMap();

                        document.getElementById('distanceForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            var formData = new FormData(this);

                            fetch('/mvp/distance', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.path) {
                                    plotRoute(data.path);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        });
                    });
                    document.addEventListener('DOMContentLoaded', function() {
                        initMap();

                        document.getElementById('distanceForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            // ... AJAX request code ...
                        });
                    });
                </script>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
    </body>
</html>
