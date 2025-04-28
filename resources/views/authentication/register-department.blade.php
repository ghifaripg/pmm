    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
        <meta name="author" content="Creative Tim">
        <title>Register Departemen</title>

        <!-- Canonical SEO -->
        <link rel="canonical" href="https://www.creative-tim.com/product/impact-design-system" />
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"
            type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
        <!-- Argon CSS -->

    </head>

    <body>

        <main>

            <!-- Section -->
            <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
                <div class="container">
                    <p class="text-center">
                        <a href="/dashboard" class="d-flex align-items-center justify-content-center"
                            style="margin-top: 30px; margin-bottom: 30px">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </p>
                    <div class="row justify-content-center form-bg-image" style="margin-top: 10px"
                        data-background-lg="../../assets/img/illustrations/signin.svg">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                                <div class="text-center text-md-center mb-4 mt-md-0">
                                    <h1 class="mb-0 h3">Create Department </h1>
                                </div>
                                <form method="POST" action="/register-department" class="mt-4">
                                    @csrf
                                    <!-- Department Name -->
                                    <div class="form-group mb-4">
                                        <label for="department_name">Department Name</label>
                                        <input type="text" name="department_name" class="form-control"
                                            placeholder="Enter Department Name" id="department_name" required>
                                    </div>

                                    <!-- Department Username -->
                                    <div class="form-group mb-4">
                                        <label for="department_username">Department Username</label>
                                        <input type="text" name="department_username" class="form-control"
                                            placeholder="Enter Department Username" id="department_username" required>
                                    </div>

                                    <!-- Bisnis Terkait -->
                                    <div class="form-group mb-4">
                                        <label for="bisnis-selector">Bisnis Terkait</label>

                                        <div id="bisnis-select-wrapper" class="input-group mb-3">
                                            <select id="bisnis-selector" name="bisnis_terkait[]" class="form-control">

                                                <option value="" disabled selected>Pilih Bisnis Terkait...
                                                </option>

                                                <!-- Loop through the bisnis_terkait data from the controller -->
                                                @foreach ($bisnisTerkait as $bisnis)
                                                    <option value="{{ $bisnis->id }}">{{ $bisnis->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-secondary ml-3"
                                                onclick="toggleBisnisInput()">+ Tambah Baru</button>
                                        </div>

                                        <div id="bisnis-input-wrapper" class="input-group mb-3" style="display: none;">
                                            <input type="text" id="new-bisnis-input" class="form-control"
                                                placeholder="Enter new bisnis terkait">
                                            <button type="button" class="btn btn-outline-secondary ms-2"
                                                onclick="toggleBisnisInput()">Cancel</button>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary mb-3"
                                            onclick="addBisnis()">Tambah</button>

                                        <div id="bisnis-terkait-list" class="mb-2"></div>
                                        <div id="bisnis-terkait-inputs"></div>
                                    </div>


                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Create Department</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
        <!-- Optional JS -->
        <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
        <!-- Argon JS -->
        <script src="{{ asset('assets/js/dashboard.js?v=1.2.0') }}"></script>
        <!-- Demo JS - remove this in your project -->
        <script src="{{ asset('assets/js/demo.min.js') }}"></script>
        <!-- ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            let isAddingNew = false;

            function toggleBisnisInput() {
                isAddingNew = !isAddingNew;
                document.getElementById('bisnis-select-wrapper').style.display = isAddingNew ? 'none' : 'flex';
                document.getElementById('bisnis-input-wrapper').style.display = isAddingNew ? 'flex' : 'none';

                if (!isAddingNew) {
                    document.getElementById('new-bisnis-input').value = '';
                }
            }

            function addBisnis() {
                let value, label;

                if (isAddingNew) {
                    value = document.getElementById('new-bisnis-input').value.trim();
                    label = value;

                    if (!value) {
                        alert('Masukkan nama bisnis terkait baru.');
                        return;
                    }
                } else {
                    const selector = document.getElementById('bisnis-selector');
                    value = selector.value;
                    label = selector.options[selector.selectedIndex]?.text;

                    if (!value) {
                        alert('Pilih bisnis terkait dari daftar.');
                        return;
                    }
                }

                // Check for duplicates
                const existingInputs = document.getElementsByName('bisnis_terkait[]');
                for (const input of existingInputs) {
                    if (input.value === value) {
                        alert('Bisnis terkait ini sudah ditambahkan.');
                        return;
                    }
                }

                const id = 'bisnis_' + Date.now();

                const tag = document.createElement('div');
                tag.className = 'badge bg-info text-dark me-2 mb-2';
                tag.id = 'display_' + id;
                tag.innerHTML = `
                    ${label}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" onclick="removeBisnis('${id}')"></button>
                `;
                document.getElementById('bisnis-terkait-list').appendChild(tag);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'bisnis_terkait[]';
                hiddenInput.value = value;
                hiddenInput.id = id;
                document.getElementById('bisnis-terkait-inputs').appendChild(hiddenInput);

                if (isAddingNew) {
                    document.getElementById('new-bisnis-input').value = '';
                } else {
                    document.getElementById('bisnis-selector').selectedIndex = 0;
                }
            }

            function removeBisnis(id) {
                document.getElementById(id)?.remove();
                document.getElementById('display_' + id)?.remove();
            }
        </script>
    </body>

    </html>
