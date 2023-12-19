<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap 与 Leaflet</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
</head>
<body>
    <div id="map" style="width: 100%; height: 500px;"></div>
    <!-- 地图容器 -->

    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
    <script>
        var map;
        var marker; // 全局变量来存储 Marker

        function initMap() {
            map = L.map('map');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
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

                // 在这里添加代码将点位发送到 Amazon Location Service
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>