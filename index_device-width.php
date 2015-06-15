<?php
/**
 * Created by MrF.
 * Date: 2015/6/8
 * Time: 20:38
 */
header("Content-type:text/html;charset=utf-8");
//上传文件类型列表
$uptypes=array(
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
);
$max_file_size=5000000;     //上传文件大小限制, 单位BYTE
$destination_folder="uploads/"; //上传文件路径
$scale = 10;//压缩文件比例
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $file = $_FILES["upfile"];
    if (!is_uploaded_file($file["tmp_name"])){
        echo "<script>alert('图片不存在!');window.location.href='index_device-width.php';</script>";exit;
    }
    if($max_file_size < $file["size"]){
        echo "<script>alert('文件太大，请换一张图片');window.location.href='index_device-width.php';</script>";exit;
    }
    if(!in_array($file["type"], $uptypes)){
        echo "<script>alert('".$file["type"]."文件类型不符!');window.location.href='index_device-width.php';</script>";exit;
    }
    if(!file_exists($destination_folder)){
        mkdir($destination_folder);
    }

    $filename=$file["tmp_name"];
    //IOS端判断图片exif信息，进行图片翻转
    $image = imagecreatefromstring(file_get_contents($filename));
    $exif = exif_read_data($filename);
    if(!empty($exif['Orientation'])) {
        switch($exif['Orientation']) {
            case 8:
                $image = imagerotate($image,90,0);
                break;
            case 3:
                $image = imagerotate($image,180,0);
                break;
            case 6:
                $image = imagerotate($image,-90,0);
                break;
        }
    }
    $image_size = getimagesize($filename);
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    $destination = $destination_folder.time().".".$ftype;
    $destination2 = $destination_folder.time()."_thumb.".$ftype;
    imagejpeg($image, $destination, 60);
//    if (file_exists($destination) && $overwrite != true){
//        echo "<script>alert('同名文件已经存在了');window.location.href='index.php';</script>";exit;
//    }
//    if(!move_uploaded_file ($filename, $destination)){
//        echo "<script>alert('移动文件出错');</script>";exit;
//    }

    //增加排行榜小图片截图
    require 'imgthumb.class.php';
    $resizeimage2 = new resizeimage($destination, 65, 70, "1",$destination2);

    ImageDestroy($image);
}
?>

