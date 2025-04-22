<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main" style="overflow-x: hidden">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  d-flex  align-items-center">
            <a class="navbar-brand" href="/dashboard ">
                <img src="{{ asset('assets/img/logo.webp') }}" height="40" class="navbar-brand-img" alt="...">
            </a>
            <div class=" ml-auto ">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-dashboards" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="navbar-dashboards">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">Dashboards</span>
                        </a>
                        <div class="collapse" id="navbar-dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/dashboard" class="nav-link">
                                        <span class="sidenav-mini-icon"> D </span>
                                        <span class="sidenav-normal"> Dashboard </span>
                                    </a>
                                </li>
                                <?php if ($userId == 1): ?>

                                <li class="nav-item">
                                    <a href="/dashboard-admin" class="nav-link">
                                        <span class="sidenav-mini-icon"> D </span>
                                        <span class="sidenav-normal"> Dashboard Admin </span>
                                    </a>
                                </li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontrakMenu" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="kontrakMenu">
                            <i class="ni ni-ungroup text-orange"></i>
                            <span class="nav-link-text">Kontrak Manajemen</span>
                        </a>
                        <div class="collapse" id="kontrakMenu">
                            <ul class="nav nav-sm flex-column">

                                <!-- Form Kontrak Manajemen -->
                                <li class="nav-item">
                                    <a class="nav-link" href="#formKontrakSubmenu" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="formKontrakSubmenu">
                                        <span class="sidenav-mini-icon">FK</span>
                                        <span class="sidenav-normal">Kontrak Manajemen</span>
                                    </a>
                                    <div class="collapse" id="formKontrakSubmenu">
                                        <ul class="nav nav-sm flex-column ms-3">
                                            <li class="nav-item">
                                                <a href="/kontrak" class="nav-link">
                                                    <span class="sidenav-mini-icon">P</span>
                                                    <span class="sidenav-normal">Pilih Tahun</span>
                                                </a>
                                            </li>
                                            <?php if ($userId == 1): ?>
                                            <li class="nav-item">
                                                <a href="/form-kontrak" class="nav-link">
                                                    <span class="sidenav-mini-icon">IF</span>
                                                    <span class="sidenav-normal">Isi Form</span>
                                                </a>
                                            </li>
                                            <?php endif ?>
                                        </ul>
                                    </div>
                                </li>

                                <!-- Penjabaran Strategi Pencapaian -->
                                <li class="nav-item">
                                    <a class="nav-link" href="#penjabaranSubmenu" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="penjabaranSubmenu">
                                        <span class="sidenav-mini-icon">SP</span>
                                        <span class="sidenav-normal">Penjabaran Strategi</span>
                                    </a>
                                    <div class="collapse" id="penjabaranSubmenu">
                                        <ul class="nav nav-sm flex-column ms-3">
                                            <li class="nav-item">
                                                <a href="/penjabaran" class="nav-link">
                                                    <span class="sidenav-mini-icon">PT</span>
                                                    <span class="sidenav-normal">Pilih Tahun</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/isi-penjabaran" class="nav-link">
                                                    <span class="sidenav-mini-icon">IP</span>
                                                    <span class="sidenav-normal">Isi Penjabaran</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-tables">
                            <i class="ni ni-align-left-2 text-default"></i>
                            <span class="nav-link-text">IKU</span>
                        </a>
                        <div class="collapse" id="navbar-tables">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/iku" class="nav-link">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal"> Pilih Tahun </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/form-iku" class="nav-link">
                                        <span class="sidenav-mini-icon"> FI </span>
                                        <span class="sidenav-normal"> Form IKU </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/progres" class="nav-link">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal"> Progres </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-maps" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-maps">
                            <i class="ni ni-map-big text-primary"></i>
                            <span class="nav-link-text">Evaluasi</span>
                        </a>
                        <div class="collapse" id="navbar-maps">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/evaluasi" class="nav-link">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal"> Pilih Periode </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/form-evaluasi" class="nav-link">
                                        <span class="sidenav-mini-icon"> FE </span>
                                        <span class="sidenav-normal"> Form Evaluasi </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="my-3">
                    <?php if (!$isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <i class="ni ni-single-02"></i>
                            <span class="nav-link-text">Profile</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-admin" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-admin">
                            <i class="ni ni-single-02 text-orange"></i>
                            <span class="nav-link-text">Profile (Admin)</span>
                        </a>
                        <div class="collapse" id="navbar-admin">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="/profile" class="nav-link">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal"> Profile </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/register" class="nav-link">
                                        <span class="sidenav-mini-icon"> RU </span>
                                        <span class="sidenav-normal"> Register User </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/register-department" class="nav-link">
                                        <span class="sidenav-mini-icon"> RD </span>
                                        <span class="sidenav-normal"> Register Departemen </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/users" class="nav-link">
                                        <span class="sidenav-mini-icon"> L </span>
                                        <span class="sidenav-normal"> List User </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>
