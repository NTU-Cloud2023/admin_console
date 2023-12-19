                    <div class="sidenav-menu">
                        <div class="nav accordion" id="accordionSidenav">
                            <!-- Sidenav Menu Heading (Account)-->
                            <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                            <div class="sidenav-menu-heading d-sm-none">Account</div>
                            <!-- Sidenav Link (Alerts)-->
                            <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                            <a class="nav-link d-sm-none" href="#!">
                                <div class="nav-link-icon"><i data-feather="bell"></i></div>
                                Alerts
                                <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                            </a>
                            <!-- Sidenav Link (Messages)-->
                            <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                            <a class="nav-link d-sm-none" href="#!">
                                <div class="nav-link-icon"><i data-feather="mail"></i></div>
                                Messages
                                <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                            </a>
                            <!-- Sidenav Menu Heading (Core)-->
                            <div class="sidenav-menu-heading">總覽</div>
                            <!-- Sidenav Link (dashboard)-->
                            <a class="nav-link" href="/home">
                                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                儀錶板
                            </a>
                            <!-- Sidenav Accordion (Dashboard)-->
                            <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                Dashboards
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDashboards" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                                    <a class="nav-link" href="dashboard-1.html">
                                        Default
                                        <span class="badge bg-primary-soft text-primary ms-auto">Updated</span>
                                    </a>
                                    <a class="nav-link" href="dashboard-2.html">Multipurpose</a>
                                    <a class="nav-link" href="dashboard-3.html">Affiliate</a>
                                </nav>
                            </div> -->
                            <!-- Sidenav Heading (Custom)-->
                            <div class="sidenav-menu-heading">資產管理</div>
                            <!-- Sidenav Accordion (Spaces)-->
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#spacePages" aria-expanded="false" aria-controls="spacePages">
                                <div class="nav-link-icon"><i data-feather="globe"></i></div>
                                球場管理
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="spacePages" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                                    <!-- Nested Sidenav Accordion (Pages -> Account)-->
                                    <!-- bastketball -->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesSpaceBasketball" aria-expanded="false" aria-controls="pagesSpaceBasketball">
                                        籃球場
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesSpaceBasketball" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/space/basketball/overview">總覽</a>
                                            <a class="nav-link" href="/space/basketball/add">新增球場</a>
                                        </nav>
                                    </div>
                                    <!-- 排球場-->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesSpaceVolleyball" aria-expanded="false" aria-controls="pagesSpaceVolleyball">
                                        排球場
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesSpaceVolleyball" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/space/volleyball/overview">總覽</a>
                                            <a class="nav-link" href="/space/volleyball/add">新增球場</a>
                                        </nav>
                                    </div>
                                    <!-- 網球-->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesSpaceTennis" aria-expanded="false" aria-controls="pagesSpaceTennis">
                                        網球場
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesSpaceTennis" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/space/tennis/overview">總覽</a>
                                            <a class="nav-link" href="/space/tennis/add">新增球場</a>
                                        </nav>
                                    </div>
                                    <!-- 羽球場-->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesSpaceBadminton" aria-expanded="false" aria-controls="pagesSpaceBadminton">
                                        羽球場
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesSpaceBadminton" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/space/badminton/overview">總覽</a>
                                            <a class="nav-link" href="/space/badminton/add">新增球場</a>
                                        </nav>
                                    </div>
                                    <!-- Nested Sidenav Accordion (Pages -> Authentication)-->
                                    <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesAuth"> -->
                                            <!-- Nested Sidenav Accordion (Pages -> Authentication -> Basic)-->
                                            <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuthBasic" aria-expanded="false" aria-controls="pagesCollapseAuthBasic">
                                                Basic
                                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                            </a>
                                            <div class="collapse" id="pagesCollapseAuthBasic" data-bs-parent="#accordionSidenavPagesAuth">
                                                <nav class="sidenav-menu-nested nav">
                                                    <a class="nav-link" href="auth-login-basic.html">Login</a>
                                                    <a class="nav-link" href="auth-register-basic.html">Register</a>
                                                    <a class="nav-link" href="auth-password-basic.html">Forgot Password</a>
                                                </nav>
                                            </div> -->
                                            <!-- Nested Sidenav Accordion (Pages -> Authentication -> Social)-->
                                            <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuthSocial" aria-expanded="false" aria-controls="pagesCollapseAuthSocial">
                                                Social
                                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                            </a>
                                            <div class="collapse" id="pagesCollapseAuthSocial" data-bs-parent="#accordionSidenavPagesAuth">
                                                <nav class="sidenav-menu-nested nav">
                                                    <a class="nav-link" href="auth-login-social.html">Login</a>
                                                    <a class="nav-link" href="auth-register-social.html">Register</a>
                                                    <a class="nav-link" href="auth-password-social.html">Forgot Password</a>
                                                </nav>
                                            </div>
                                        </nav>
                                    </div> -->
                                    <!-- Nested Sidenav Accordion (Pages -> Error)-->
                                    <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" data-bs-parent="#accordionSidenavPagesMenu">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="error-400.html">400 Error</a>
                                            <a class="nav-link" href="error-401.html">401 Error</a>
                                            <a class="nav-link" href="error-403.html">403 Error</a>
                                            <a class="nav-link" href="error-404-1.html">404 Error 1</a>
                                            <a class="nav-link" href="error-404-2.html">404 Error 2</a>
                                            <a class="nav-link" href="error-500.html">500 Error</a>
                                            <a class="nav-link" href="error-503.html">503 Error</a>
                                            <a class="nav-link" href="error-504.html">504 Error</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link" href="pricing.html">Pricing</a>
                                    <a class="nav-link" href="invoice.html">Invoice</a> -->
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#balltypePages" aria-expanded="false" aria-controls="balltypePages">
                                <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                                球種
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="balltypePages" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" href="/space/balltype/overview">總覽</a>
                                </nav>
                            </div>


                            <!-- Sidenav Heading (UI Toolkit)-->
                            <div class="sidenav-menu-heading">帳戶管理</div>
                            <!-- Sidenav Accordion (Layout)-->
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#customerPages" aria-expanded="false" aria-controls="customerPages">
                                <div class="nav-link-icon"><i data-feather="user"></i></div>
                                客戶帳號
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <!-- /customer/overview -->
                            <div class="collapse" id="customerPages" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" href="/customer/overview">總覽</a>
                                </nav>
                            </div>


                            <!-- Sidenav Heading (UI Toolkit)-->
                            <div class="sidenav-menu-heading">概念驗證 MVP</div>
                            <!-- Sidenav Accordion (Layout)-->
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseMVP" aria-expanded="false" aria-controls="collapseMVP">
                                <div class="nav-link-icon"><i data-feather="wind"></i></div>
                                前端用測試
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseMVP" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                                    <!-- Nested Sidenav Accordion (Layout -> Navigation)-->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLayoutSidenavVariations" aria-expanded="false" aria-controls="collapseLayoutSidenavVariations">
                                        球場相關
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayoutSidenavVariations" data-bs-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/mvp/distance">球場導航</a>
                                            <a class="nav-link" href="/mvp/spacelist">球場列表</a>
                                            <!-- <a class="nav-link" href="layout-rtl.html">RTL Layout</a> -->
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
                                        訂單相關
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseOrders" data-bs-parent="#accordionOrders">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/mvp/orderclear">清除訂單紀錄</a>
                                            <a class="nav-link" href="/mvp/orderadd">新增訂單測試</a>
                                            <!-- <a class="nav-link" href="layout-rtl.html">RTL Layout</a> -->
                                        </nav>
                                    </div>
                                    <!-- Nested Sidenav Accordion (Layout -> Container Options)-->
                                    <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLayoutContainers" aria-expanded="false" aria-controls="collapseLayoutContainers">
                                        Container Options
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayoutContainers" data-bs-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="layout-boxed.html">Boxed Layout</a>
                                            <a class="nav-link" href="layout-fluid.html">Fluid Layout</a>
                                        </nav>
                                    </div> -->
                                    <!-- Nested Sidenav Accordion (Layout -> Page Headers)-->
                                    <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsPageHeaders" aria-expanded="false" aria-controls="collapseLayoutsPageHeaders">
                                        Page Headers
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayoutsPageHeaders" data-bs-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="header-simplified.html">Simplified</a>
                                            <a class="nav-link" href="header-compact.html">Compact</a>
                                            <a class="nav-link" href="header-overlap.html">Content Overlap</a>
                                            <a class="nav-link" href="header-breadcrumbs.html">Breadcrumbs</a>
                                            <a class="nav-link" href="header-light.html">Light</a>
                                        </nav>
                                    </div> -->
                                    <!-- Nested Sidenav Accordion (Layout -> Starter Layouts)-->
                                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsStarterTemplates" aria-expanded="false" aria-controls="collapseLayoutsStarterTemplates">
                                        傳訊系統
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayoutsStarterTemplates" data-bs-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" href="/mvp/messages">發訊測試</a>
                                            <!-- <a class="nav-link" href="#!">Minimal</a> -->
                                        </nav>
                                    </div>
                                </nav>
                            </div>

                            <!-- Sidenav Heading (Addons)-->
                            <div class="sidenav-menu-heading">系統管理</div>
                            <!-- Sidenav Link (Charts)-->
                            <a class="nav-link" href="#!">
                                <div class="nav-link-icon"><i data-feather="settings"></i></div>
                                管理者後台設定
                            </a>
                            <!-- Sidenav Link (Tables)-->
                            <!-- <a class="nav-link" href="tables.html">
                                <div class="nav-link-icon"><i data-feather="filter"></i></div>
                                Tables
                            </a> -->
                        </div>
                    </div>
                    <!-- Sidenav Footer-->
                    <div class="sidenav-footer">
                        <div class="sidenav-footer-content">
                            <div class="sidenav-footer-subtitle">Logged in as:</div>
                            <div class="sidenav-footer-title">{{ session('userInfo.nickname') }}</div>
                        </div>
                    </div>
