<!DOCTYPE html>
<html>
<head>
    <title>Real-time Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<div class="container">
    <h2 class="mb-3">Real-time Search (1M Records)</h2>

    <input type="text" id="search" class="form-control" placeholder="Type to search...">

    <ul class="list-group mt-3" id="results"></ul>
</div>

<script>
let timer = null;

document.getElementById('search').addEventListener('keyup', function () {
    clearTimeout(timer);
    let query = this.value;

    timer = setTimeout(() => {
        if (query.length < 2) {
            document.getElementById('results').innerHTML = '';
            return;
        }

        fetch(`/search?q=${query}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach(item => {
                    html += `<li class="list-group-item">
                        <strong>${item.name}</strong> - ${item.email} (${item.city})
                    </li>`;
                });
                document.getElementById('results').innerHTML = html;
            });
    }, 300);
});
</script>

</body>
</html>