<!DOCTYPE html>
<html>
<head lang="zh">
    <title>《麻辣英雄》麻辣颜值大比拼</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
    <meta name="apple-touch-fullscreen" content="YES">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="pragram" content="no-cache">
    <link rel="stylesheet" href="css/comv2.css?20150615"/>
    <script src="jquery.js" type="text/javascript"></script>
    <script src="sapp.js" type="text/javascript"></script>
    <script type="text/javascript">
        var service_url = "http://party.mlyx.syyx.com:8080/";
    </script>
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
<?php if (!($_SERVER['REQUEST_METHOD'] == 'POST')){?>
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
        sapp.fill({
            target : ".wrap,.tip",
            width : 640,
            height : 1007,
            mode : "contain"
        })
        var title = $('#share-title').text();
        var desc = $('#share-des').text();
        var link = window.location.href;
        var imgUrl = $('#share-img').text();


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
<?php }else{?>
    <!--上传后-->
    <!-- 分享内容 start-->
    <div style="display: none">
        <div id="share-title">麻辣颜值大比拼</div>
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
    <div class="wrap wrap3">
        <div class="img-wrap">
            <img src="<?php echo $destination;?>"/>
        </div>
        <a class="btn" id="btn2"><img src="img/btn8.png" alt=""/></a>
        <a class="btn btn3" href="./index_device-width.php?upload=true"><img src="img/btn5.png" alt=""/></a>

    </div>
    <div class="wrap wrap4">
        <div class="img-wrap">
            <img src="<?php echo $destination;?>"/>
        </div>
        <!--    <p class="t t1">9000</p>-->
        <!---->
        <!--    <p class="t t2">XX变量高校排名:1280</p>-->
        <!---->
        <!--    <p class="t t3">1200</p>-->

        <p class="t t4" id="score"></p>

        <!--    <p class="t t5">0</p>-->

        <p class="t t6" id="des"></p>

        <a class="btn" id="btn5"><img src="img/btn11.png" alt=""/></a>
        <img class="tip tip2" src="img/tip3.png" id="rewardOver"/>
        <div class="tip2 login">
            <input type="text" id="school" value="请输入学校名称" placeholder="请输入学校名称"  onfocus="if(this.value=='请输入学校名称'){this.value='';}" onblur="if(this.value==''){this.value='请输入学校名称';}" />
            <input type="text" id="phone" value="请输入手机号码" placeholder="请输入手机号码"  onfocus="if(this.value=='请输入手机号码'){this.value='';}" onblur="if(this.value==''){this.value='请输入手机号码';}" />
            <a class="btn" id="sub-phone"><img src="img/submit-phone.png"/></a>
        </div>
        <div class="tip tip12">
            <a class="btn btn10"><img src="img/btn6.png" alt=""/></a>
            <a class="btn btn11"><img src="img/btn7.png" alt=""/></a>
        </div>
<!--        <div class="tip tip13">-->
<!--            <a class="btn btn10"><img src="img/btn6.png" alt=""/></a>-->
<!--            <a class="btn btn11"><img src="img/btn7.png" alt=""/></a>-->
<!--        </div>-->
    </div>
    <!--    6.17以前-->
    <div class="wrap wrap5">
        <p class="t t1">9000</p>

        <p class="t t2">XX变量高校排名:1280</p>

        <p class="t t3">1200</p>

        <p class="t t9">可刷颜值次数：<span>3</span></p>

        <p class="input-txt1" id="cardNo"></p>
        <p class="input-txt2" id="idCode" ></p>

        <p class="t t7">1.0元</p>
        <p class="t t8">30</p>

        <a class="btn btn6"><img src="img/btn14.png" alt=""/></a>
        <a class="btn btn7"><img src="img/btn12.png" alt=""/></a>
        <a class="btn btn8"><img src="img/btn15.png" alt=""/></a>

        <img class="tip tip5" src="img/tip6.png" alt=""/>
        <div class="tip tip6">
            <a class="btn btn9"><img src="img/btn18.png" alt=""/></a>
        </div>
        <img class="tip tip7" src="img/tip9.jpg" alt=""/>
        <a class="btn btn4"><img src="img/btn10.png" alt=""/></a>

    </div>

    <div class="wrap wrap6">
        <p class="t t1">9000</p>

        <p class="t t2">XX变量高校排名:1280</p>

        <p class="t t3">1200</p>

        <p class="t t9">可刷颜值次数：<span>3</span></p>

        <p class="t t10">你的三个红包，<br>
            已领取完毕，<br>
            谢谢!</p>
        <p class="t t11"><!--  今天的红包已经<br>
        领完，明天请早<br>
        ~谢谢!  -->今天的红包被抢光<br>
            啦！明天请早~也可<br>
            以继续刷颜值积分哦</p>

        <a class="btn btn6"><img src="img/btn14.png" alt=""/></a>
        <a class="btn btn7"><img src="img/btn12.png" alt=""/></a>
        <a class="btn btn8"><img src="img/btn15.png" alt=""/></a>
        <!--  <a class="btn btn4"><img src="img/btn10.png" alt=""/></a>  -->
        <img class="tip tip5" src="img/tip6.png" alt=""/>
        <div class="tip tip6">
            <a class="btn btn9"><img src="img/btn18.png" alt=""/></a>
        </div>
        <img class="tip tip7" src="img/tip9.jpg" alt=""/>
        <a class="btn btn4"><img src="img/btn10.png" alt=""/></a>
    </div>
    <!--  高校颜值排行-->
    <div class="tip tip4">
        <h3>
            我为<span id="school_name"></span>贡献颜值:<span id="person_face_value_total"></span>
        </h3>
        <ul id="school_ranking_html">
            <li>1.北京电影学院<span>颜值总分:158950</span></li>
            <li>2.北京舞蹈学院<span>颜值总分:14854</span></li>
            <li>3.中央戏剧学院<span>颜值总分:12858</span></li>
            <li>4.上海戏剧学院<span>颜值总分:10954</span></li>
            <li>5.北京外国语大学<span>颜值总分:9954</span></li>
            <li>6.上海外国语大学<span>颜值总分:8895</span></li>
            <li>7.湖南师范大学<span>颜值总分:6775</span></li>
            <li>8.上海音乐学院<span>颜值总分:5754</span></li>
            <li>9.中央民族大学<span>颜值总分:4528</span></li>
            <li>10.北京大学<span>颜值总分:3895</span></li>

        </ul>
    </div>
    <!-- 个人颜值排行-->
    <div class="tip tip3">
        <ul id="personage_ranking_html">
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
            <li>
                <img src="test.jpg"/>

                <p class="list-t1">1</p>

                <p class="list-t2">北京电影学院</p>

                <p class="list-t3">10000</p>
            </li>
        </ul>
    </div>
    <!--    6.17以后-->
    <!--<div class="wrap wrap5_2">-->
    <!---->
    <!--</div>-->

    <script type="text/javascript">
        sapp.fill({
            target : ".wrap,.tip",
            width : 640,
            height : 1007,
            mode : "contain"
        })
        $(window).on("load", function(){
            //loading
            $("#loading").removeClass("in");
            $('.wrap3').addClass("in");
        });


        var desc,tArrary,hongbaoValue;
        //var link,link2;
        var title = $('#share-title').text();
        var imgUrl = $('#share-img').text();
        var storage = window.localStorage;
        //65-98
        var max=95;
        var min=28;
        var score = Math.floor(Math.random()*(max-min+1)+min);
        $('#score').text(score)
        if(score>=28 && score<=70){
            tArrary = [
                "美过。我走了，你们聊",
                "有话好好说，不发自拍还是朋友",
                "好像很准的样子，我竟无颜以对"
            ]
            desc = tArrary[Math.floor(Math.random()*2)];
            hongbaoValue = 1;
        }else if(score>=71 && score<=85){
            tArrary = [
                "麻麻说学习好就有好（看）对象（深思脸）",
                "茫茫人海路人甲一枚",
                "大家都夸我的脸可替代性极强"
            ]
            desc = tArrary[Math.floor(Math.random()*2)];
            hongbaoValue = 2;
        }else if(score>=86 && score<=95){
            tArrary = [
                "哼人家只想安静地做一个花瓶啦",
                "人见人爱花见花开，说的就是你吧",
                "传说中可以靠脸吃饭的人"
            ]
            desc = tArrary[Math.floor(Math.random()*2)];
            hongbaoValue = 3;
        }
        $('#des').html('').text(desc);

        $(function(){
            $('.tip').on('click',function(){
                $(this).removeClass('on')
            })

            $('#btn2').on('click',function(){
                $('.wrap3').removeClass('in')
                $('.wrap4').addClass('in')
            })

            //个人排行榜
            $('.btn4,.btn10').on('click',function(){
                getPersonageRanking();
                $('.tip3').addClass('on')
            })
            //高校排行榜
            $('.btn7,.btn11').on('click',function(){
                getSchoolRanking();
                $('.tip4').addClass('on')
            })
            //红包使用说明按钮
            $('.btn6').on('click',function(){
                $('.tip6').addClass('on')
            })
            //点击查看大图按钮
            $('.btn9').on('click',function(){
                $('.tip7').addClass('on')
            })
            //取消弹层
            //$('.tip7').on('click',function(){
            //$(this).removeClass('on')
            //$('.tip6').removeClass('on')
            //})
            //分享麻辣颜值按钮
            $('.btn8').on('click',function(){
                $('.tip5').addClass('on')
            })

            //判断用户是否二次登陆

            $('#btn5').on('click',function(){

                if (!storage.getItem("pageLoadCount")) {//若为初次登陆
                    storage.setItem("pageLoadCount",1);
                    //打开登陆弹层
                    $('.login').addClass('on');

                }else{//若为二次登陆
                    storage.pageLoadCount = parseInt(storage.getItem("pageLoadCount")) + 1;
                    if(storage.getItem("phoneNum") && storage.getItem("schoolName")){
                        //storage.phoneNum = storage.getItem("phoneNum");
                        //storage.schoolName = storage.getItem("schoolName");
                        var phoneNum = parseInt(storage.getItem("phoneNum"));
                        var schoolName = storage.getItem("schoolName");
                        submitPhone(phoneNum,schoolName)
                    }else{
                        $('.login').addClass('on');
                    }
                }
            })

            $('#sub-phone').on('click',function(){
                var phoneNum = $.trim($('#phone').val());
                var schoolName = $.trim($('#school').val());

                if(/^1[3|4|5|8|7][0-9]\d{4,8}$/.test(phoneNum)){
                    if(schoolName != ''){
                        storage.setItem("phoneNum",phoneNum)
                        storage.setItem("schoolName",schoolName);
                        submitPhone(phoneNum,schoolName)
                    }else{
                        alert('请输入学校名！')
                    }
                }else{
                    alert('请输入正确手机号！')
                }
            })

        })

        //新用户信息登记 和 判断用户可玩次数
        function submitPhone(phoneNum,schoolName){
            var p =phoneNum;
            var s = schoolName;
            var data = "{'person_id':'"+p+"','school_name':'"+s+"'}";
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"submit_person.ashx?data="+encodeURIComponent(data),
                success:function(data){
                    $('.login').removeClass('on');
                    if(data != null){
                        var check_chances = data.can_check_chances;
                    }

                    if(check_chances>0){
                        submitFaceValue(p,s,score);
                        //判断领红包次数
                        getRewardTimes(p);
                    }else{
                            //所有次数都用完
                            $('.tip12').addClass('on')
//                        else{
//                            //未分享还可以有次数
//                            $('.tip13').addClass('on')
//
//                        }
                    }
                },
                error:function(){
                    alert('服务器故障1，请稍后...')
                }
            })
        }

        //$('#school_name').text(storage.getItem("schoolName"));
        //$('#person_face_value_total').text(parseInt(storage.getItem("personFaceValueTotalLocal")));
        $('#school_name').text(localStorage.schoolName);
        if(localStorage.personFaceValueTotalLocal != null){$('#person_face_value_total').text(parseInt(localStorage.personFaceValueTotalLocal));}
        //颜值结果录入数据库，并返回相关信息
        function submitFaceValue(phoneNum,schoolName,personFaceValue){
            var p = phoneNum;
            var s = schoolName;
            var pf = personFaceValue;
            var data = "{'person_id':'"+p+"','person_face_value':'"+pf+"','img_url':'<?php echo $destination2;?>'}";
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"submit_facevalue.ashx?data="+encodeURIComponent(data),
                success:function(data){
                    var personFaceValueTotal = data.person_face_value;
                    var canCheckChancesNew = data.can_check_chances;
                    var personFaceRanking = data.person_face_ranking;
                    var schoolFaceRanking = data.school_face_ranking;
                    $('.t1').text(personFaceValueTotal);
                    $('.t9').html("可刷颜值次数：<span>"+canCheckChancesNew+"</span>");
                    $('.t3').text(personFaceRanking);
                    $('.t2').text(s+"排名:"+schoolFaceRanking);
                    $('#school_name').text(s);
                    $('#person_face_value_total').text(personFaceValueTotal);
                    //storage.setItem("personFaceValueTotalLocal",personFaceValueTotal);
                    localStorage.personFaceValueTotalLocal = personFaceValueTotal;
                    localStorage.personFaceRanking = personFaceRanking;
                    localStorage.schoolFaceRanking = schoolFaceRanking;
                },
                error:function(){
                    alert('服务器故障2，请稍后...')
                }
            })
        }

        //判断领红包次数
        function getRewardTimes(phoneNum){
            //$('.wrap4').removeClass('in');
            //$('.wrap5').addClass('in');
            var data = "{'person_id':'"+phoneNum+"'}";
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"get_isenable_hongbao.ashx?data="+encodeURIComponent(data),
                success:function(data){
                    if(data != null){
                        if(data.hongbao_chances > 0){
                            $('.wrap4').removeClass('in');
                            $('.wrap5').addClass('in');
                            getReward(phoneNum,hongbaoValue)
                        }else{
                            rewardTimesOver();
                        }
                    }
                },
                error:function(){
                    alert('服务器故障3，请稍后...')
                }
            })
        }

        //领红包
        function getReward(phoneNum,hongbaoValueTwo){
            var data = "{'person_id':'"+phoneNum+"','hongbao_value':'"+hongbaoValueTwo+"'}";
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"get_hongbao.ashx?data="+encodeURIComponent(data),
                success:function(data){
                    if(data != null){
                        var carNo = data.hongbao_cardNo;
                        var idCode = data.hongbao_idCode;
                    }
                    if(carNo && idCode){
                        $('#cardNo').text(carNo); //JSON.stringify(carNo)
                        $('#idCode').text(idCode);
                        $('.t7').text(hongbaoValue+".0元");
                    }else{
                        rewardOver();
                    }
                },
                error:function(){
                    alert('服务器故障4，请稍后...')
                }
            })
        }

        //今日红包领取次数用完
        function rewardTimesOver(){
            $('.wrap4').removeClass('in');
            $('.wrap6').addClass('in');
            $('.t11').hide();
            $('.t10').show();
        }

        //今日红包领完
        function rewardOver(){
            $('.wrap4').removeClass('in');
            $('.wrap6').addClass('in');
            $('.t10').hide();
            $('.t11').show();
        }
        /**参数说明：
         * 根据长度截取先使用字符串，超长部分追加…
         * str 对象字符串
         * len 目标字节长度
         * 返回值： 处理结果字符串
         */
        function cutString(str, len) {
            //length属性读出来的汉字长度为1
            if(str.length*2 <= len) {
                return str;
            }
            var strlen = 0;
            var s = "";
            for(var i = 0;i < str.length; i++) {
                s = s + str.charAt(i);
                if (str.charCodeAt(i) > 128) {
                    strlen = strlen + 2;
                    if(strlen >= len){
                        return s.substring(0,s.length-1) + "...";
                    }
                } else {
                    strlen = strlen + 1;
                    if(strlen >= len){
                        return s.substring(0,s.length-2) + "...";
                    }
                }
            }
            return s;
        }
        //个人排行榜
        function getPersonageRanking(){
            var sname;
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"get_personage_ranking.ashx",
                success:function(data){
                    $("#personage_ranking_html").html("");
                    $.each(data,function(i){
                        var n=i+1;
                        sname = cutString(''+data[i].school_name,14);
                        $("#personage_ranking_html").append("<li><img src='"+data[i].img_url+"'/><p class='list-t1'>"+n+"</p><p class='list-t2'>"+sname+"</p><p class='list-t3'>"+data[i].person_face_value+"</p></li>");
                    });
                },
                error:function(){
                    alert('服务器故障5，请稍后...')
                }
            })
        }


        //高校排行榜
        function getSchoolRanking(){
            var sname;
            $.ajax({
                type:"GET",
                dataType:"json",
                url:service_url+"get_school_ranking.ashx",
                success:function(data){
                    $("#school_ranking_html").html("");
                    $.each(data,function(i){
                        var n=i+1;
                        sname = cutString(''+data[i].school_name,14);
                        $("#school_ranking_html").append("<li>"+n+"."+sname+"<span>颜值总分:"+data[i].school_face_value+"</span></li>");
                    });
                },
                error:function(){
                    alert('服务器故障6，请稍后...')
                }
            })
        }

    </script>
<?php } ?>
</body>
</html>
