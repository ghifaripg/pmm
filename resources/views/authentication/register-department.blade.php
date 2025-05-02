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

                <!-- Main Form -->
                <div class="row justify-content-center form-bg-image" style="margin-top: 10px"
                    data-background-lg="../../assets/img/illustrations/signin.svg">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Create Department</h1>
                            </div>

                            <form method="POST" action="/register-department" class="mt-4">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="department_name">Department Name</label>
                                    <input type="text" name="department_name" class="form-control"
                                        placeholder="Enter Department Name" id="department_name" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="department_username">Department Username</label>
                                    <input type="text" name="department_username" class="form-control"
                                        placeholder="Enter Department Username" id="department_username" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="bisnis-selector">Bisnis Terkait</label>
                                    <div class="input-group mb-3">
                                        <select id="bisnis-selector" class="form-control" multiple>
                                            @foreach ($bisnisTerkait as $bisnis)
                                                <option value="{{ $bisnis->id }}">{{ $bisnis->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-secondary mt-2 ms-2"
                                            id="tambah-bisnis-btn">
                                            Tambah
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary mt-2 ms-2"
                                            data-bs-toggle="modal" data-bs-target="#bisnisModal">
                                            + Tambah Baru
                                        </button>
                                    </div>

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

                <!-- Modal -->
                <div id="bisnisModal" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="add-bisnis-form">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Bisnis Terkait Baru</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="new-bisnis-name">Nama Bisnis Terkait</label>
                                        <input type="text" id="new-bisnis-name" class="form-control"
                                            placeholder="Masukkan nama bisnis terkait baru" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
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
        $(document).ready(function() {
            $('#bisnis-selector').select2({
                placeholder: "Pilih Bisnis Terkait...",
                allowClear: true,
                width: '100%'
            });

            const selectedBisnis = [];

            function renderSelectedBisnis() {
                const list = $('#bisnis-terkait-list');
                const inputs = $('#bisnis-terkait-inputs');
                list.empty();
                inputs.empty();

                selectedBisnis.forEach((item, index) => {
                    list.append(`
                        <div class="d-flex justify-content-between align-items-center border p-2 mb-2 rounded">
                            <span>${item.text}</span>
                            <button type="button" class="btn btn-sm btn-danger remove-bisnis" data-index="${index}">&times;</button>
                        </div>
                    `);

                    inputs.append(`<input type="hidden" name="bisnis_terkait[]" value="${item.id}">`);

                    if (item.id.startsWith('new_')) {
                        inputs.append(
                            `<input type="hidden" name="new_bisnis_names[${item.id}]" value="${item.text}">`
                            );
                    }

                });
            }

            $('#tambah-bisnis-btn').click(function() {
                const selectedOptions = $('#bisnis-selector').select2('data');

                if (selectedOptions.length === 0) {
                    alert('Silakan pilih bisnis terkait terlebih dahulu');
                    return;
                }

                selectedOptions.forEach(opt => {
                    if (!selectedBisnis.find(b => b.id === opt.id)) {
                        selectedBisnis.push({
                            id: opt.id,
                            text: opt.text
                        });
                    }
                });

                renderSelectedBisnis();

                $('#bisnis-selector').val(null).trigger('change');
            });

            $(document).on('click', '.remove-bisnis', function() {
                const index = $(this).data('index');
                selectedBisnis.splice(index, 1);
                renderSelectedBisnis();
            });

            $('#add-bisnis-form').on('submit', function(e) {
                e.preventDefault();

                const newBisnisName = $('#new-bisnis-name').val().trim();
                if (!newBisnisName) {
                    alert('Masukkan nama bisnis terkait baru.');
                    return;
                }

                const newId = 'new_' + Date.now();
                selectedBisnis.push({
                    id: newId,
                    text: newBisnisName
                });
                renderSelectedBisnis();

                $('#new-bisnis-name').val('');
                var modal = bootstrap.Modal.getInstance(document.getElementById('bisnisModal'));
                modal.hide();
            });
        });
    </script>
</body>

</html>
