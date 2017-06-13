<script src="http://localhost/www/TDUONG/mobile/js/jquery.min.js"></script>
<script src="http://localhost/www/TDUONG/mobile/js/jquery.touchSwipe.min.js"></script>
<script src="http://localhost/www/TDUONG/mobile/js/materialize.js"></script>
<script src="http://localhost/www/TDUONG/mobile/js/jquery.typewatch.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

//        $("#SIDEBACK").addClass("sideright");
	    var btn_sidebar = $("#open-sidebar");
	    var sidebar = $("#SIDEBAR");
//	    var body = $("#BODY, #NAVBAR, #DOCK");
        var body = $("#SIDEBACK");
	    var default_width = $(window).width()*0.75;
	    sidebar.css("max-width", default_width + "px");
        $("#NAVBAR, #DOCK").css("max-width",  $(window).width() + "px");
	    btn_sidebar.click(function (e) {
            e.stopPropagation();
            $(".popup").hide();
            $("#BODY").css("opacity","1");
            if(!$(this).hasClass("active")) {
                $("html, body").css("overflow","hidden");
                body.animate({marginLeft: "75%"}, "fast", "linear");
                sidebar.animate({left: "0"}, "fast", "linear", function() {
                    btn_sidebar.addClass("active");
                });
            } else {
                $("html, body").css("overflow","auto");
                body.animate({marginLeft: "0"}, "fast", "linear");
                sidebar.animate({left: "-75%"}, "fast", "linear", function() {
                    btn_sidebar.removeClass("active");
                });
            }
        });
	    var close_div = $("#SIDEBACK, .close-sidebar");
	    close_div.click(function (e) {
            $("html, body").css("overflow","auto");
            body.animate({marginLeft: "0"}, "fast", "linear");
            sidebar.animate({left: "-75%"}, "fast", "linear", function() {
                btn_sidebar.removeClass("active");
            });
        });

//        $("#NAVBAR ul").delegate("li#menu-chon-mon","click",function() {
//            // $("li#menu-chon-mon").click(function() {
//            if($(this).hasClass("active")) {
//                $("#MAIN").css("opacity","1");
//                $("body, html").css("overflow","auto");
//                $(this).find("ul#menu-chon-xo").stop().fadeOut("fast");
//                $(this).removeClass("active");
//            } else {
//                $(".popup").fadeOut("fast");
//                $('.fixed-action-btn').closeFAB();
//                $("#MAIN").css("opacity","0.1");
//                $("body, html").css("overflow","hidden");
//                $(this).find("ul#menu-chon-xo").stop().slideDown("fast")
//                $(this).addClass("active");
//            }
//        });

