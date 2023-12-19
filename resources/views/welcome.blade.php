<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ env('TITLE_NAME') }}</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
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
                                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                                            儀表板
                                        </h1>
                                        <div class="page-header-subtitle">一個地方，快速取得所有球場現況</div>
                                    </div>
                                    <!-- <div class="col-12 col-xl-auto mt-4">
                                        <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                                            <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                                            <input class="form-control ps-0 pointer" id="litepickerRangePlugin" placeholder="Select date range..." />
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- <header class="py-10 mb-4 bg-gradient-primary-to-secondary">
                        <div class="container-xl px-4">
                            <div class="text-center">
                                <h1 class="text-white">Welcome to SportU Admin Console</h1>
                                <p class="lead mb-0 text-white-50">Your professional course management tool</p>
                            </div>
                        </div>
                    </header> -->
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-body h-100 p-5">
                                        <div class="row align-items-center">
                                            <div class="col-xl-8 col-xxl-12">
                                                <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                                    <h1 class="text-primary">歡迎使用 SPORTU Admin AI!<span class="badge bg-warning-soft text-warning ms-auto">coming soon</span></h1>
                                                    <!-- <p class="text-gray-700 mb-0">使用OpenAI GPT快速提供現況報導。</p>
                                                    <p class="text-gray-700 mb-0">=============</p>
                                                    <p class="text-gray-700 mb-0">目前您提供的球場在學生族群中非常熱門。</p>
                                                    <p class="text-gray-700 mb-0">大部分的使用者在傍晚時使用。</p>
                                                    <p class="text-gray-700 mb-0">或許可以考慮提供更多的球場供學生族群使用。</p> -->
                                                    <p class="text-gray-700 mb-0" id="gpt-text">根據所提供的數據，這裡有一些關鍵的總結：

        最熱門的球場：臺灣大學中央籃球場和臺灣大學醉月湖湖心亭單挑場地頻繁被預定，顯示這些地點非常受歡迎。

        最受歡迎的時段：從數據中看，下午和傍晚時段（大約從14:00到19:00）是預訂球場最繁忙的時間。這可能是因為這段時間對於學生和上班族來說都是較為方便的。

        最活躍的用戶：「joey」和「超猴崽」是最活躍的用戶，他們頻繁進行預訂。這可能表明他們對於使用系統和參與相關活動都非常熱心。

        球種多樣性：籃球是最受歡迎的球類運動，但也有其他運動如排球和網球的場地被預訂。

        這些總結提供了對於使用者行為和場地使用情況的洞察。</p>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-xxl-12 text-center">
                                                <!-- <img class="img-fluid" src="assets/img/illustrations/at-work.svg" style="max-width: 26rem" /> -->
                                            </div>
                                        </div>
                                    </div>
                                    @if(Session::get('userInfo')['email'] === 'tsuyiren@gmail.com' || Session::get('userInfo')['email'] === 'g23988@gmail.com')
                                        <div class="card-footer position-relative">
                                            <div class="d-flex align-items-center justify-content-between small text-body">
                                                <a class="stretched-link text-body" href="javascript:void(0);" id="askGPT">Try it <span class="badge bg-warning-soft text-warning ms-auto">Preview</span></a>
                                                <i class="fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6 mb-4">
                                <div class="card card-header-actions h-100">
                                    <div class="card-header">
                                        最新動態
                                        <div class="dropdown no-caret">
                                            <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                            <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                                <h6 class="dropdown-header">過濾動態 :<span class="badge bg-warning-soft text-warning ms-auto">coming soon</span></h6>
                                                <a class="dropdown-item" href="#!"><span class="badge bg-green-soft text-green my-1">Commerce</span></a>
                                                <a class="dropdown-item" href="#!"><span class="badge bg-blue-soft text-blue my-1">Reporting</span></a>
                                                <a class="dropdown-item" href="#!"><span class="badge bg-yellow-soft text-yellow my-1">Server</span></a>
                                                <a class="dropdown-item" href="#!"><span class="badge bg-purple-soft text-purple my-1">Users</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline timeline-xs">
                                            <!-- Timeline Item 1-->
                                            @foreach ($userMessages as $message)
                                                <div class="timeline-item">
                                                    <div class="timeline-item-marker">
                                                    <div class="timeline-item-marker-text">
                                                        {{ \Carbon\Carbon::createFromTimestamp($message->message_timestamp)->diffForHumans() }}
                                                    </div>
                                                        <div class="timeline-item-marker-indicator bg-green"></div>
                                                    </div>
                                                    <div class="timeline-item-content">
                                                        <span class="fw-bold text-dark">{{ $message->message }}</span>
                                                        <!-- Additional content -->
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6 mb-4">
                                <div class="card card-header-actions h-100">
                                    <div class="card-header">
                                        球場容量追蹤 (目前時段)
                                    </div>
                                    <div class="card-body">
                                        @foreach ($courts as $court)
                                            @php
                                                $currentHeadcount = $court->headcount ? $court->headcount : 0;
                                                $usagePercentage = $court->capacity > 0 ? min(100, ($currentHeadcount / $court->capacity) * 100) : 0;
                                                $progressBarClass = 'bg-danger'; // 預設為紅色

                                                if ($usagePercentage < 30) {
                                                    $progressBarClass = 'bg-success'; // 綠色
                                                } elseif ($usagePercentage < 60) {
                                                    $progressBarClass = 'bg-info'; // 藍色
                                                } elseif ($usagePercentage < 90) {
                                                    $progressBarClass = 'bg-warning'; // 黃色
                                                }
                                            @endphp
                                            <h4 class="small">
                                                {{ $court->name }}
                                                <span class="float-end fw-bold">{{ number_format($usagePercentage, 0) }}%</span>
                                            </h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $usagePercentage }}%" aria-valuenow="{{ $usagePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        @endforeach
                                    </div>



                                    <div class="card-footer position-relative">
                                        <div class="d-flex align-items-center justify-content-between small text-body">
                                            <a class="stretched-link text-body" href="/space/basketball/overview">檢視所有受管的球場</a>
                                            <i class="fas fa-angle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Example Colored Cards for Dashboard Demo-->
                        <div class="row">
                            <div class="col-lg-6 col-xl-3 mb-4">
                                <div class="card bg-warning text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <div class="text-white-75 small">尚未處理的訂單 (Pending)</div>
                                                <div class="text-lg fw-bold">{{ $queueCount ?? 'Loading...' }}</div>
                                            </div>
                                            <i class="feather-xl text-white-50" data-feather="clock"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between small">
                                        <a class="text-white stretched-link" href="javascript:void(0);" id="processQueueLink">手動驅動處理</a>
                                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-3 mb-4">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <div class="text-white-75 small">等待配對中的人數 (Waiting)</div>
                                                <div class="text-lg fw-bold">{{ $waitingMatchesCount ?? 'Loading...' }}</div>
                                            </div>
                                            <i class="feather-xl text-white-50" data-feather="pen-tool"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between small">
                                        <a class="text-white stretched-link" href="javascript:void(0);" id="processCalculateLink">手動驅動統計</a>
                                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-3 mb-4">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <div class="text-white-75 small">成功配對的數量 (Succeeded)</div>
                                                <div class="text-lg fw-bold">{{ $successfulMatchesCount }}</div>
                                            </div>
                                            <i class="feather-xl text-white-50" data-feather="check-square"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between small">
                                        <a class="text-white stretched-link" href="#!">觀察趨勢</a>
                                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-3 mb-4">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <div class="text-white-75 small">配對失敗的紀錄 (Failed)</div>
                                                <div class="text-lg fw-bold">2</div>
                                            </div>
                                            <i class="feather-xl text-white-50" data-feather="x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between small">
                                        <a class="text-white stretched-link" href="#!">未報到總覽</a>
                                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Example Charts for Dashboard Demo-->
                        <div class="row">
                            <div class="col-xl-6 mb-4">
                                <div class="card card-header-actions h-100">
                                    <div class="card-header">
                                        收入明細
                                        <div class="dropdown no-caret">
                                            <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                            <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                                                <a class="dropdown-item" href="#!">Last 12 Months</a>
                                                <a class="dropdown-item" href="#!">Last 30 Days</a>
                                                <a class="dropdown-item" href="#!">Last 7 Days</a>
                                                <a class="dropdown-item" href="#!">This Month</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#!">Custom Range</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="badge bg-warning-soft text-warning ms-auto">coming soon</span>
                                        <div class="chart-area"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-4">
                                <div class="card card-header-actions h-100">
                                    <div class="card-header">
                                        每月收支
                                        <div class="dropdown no-caret">
                                            <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                            <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                                                <a class="dropdown-item" href="#!">Last 12 Months</a>
                                                <a class="dropdown-item" href="#!">Last 30 Days</a>
                                                <a class="dropdown-item" href="#!">Last 7 Days</a>
                                                <a class="dropdown-item" href="#!">This Month</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#!">Custom Range</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="badge bg-warning-soft text-warning ms-auto">coming soon</span>
                                        <div class="chart-bar"><canvas id="myBarChart" width="100%" height="30"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Example DataTable for Dashboard Demo-->
                        <!-- <div class="card mb-4">
                            <div class="card-header">Personnel Management</div>
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
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                            <td><div class="badge bg-primary text-white rounded-pill">Full-time</div></td>
                                            <td>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark me-2"><i data-feather="more-vertical"></i></button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark"><i data-feather="trash-2"></i></button>
                                            </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div> -->
                    </div>
                </main>
                <footer class="footer-admin mt-auto footer-light">
                    @include('layouts.footer')
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script>
            // 處理 processQueueLink
            document.getElementById('processQueueLink').addEventListener('click', function(event) {
                event.preventDefault();
                processRequest('/v1/queue/process');
            });

            // 處理 processCalculateLink
            document.getElementById('processCalculateLink').addEventListener('click', function(event) {
                event.preventDefault();
                processRequest('/v1/spaces/calculate');
            });

            // 處理 askGPT 按钮点击
            document.getElementById('askGPT').addEventListener('click', function(event) {
                event.preventDefault();
                // 先set gpt-text 为 "讀取中..."
                document.getElementById('gpt-text').textContent = '讀取中...';

                // 發動 fetch 請求
                fetch('/ask-gpt4')
                    .then(response => response.json())
                    .then(data => {
                        var gptResponse = data.response.choices[0].message.content;
                        document.getElementById('gpt-text').textContent = gptResponse;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('gpt-text').textContent = '錯誤發生：無法獲取回應。';
                    });
            });

            // 合并功能
            function processRequest(url) {
                fetch(url)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('连接出现错误');
                        }
                    })
                    .then(data => {
                        console.log(data);
                        location.reload();
                    })
                    .catch(error => {
                        console.error('错误:', error);
                        location.reload();
                    });
            }
        </script>





    </body>
</html>
