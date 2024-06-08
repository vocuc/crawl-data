<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
            crossorigin="anonymous"></script>

</head>
<body>
    <div class="container" style="max-width: 800px">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($geos as $geo)
                <tr>
                    <th scope="row">{{$geo->id}}</th>
                    <td>{{$geo->code}}</td>
                    <td>{{$geo->name}}</td>
                    @if($geo->crawl_status == 0)
                        <td>Đang xử lý</td>
                    @endif
                    @if($geo->crawl_status == 1)
                        <td>Đã Hoàn Thành</td>
                    @endif
                    <td>
                        <a href="{{route("deleteGeo", ["code" => $geo->code])}}">Xóa</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form method="post" action="{{route("configs")}}" style="margin-top: 100px">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif
            {{csrf_field()}}
            <div class="row g-3">
                <div class="col">
                    <label>ID</label>
                    <input type="text" name="code" class="form-control">
                </div>
                <div class="col">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <button class="btn btn-success" type="submit">Thêm mới</button>
            </div>
        </form>
    </div>
</body>
</html>
