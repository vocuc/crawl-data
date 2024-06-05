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
<body class="antialiased">
<table class="table">
    <thead>
    <tr>
        <th scope="col">khách sạn</th>
        <th>Giá</th>
        <th scope="col">region</th>
        <th scope="col">Rate</th>
        <th scope="col">Sao</th>
        <th scope="col">Tag</th>
        <th>Ảnh</th>
    </tr>
    </thead>
    <tbody>
    @foreach($hotels as $hotel)
        <?php $data = json_decode($hotel->data, true);?>
        <tr>
            <th>{{$data["name"]}}</th>
            <th>{{number_format($data["hotelInventorySummary"]["cheapestRateDisplay"]["baseFare"]["amount"], 0, ",", ".") }} VNĐ</th>
            <td>{{$data["region"]}}</td>
            <td>{{$data["userRating"]}}({{$data["numReviews"]}} Đánh giá) {{$data["userRatingInfo"]}}</td>
            <td>{{$data["starRating"]}}</td>
            <td>
                @foreach($data["hotelFeatures"] as $tag)
                    <div style="margin-right: 10px">{{$tag["text"]}}</div>
                @endforeach
            </td>
            <td>
                @foreach($data["imageUrls"] as $img)
                    <?php $img = explode("?", $img); ?>
                    <img src="{{$img[0]}}?src=imagekit&amp;tr=dpr-3,c-at_max,h-168,q-40,w-968" width="50px">
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
