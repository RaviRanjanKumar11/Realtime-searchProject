<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Realtime Search</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #ffffff);
            min-height: 100vh;
        }
        .search-card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .search-input {
            border-radius: 12px;
            padding: 14px 16px;
        }
        .result-item {
            transition: background 0.2s ease;
        }
        .result-item:hover {
            background: #f1f5f9;
        }
        .loading {
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-4 position-relative">

<!-- Top Right Logout -->
<form method="POST" action="/logout" class="position-absolute top-0 end-0 p-4">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <button class="btn btn-outline-danger btn-sm">
        Logout
    </button>
</form>

<div class="container" style="max-width: 720px;">

    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">Realtime People Search</h2>
        <p class="text-muted mb-0">
            Search 1M+ records using MySQL, Elasticsearch, Redis cache & Laravel Queue
        </p>
    </div>

    <!-- Search Card -->
    <div class="card search-card p-4">

        <label class="form-label fw-semibold">
            Search by Name, Email or City
        </label>

        <input
            type="text"
            id="search"
            class="form-control search-input"
            placeholder="Start typing..."
            autocomplete="off"
        >

        <div id="loading" class="loading mt-2 d-none">
            🔍 Searching...
        </div>

        <ul class="list-group list-group-flush mt-3" id="results"></ul>
    </div>

</div>

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
                    html = `<li class="list-group-item text-muted">
                                No results found
                            </li>`;
                } else {
                    data.forEach(item => {
                        html += `
                            <li class="list-group-item result-item">
                                <div class="fw-semibold">${item.name}</div>
                                <div class="text-muted" style="font-size: 14px;">
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
                resultsEl.innerHTML = `
                    <li class="list-group-item text-danger">
                        Something went wrong
                    </li>`;
                loadingEl.classList.add('d-none');
            });

    }, 300);
});
</script>

</body>

</html>
