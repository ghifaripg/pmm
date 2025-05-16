<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>Register Departemen</title>

    <link rel="canonical" href="https://www.creative-tim.com/product/impact-design-system" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">

    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main>
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="/dashboard"
                        class="d-flex btn btn-outline-secondary align-items-center justify-content-center"
                        style="margin-top: 30px; margin-left: 450px; max-width: 400px">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </p>

                <!-- Main Form -->
                <div class="row justify-content-center form-bg-image" style="margin-top: 10px"
                    data-background-lg="../../assets/img/illustrations/signin.svg">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500 mt-4">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Register Unit Kerja</h1>
                            </div>

                            <form method="POST" action="/register-department" class="mt-4">
                                @csrf

                                <!-- Role Type -->
                                <div class="form-group mb-4">
                                    <label>Registrasi Sebagai:</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role_type"
                                            id="role_director" value="director" required>
                                        <label class="form-check-label" for="role_director">Direktur</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role_type"
                                            id="role_division" value="division">
                                        <label class="form-check-label" for="role_division">Divisi</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role_type"
                                            id="role_department" value="department">
                                        <label class="form-check-label" for="role_department">Departemen</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <!-- Director -->
                                        <div class="form-group mb-4" id="director_input_group">
                                            <label for="director_name">Nama Direktur</label>
                                            <input type="text" name="director_name" class="form-control"
                                                id="director_name" placeholder="Tulis Nama Direktur">
                                        </div>

                                        <!-- Director Select -->
                                        <div class="form-group mb-4 d-none" id="director_select_group">
                                            <label for="director_select">Direktur</label>
                                            <select name="director_select" id="director_select" class="form-control"
                                                required>
                                                <option value="">-- Pilih --</option>
                                                @foreach ($directors as $director)
                                                    <option value="{{ $director->director_id }}">
                                                        {{ $director->director_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Division -->
                                        <div class="form-group mb-4" id="division_input_group">
                                            <label for="division_name">Nama Divisi</label>
                                            <input type="text" name="division_name" class="form-control"
                                                id="division_name" placeholder="Tulis Nama Divisi">
                                        </div>

                                        <!-- Division Select -->
                                        <div class="form-group mb-4 d-none" id="division_select_group">
                                            <label for="division_select">Divisi</label>
                                            <select name="division_select" id="division_select" class="form-control"
                                                required>
                                                <option value="">-- Pilih Divisi --</option>
                                                <option value="-">-</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->division_id }}"
                                                        data-director-id="{{ $division->director_id }}">
                                                        {{ $division->division_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <!-- Department -->
                                        <div class="form-group mb-4">
                                            <label for="department_name">Nama Departemen</label>
                                            <input type="text" name="department_name" class="form-control"
                                                id="department_name" placeholder="Tulis Nama Departemen">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                id="username" placeholder="Tulis Username" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-3 justify-content-center">
                                    <button type="submit" style="max-width: 380px" class="btn btn-primary">Register
                                        Unit Kerja</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function toggleFields() {
            const role = $('input[name="role_type"]:checked').val();

            // Hide all initially
            $('#director_input_group, #director_select_group').addClass('d-none');
            $('#division_input_group').removeClass('d-none');
            $('#division_select_group').addClass('d-none');

            // Enable everything first
            $('#director_name, #director_select, #division_name, #division_select, #department_name').prop('disabled', false);

            if (role === 'director') {
                $('#director_input_group').removeClass('d-none');
                $('#division_name, #department_name').val('').prop('disabled', true);
            } else if (role === 'division') {
                $('#director_select_group').removeClass('d-none');
                $('#division_name').prop('disabled', false);
                $('#department_name').val('').prop('disabled', true);
            } else if (role === 'department') {
                $('#director_select_group').removeClass('d-none');
                $('#division_input_group').addClass('d-none');
                $('#division_select_group').removeClass('d-none');
            }
        }

        // Run on role change
        $('input[name="role_type"]').on('change', toggleFields);

        // Auto-set director based on division
        $('#division_select').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const directorId = selectedOption.data('director-id');

            if (directorId && directorId !== '-') {
                $('#director_select').val(directorId);
                $('#director_select').prop('disabled', true);
            } else {
                $('#director_select').val('');
                $('#director_select').prop('disabled', false);
            }
        });

        // Trigger default toggle on load (in case of form re-render)
        $(document).ready(toggleFields);
    </script>
</body>

</html>
