<x-app-layout>

    <div class="container py-5" style="max-width: 900px;">

        <!-- Top Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Realtime Search Dashboard</h2>

            <!-- Logout -->
            {{-- <form method="POST" action="/logout">
                @csrf
                <button class="btn btn-outline-danger btn-sm">
                    Logout
                </button>
            </form> --}}
        </div>

        <!-- Info -->
        <p class="text-muted mb-4">
            Search 1M+ records using MySQL, Elasticsearch, Redis cache & Laravel Queue
        </p>

        <!-- Search Card -->
        <div class="card p-4" style="border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

            <label class="form-label fw-semibold">
                Search by Name, Email or City
            </label>

            <input
                type="text"
                id="search"
                class="form-control mb-2"
                style="border-radius:12px; padding:14px 16px;"
                placeholder="Start typing..."
                autocomplete="off"
            >

            <div id="loading" class="text-muted small d-none">
                🔍 Searching...
            </div>

            <ul class="list-group list-group-flush mt-3" id="results"></ul>
        </div>

    </div>

    <!-- JS -->
    <script>
        let timer = null;
        const searchInput = document.getElementById('search');
        const resultsEl = document.getElementById('results');
        const loadingEl = document.getElementById('loading');

        searchInput.addEventListener('keyup', function () {
            clearTimeout(timer);
            let query = this.value.trim();

            timer = setTimeout(() => {

                if (query.length < 2) {
                    resultsEl.innerHTML = '';
                    loadingEl.classList.add('d-none');
                    return;
                }

                loadingEl.classList.remove('d-none');

                fetch(`/search?q=${query}`)
                    .then(res => res.json())
                    .then(data => {

                        let html = '';

                        if (data.length === 0) {
                            html = `<li class="list-group-item text-muted">No results found</li>`;
                        } else {
                            data.forEach(item => {
                                html += `
                                    <li class="list-group-item">
                                        <div class="fw-semibold">${item.name}</div>
                                        <div class="text-muted small">
                                            ${item.email} • ${item.city}
                                        </div>
                                    </li>
                                `;
                            });
                        }

                        resultsEl.innerHTML = html;
                        loadingEl.classList.add('d-none');
                    })
                    .catch(() => {
                        resultsEl.innerHTML =
                            `<li class="list-group-item text-danger">Something went wrong</li>`;
                        loadingEl.classList.add('d-none');
                    });

            }, 300);
        });
    </script>

</x-app-layout>
