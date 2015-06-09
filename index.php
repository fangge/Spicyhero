

<!DOCTYPE html>
<html>
<head lang="zh">
    <title>《麻辣英雄》麻辣颜值大比拼</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=640,user-scalable=no">
    <meta name="apple-touch-fullscreen" content="YES">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="pragram" content="no-cache">
    <link rel="stylesheet" href="css/com.css?20150608"/>
    <script src="jquery.js" type="text/javascript"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?2e0cf0cbc4f337f4478d7a55aa04e472";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
<div style="display: none"><img src="http://party.mlyx.syyx.com/img/share.jpg" alt=""/></div>
<!--上传前-->
<!-- 分享内容 start-->
<div style="display: none">
    <div id="share-title">麻辣颜值大比拼</div>
    <div id="share-des">刷颜值也能拿红包！原来我的脸这么值钱！快来看看你的颜值值多少钱吧！</div>
    <div id="share-img">http://party.mlyx.syyx.com/img/share.jpg</div>

</div>
<!-- 分享内容 end-->
<div class="in" id="loading">
    <div class="floatingCirclesG">
        <div class="f_circleG" id="frotateG_01">
        </div>
        <div class="f_circleG" id="frotateG_02">
        </div>
        <div class="f_circleG" id="frotateG_03">
        </div>
        <div class="f_circleG" id="frotateG_04">
        </div>
        <div class="f_circleG" id="frotateG_05">
        </div>
        <div class="f_circleG" id="frotateG_06">
        </div>
        <div class="f_circleG" id="frotateG_07">
        </div>
        <div class="f_circleG" id="frotateG_08">
        </div>
    </div>
</div>

<div class="wrap wrap1">
    <img class="intro tip" src="img/intro.png"/>
    <a class="btn" id="btn1"><img src="img/btn1.png"/></a>
</div>
<div class="wrap wrap2">
    <img class="btn upload" src="img/btn2.png"/>
    <form enctype="multipart/form-data" method="post" name="upform"><input id="uploadInput" name="upfile" type="file"  capture="camera" accept="image/*" class="chuan btn" onchange="upform.submit()" /></form>
</div>
<script type="text/javascript">
    var title = $('#share-title').text();
    var desc = $('#share-des').text();
    var link = window.location.href;
    var imgUrl = $('#share-img').text();

    wx.config({
        appId: 'wx445c5cd15c5d5184',
        timestamp: 1433857187,
        nonceStr: 'z6uDNvkg0fcYQyxa',
        signature: '46cba085d61c47ab215325e21397ac4002edec30',
        jsApiList: [
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo'
        ]
    });
    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: title,
            desc: desc,
            link: link,
            imgUrl: imgUrl,
            trigger: function () {
                //alert('用户点击发送给朋友');
            },
            success: function () {
                //alert('已分享');
            },
            cancel: function () {
                //alert('已取消');
            }
        });
        wx.onMenuShareTimeline({
            title: desc,
            link: link,
            imgUrl: imgUrl,
            trigger: function () {
                //alert('用户点击分享到朋友圈');
            },
            success: function () {
                //alert('已分享');
            },
            cancel: function () {
                //alert('已取消');
            }
        });
        wx.onMenuShareQQ({
            title: title,
            link: link,
            desc: desc,
            imgUrl: imgUrl,
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareWeibo({
            title: title,
            link: link,
            desc: desc,
            imgUrl: imgUrl,
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
    wx.error(function(res){
        //alert(res);
    });
    $(window).on("load", function(){
        //loading
        $("#loading").removeClass("in");
        $('.wrap1').addClass("in");
        $('.intro').addClass("on")
    });
    $(function(){
        $('.tip').on('click',function(){
            $(this).removeClass('on')
        })

        $('#btn1').on('click',function(){
            $('.wrap1').removeClass('in')
            $('.wrap2').addClass('in')
        })

    })

    function getQueryString(name) {//获取传导参数
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
    var upload = getQueryString('upload');
    if(upload){
        $("#loading,.wrap1").hide();
        $('.wrap2').addClass('in')
    }
</script>
</body>
</html>
