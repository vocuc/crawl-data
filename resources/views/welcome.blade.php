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
<div style="width: 600px;margin: auto">
    @foreach($hotels as $hotel)
        <?php
        if (request()->get('l') == "en") {
            $data = json_decode($hotel->data_detail_en, true);
        } else {
            $data = json_decode($hotel->data_detail, true);
        }
        ?>
        <?php $hotel = json_decode($hotel->data, true); ?>
        <div style="margin-bottom: 50px">
            <div>
                <b>Tên khách sạn: </b>{{$data["data"]["name"]}}
            </div>
            <div><b>Địa chỉ: </b>{{$data["data"]["address"]}}</div>
            <div><b>Giá: </b>
                {{number_format($hotel["hotelInventorySummary"]["cheapestRateDisplay"]["baseFare"]["amount"], 0, ",", ".") }} VNĐ
            </div>
            <div><b>Vị trí: </b>{{$data["data"]["hotelGEO"]["longitude"]}} - {{$data["data"]["hotelGEO"]["latitude"]}}</div>
            <div><b>Dánh giá: </b>{{$data["data"]["starRating"]}}</div>
            <div><b>Sao: </b>{{$hotel["userRating"]}} - {{$data["data"]["userRatingInfo"]}} - (Từ {{$hotel["numReviews"]}} Đánh giá)</div>
            <div>
                <b>Tiện ích: </b>
                @foreach($data["data"]["hotelFacilitiesTagDisplay"] as $facilities)
                    <span style="margin-right: 10px">{{$facilities["name"]}}</span>
                @endforeach
            </div>
            <div>
                <div>
                    <b>Mô tả: </b> {!! $data["data"]["attribute"]["overview"] !!}
                </div>
                @foreach($data["data"]["assets"] as $img)
                    <img src="{{$img["url"]}}" width="50px">
                @endforeach
            </div>
        </div>
    @endforeach
    <div style="text-align: right; margin-top: 20px;float: right">
        {{$hotels->links()}}
    </div>
    <div style="clear: right"></div>
</div>

</body>
</html>
