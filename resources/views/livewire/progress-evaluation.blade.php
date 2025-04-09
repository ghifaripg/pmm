<div>

            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <h2 class="fs-5 fw-bold mb-0">Progres Evaluasi</h2>
                <a href="/evaluasi" class="btn btn-sm btn-primary">Isi Evaluasi</a>
            </div>
            <div class="card-body">
                <h5 class="mb-4">Evaluasi Bulanan - {{ $selectedYear }}</h5>
                <div class="mt-4">
                    @foreach ($months as $month => $percentage)
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <svg class="icon icon-sm text-gray-500" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="col">
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="h6 mb-0">
                                            {{ \Carbon\Carbon::createFromFormat('!m', $month)->locale('id')->translatedFormat('F') }}
                                        </div>
                                        <div class="small fw-bold text-gray-500"><span>{{ $percentage }} %</span></div>
                                    </div>
                                    <div class="progress mb-0">
                                        <div class="progress-bar
                                        @if ($percentage == 100) bg-success
                                        @elseif($percentage >= 50) bg-warning
                                        @else bg-danger @endif"
                                            role="progressbar" aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0" aria-valuemax="100"
                                            style="width: {{ $percentage }}%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button wire:click="prevPage" class="btn btn-outline-primary btn-sm"
                        @if ($page == 1) disabled @endif>
                        Previous
                    </button>
                    <span>Page {{ $page }} of {{ $totalPages }}</span>
                    <button wire:click="nextPage" class="btn btn-outline-primary btn-sm"
                        @if ($page == $totalPages) disabled @endif>
                        Next
                    </button>
                </div>
            </div>
</div>