<!--	    --><?php //if(isset($_SESSION["lmID"]) && isset($_SESSION["mon"])) { ?>
//        setTimeout(function () {
//            $.ajax({
//                async: true,
//                data: "dulieu=navmon",
//                type: "post",
//                url: "http://localhost/www/TDUONG/mobile/xuly-tongquan/",
//                success: function (result) {
//                    $("#NAVBAR ul").html(result);
//                }
//            });
//        },1000);
//        <?php //} ?>
		
		function center(div_con, div_cha) {
			main_width=$(div_cha).width();
			obj_width=$(div_con).width();
			$(div_con).css("left",(main_width / 2) - (obj_width / 2));
		}
		function center_top(div_con, div_cha) {
			main_height=$(div_cha).height();
			obj_height=$(div_con).height();
			$(div_con).css("margin-top",(main_height / 2) - (obj_height / 2)-15);
		}
        function center_top2(div_con, div_cha) {
            main_height=$(div_cha).height();
            obj_height=$(div_con).height();
            $(div_con).css("margin-top",(main_height / 2) - (obj_height / 2));
        }

        $("div.noi").each(function (index, element) {
            center_top2($(element).find("button"),element);
        });
		
		//center("#MAIN #main-top #main-avata","#MAIN");
		//center("#MAIN .main-div .main-chart4 .chart-info","#MAIN .main-div .main-chart4");
		center("#MAIN .main-div .main-chart5 .chart-info","#MAIN .main-div .main-chart5");
		center("#MAIN .main-div .main-chart3 nav.chart-button","#MAIN");
		$(".multi-div > nav .multi-right > .chart-me").each(function(index, element) {
            p = $(element).find(".chart-info");
			center(p,element);
        });
		$("#MAIN .main-div .main-chart4").each(function(index, element) {
            p = $(element).find(".chart-info");
			center(p,element);
        });
		center("#chart-li1 ul li > div#main-star p","#chart-li1 ul li > div#main-star");  
		
		$(".popup button.btn_ok").click(function() {
			$(".popup").fadeOut("fast");
			$("#BODY").css("opacity","1");
			return false;
		});

        <?php if(stripos($_SERVER['REQUEST_URI'],"/thach-dau/")===false) { ?>
        $("#tb-accept").click(function () {
            me = $(this);
            tdID = me.attr("data-tdID");
            if (tdID != "") {
                $("#thongbao-big").find("> div p.title").html("Đang nhận thách đấu...");
                $("#tb-accept").html("...").removeAttr("data-tdID");
                $("#tb-cancle").hide();
                $.ajax({
                    url: "http://localhost/www/TDUONG/mobile/xuly-thachdau/",
                    async: false,
                    data: "tdID1=" + tdID,
                    type: "post",
                    success: function (result) {
                        tbID = me.attr("data-tbID");
                        if ($.isNumeric(tbID) && tbID != 0) {
                            $.ajax({
                                url: "http://localhost/www/TDUONG/mobile/xuly-thongbao/",
                                async: false,
                                data: "tbID=" + tbID,
                                type: "post",
                                success: function (result) {
                                    $("#thongbao-big").fadeOut("fast");
                                }
                            });
                        }
                    }
                });
            }
            return false;
        });

        $("#tb-cancle").click(function () {
            me = $(this);
            tdID = me.attr("data-tdID");
            if (tdID != "") {
                $("#thongbao-big").find("> div p.title").html("Đang từ chối thách đấu...");
                $("#tb-cancle").html("...").removeAttr("data-tdID");
                $("#tb-accept").hide();
                $.ajax({
                    url: "http://localhost/www/TDUONG/mobile/xuly-thachdau/",
                    async: false,
                    data: "tdID0=" + tdID,
                    type: "post",
                    success: function (result) {
                        tbID = me.attr("data-tbID");
                        if ($.isNumeric(tbID) && tbID != 0) {
                            $.ajax({
                                url: "http://localhost/www/TDUONG/mobile/xuly-thongbao/",
                                async: false,
                                data: "tbID=" + tbID,
                                type: "post",
                                success: function (result) {
                                    $("#thongbao-big").fadeOut("fast");
                                }
                            });
                        }
                    }
                });
            }
            return false;
        });
        <?php } ?>
		
		$(".popup button.btn_tb").click(function() {
			tbID = $(this).attr("data-tbID");
			if($.isNumeric(tbID) && tbID!=0) {
                $(this).closest(".popup").find("button").hide();
				$.ajax({
					url: "http://localhost/www/TDUONG/mobile/xuly-thongbao/",
					async: false,
					data: "tbID1=" + tbID,
					type: "post",
					success: function(result) {
						$(".popup").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				});
			}
		});

//        $("#NAVBAR ul").delegate("li a.access_mon","click",function() {
        $("a.access_mon").click(function () {
            $(".popup").fadeOut("fast");
			$("#baoloi div p.title").html("Bạn không tham gia môn học này, nếu muốn đăng kí học, bạn vui lòng liên hệ thầy Dương theo số 09765.82.764 để đăng kí");
			$("#baoloi").fadeIn("fast");
			$("#BODY").css("opacity","0.1");
		});

//        $("#NAVBAR ul").delegate("li a.nghi_mon","click",function() {
        $("a.nghi_mon").click(function () {
            $(".popup").fadeOut("fast");
			$("#baoloi div p.title").html("Bạn đã nghỉ học hẳn môn này, để đăng ký học lại vui lòng liên hệ thầy Dương 09765.82.764.");
			$("#baoloi").fadeIn("fast");
			$("#BODY").css("opacity","0.1");
		});
	});
	
	var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

	}
</script>

<?php
//	$sms_con=NULL;
//	if(isset($_POST["sms-ok"]) && check_times_mail($_SESSION["ID_HS"])) {
//		if(isset($_POST["sms-con"])) {
//			$sms_con=mysql_escape_string($_POST["sms-con"]);
//		}
//		if(valid_text($sms_con) && isset($_SESSION["ID_HS"])) {
//			$result_loi=get_hs_short_detail2($_SESSION["ID_HS"]);
//			$data_loi=mysqli_fetch_assoc($result_loi);
//			$html=file_get_contents("email.html");
//			$html=str_replace("%title%","Thông báo lỗi mới ".date("j")."/".date("m")."/".date("Y"),$html);
//			$html=str_replace("%sub_title%","$data_loi[fullname] gửi thông báo lỗi!",$html);
//			$html=str_replace("%content%",$sms_con,$html);
//			$html=str_replace("%ps%","$data_loi[cmt] - ".format_dateup($data_loi["birth"]),$html);
//			send_email("no-reply@bgo.edu.vn","mactavish124!@","dinhvankiet124@gmail.com;ceo.blackgold@gmail.com","BGO.EDU.VN: Thông báo lỗi mới từ em $data_loi[fullname] - $data_loi[cmt]",$html,true);
//			update_times_mail($_SESSION["ID_HS"]);
//			$_SESSION["bao-loi"]=1;
//		}
//		header("location:http://localhost/www/TDUONG/mobile/ho-so/");
//		exit();
//	}
?>
