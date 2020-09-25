<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8" />
<title>福祉Moverモバイル</title>
<meta name="viewport" content="width=device-width, inital-scale=1" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>


<style>
/*
html, body {
    width: 100%;
    height: 100%;
}
*/
#map_101 {
    /* width: 100%; */
    /* height: 600px; */
    border: solid 2px;
}

.ui-header .h1 {
    color: #444;
    /* font-size: 14px; */
    text-shadow: 1px 0 0 #FFF;
}

.ui-footer .h1 {
    color: #444;
    /* font-size: 14px; */
    text-shadow: 1px 0 0 #FFF;
}

</style>

</head>

<body onload="init()">

<div id="page1" data-role="page">

    <div data-role="header">
        <h1 class="h1">メイン画面</h1>
        <div data-role="navbar" data-iconpos="left">
        <ul>
            <li><a href="#page2" id="btnSub1" class="ui-btn-active ui-state-persist" data-icon="user">サブ画面1</a>
            <li><a href="" id="btnSub2" data-icon="location">サブ画面2</a>
            <!-- <li><a href="" data-icon="my-clock">3</a> -->
        </ul>
        </div>
    </div>
    
    <div data-role="content">
        <!-- <p>ページ内容</p> -->
        <!-- <a href="#sub" data-role="button">サブ画面へ</a> -->
        <!-- <p><button data-role="button" id="button1">ボタン</button></p> -->
        <div id="map_101"></div>
    </div>
    
    <!-- <div data-role="footer" class="ui-bar"> -->
        <!-- <h1 class="h1">Copyright 2020 <a href="">エムダブルエス日高</a></h1> -->
<!-- <a href="#" class="ui-btn ui-btn-g">車両情報</a> -->
<!-- <a href="#" class="ui-btn ui-btn-a">利用者情報</a> -->
    <!-- </div> -->
</div>

<div id="page2" data-role="page">
    <div data-role="header">
        <h1>サブ画面1</h1>
        <div data-role="navbar" data-iconpos="left">
        <ul>
            <li><a href="#page1" class="ui-btn-active ui-state-persist" data-icon="navigation">メイン画面</a>
            <li><a href="#page2" data-icon="location">サブ画面2</a>
        </ul>
        </div>
    </div>
</div>

</body>

<script>
    // 初期表示
    function init() {
        
        var post_data = {name:"これはテスト"};
        var res_json = $.ajax({
            type: 'post',
            url: '/sample/position/data.json',
            data: JSON.stringify(post_data),
            contentType: 'application/json',
            dataType: 'json',
            processData: false,
            async: false,
            cache: false,
            scriptCharset: 'utf-8',
            // 成功
            success: function(json_data) {
                return json_data;
            },
            // 失敗
            error: function() {
                //alert("失敗!!!");
            },
            // 完了時
            complete: function() {
            }
        }).responseText;
//        var res_obj = $.parseJSON(res_json);
//        var result = res_obj.result;
        alert(res_json);
        
        // Height指定
        var mapHeight = $(window).height() - 
            $('#page1').find('[data-role="header"]').outerHeight() -
            $('#page1').find('[data-role="footer"]').outerHeight();
        var mapWidth = $(window).width() - 40;
        $("#map_101").height(mapHeight);
        $("#map_101").width(mapWidth);
        //$("#map_101").css('height', mapHeight);
        
        // マップ種類設定
        var Basic_Map = new Array();
        Basic_Map[0] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            continuousWorld: false
        });
        Basic_Map[1] = L.tileLayer('https://cyberjapandata.gsi.go.jp/xyz/std/{z}/{x}/{y}.png', {
            attribution: "<a href='https://maps.gsi.go.jp/development/ichiran.html' target='_blank'>地理院タイル</a>"
        });
        // マップ初期表示
        var map_101 = L.map('map_101').setView([36.302529, 139.413319], 12);
        map_101.addLayer(Basic_Map[0]);
        
        L.control.scale().addTo(map_101);   // スケール表示
        
        var baseMap = {
            "OpenStreetMap": Basic_Map[0],
            "国土地理院 標準地図": Basic_Map[1],
        };
        
        // オーバーレイ設定
        var Over_Layer = new Array();
        Over_Layer[0] = L.tileLayer('http://{s}.tile.stamen.com/{variant}/{z}/{x}/{y}.png', {
            attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, ' +
            '<a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; ' +
            'Map data {attribution.OpenStreetMap}',
            variant: 'toner-hybrid'
        });
        Over_Layer[1] = L.tileLayer('http://{s}.tile.stamen.com/{variant}/{z}/{x}/{y}.png', {
            attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, ' +
            '<a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; ' +
            'Map data {attribution.OpenStreetMap}',
            variant: 'toner-lines'
        });
        var overLay_101 = {
            "Stamen-hybrid": Over_Layer[0],
            "Stamen toner-lines": Over_Layer[1],
        };
        
        // マーカー
        var mapMain = new Array();
        mapMain[0] = L.marker([36.302529, 139.413319]).addTo(map_101);
        mapMain[0].bindPopup("太田デイトレセンター<br>36.302529, 139.413319").openPopup();
        mapMain[1] = L.marker([36.291036, 139.3692]).addTo(map_101);
        
        // イベント
        map_101.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            //alert("[" + lat.toFixed(6) + "," + lng.toFixed(6) + "]");
        });
        
        L.control.layers(baseMap, overLay_101).addTo(map_101);
    }
    
    // 利用者マーカー表示
    $('#btnSub1').bind('click', function() {
        var sub1page;
        if (confirm("利用者マーカー表示")) {
            sub1page='#page2';
        } else {
            sub1page='#page1';
        }
        $.mobile.changePage(sub1page, {transition: 'slidedown'});
    });
    // 車両マーカー表示
    $('#btnSub2').bind('click', function() {
        var sub2page;
        if (confirm("車両マーカー表示")) {
            sub2page='#page2';
        } else {
            sub2page='#page1';
        }
        $.mobile.changePage(sub2page, {transition: 'slidedown'});
    });
</script>

</html>
