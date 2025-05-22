<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sách</title>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📚 Danh sách sách</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Tên sách</th>
                <th>Mã sách</th>
                <th>Tác giả</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    <td>{{ $book->bookName }}</td>
                    <td>{{ $book->bookCode }}</td>
                    <td>{{ $book->bookAuthor }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
