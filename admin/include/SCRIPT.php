<script src="http://localhost/www/TDUONG/admin/js/jquery.min.js"></script>
<script src="http://localhost/www/TDUONG/admin/js/jquery.typewatch.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#bangluong").click(function () {
            if(confirm("Load dữ liệu sẽ mất nhiều thời gian!")) {
                return true;
            } else {
                return false;
            }
        });

        $("#da-chi").click(function () {
            $.ajax({
                async: true,
                data: "action=tien-chi-list",
                type: "post",
                url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                success: function(result) {
                    $(".popup").fadeOut("fast");
                    $("#popup-tien-chi > div > table").html("<tr><td colspan='2'><input type='submit' class='submit add-chi' value='Thêm' /></td></tr><tr style='background:#3E606F'><th><span>Nội dung</span></th><th style='width:15%;'><span></span></th></tr>"+result);
                    $("#popup-tien-chi").fadeIn("fast");
                }
            });
        });

        $("#chi-ok").click(function () {
            var money = $("#tien-chi-add").val();
            var content = $("#text-chi-add").val();
            if(money != "" && content != "") {
                $.ajax({
                    async: false,
                    data: "money=" + money + "&content=" + content,
                    type: "post",
                    url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                    success: function(t) {
                        location.reload();
                    }
                });
            } else {
                alert("Dữ liệu không chính xác!");
            }
        });

        $("#CHANGE_MON").change(function() {
            var lmID = $(this).val();
			if($.isNumeric(lmID) && lmID!=0) {
				$.ajax({
					async: false,
					data: "lmID=" + lmID,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-mon/",
					success: function(t) {
						location.reload();
					}
				});
			}
        });

        $("#CHANGE_BIEUDO").change(function() {
            var url_web = $(this).val();
            if(url_web != "" && url_web !=" " && url_web != "#") {
                window.open(url_web, "_blank");
                $(this).find("option:first-child").prop("selected",true);
            }
        });

        $("#REMIND").delegate("a#special-close","click",function(){
            var hsID = $(this).attr("data-hsID");
            if($.isNumeric(hsID) && hsID!=0) {
                $.ajax({
                    async: false,
                    data: "hsID_special=" + hsID,
                    type: "post",
                    url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                    success: function(result) {
                        $("#REMIND").fadeOut("fast");
                    }
                });
            }
        });

        $("#REMIND").delegate("a#special-hide","click",function(){
            $("#REMIND").fadeOut("fast");
        });
		
		$("#open-note").click(function() {
			get_list_note();
		});
		
		function get_list_note() {
			$.ajax({
				async: true,
				data: "action=note-list",
				type: "post",
				url: "http://localhost/www/TDUONG/admin/xuly-mon/",
				success: function(result) {
					$(".popup").fadeOut("fast");
					$("#popup-list > div > table").html("<tr><td colspan='3'><input type='submit' class='submit add-note' value='Thêm note' /><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/admin/note/'\" style='float: right;' value='Tất cả' /></td></tr><tr style='background:#3E606F'><th><span>Nội dung</span></th><th style='width:15%;'><span>Tên</span></th><th style='width:10%;'><span></span></th></tr>"+result);
					get_list_idea();
                    $("#popup-list").fadeIn("fast");
				}
			});
		}

		function get_list_idea() {
            $.ajax({
                async: true,
                data: "action=idea-list",
                type: "post",
                url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                success: function(result) {
                    $("#popup-idea > div > table").html("<tr><td colspan='2'><input type='submit' class='submit add-idea' value='Thêm' /><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/admin/idea/'\" style='float: right;' value='Tất cả' /></td></tr><tr style='background:#3E606F'><th><span>Nội dung</span></th><th style='width:10%;'><span></span></th></tr>"+result);
                    $("#popup-idea").fadeIn("fast");
                }
            });
        }

        $("#popup-list > div > table, #popup-idea > div > table").delegate("tr","click",function () {
            if($(this).hasClass("clicked") && !$(this).hasClass("edited")) {
                var content = $(this).find("td:eq(0) span").text();
                $(this).find("td:eq(0)").html("<textarea class='input edit-note' rows='5'>"+content+"</textarea>");
                $(this).addClass("edited");
            } else {
                $(this).addClass("clicked");
            }
        });

        $("#popup-list > div > table").delegate("tr td textarea","click",function() {
            $("#popup-list > div > table tr td textarea").typeWatch({
                captureLength: 3,
                callback: function (value) {
                    var me = $(this);
                    var nID = me.closest("td").attr("data-nID");
                    var note = value;
                    note = note.replace(/\+/g,"-");
                    if ($.isNumeric(nID) && nID!=0 && note!="" && note!="none") {
                        $.ajax({
                            async: false,
                            data: "note_edit=" + note + "&nID3=" + nID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                            success: function(result) {
                                me.closest("tr").removeClass("clicked").removeClass("edited");
                                me.closest("td").html("<span>"+result+"</span>");
                            }
                        });
                    }
                }
            });
        });

        $("#popup-idea > div > table").delegate("tr td textarea","click",function() {
            $("#popup-idea > div > table tr td textarea").typeWatch({
                captureLength: 3,
                callback: function (value) {
                    var me = $(this);
                    var oID = me.closest("td").attr("data-oID");
                    var note = value;
                    note = note.replace(/\+/g,"-");
                    if ($.isNumeric(oID) && oID!=0 && note!="" && note!="none") {
                        $.ajax({
                            async: false,
                            data: "idea_edit=" + note + "&oID3=" + oID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                            success: function(result) {
                                me.closest("tr").removeClass("clicked").removeClass("edited");
                                me.closest("td").html("<span>"+result+"</span>");
                            }
                        });
                    }
                }
            });
        });

        $("#popup-tien-chi > div > table, #MAIN #main-mid .status .table").delegate("tr td input.add-chi","click",function() {
            $(".popup").fadeOut("fast");
            $("#popup-chi").fadeIn("fast");
            $("#tien-chi-add, #text-chi-add").val("");
        });
		
		$("#popup-list > div > table, #MAIN #main-mid .status .table").delegate("tr td input.add-note","click",function() {
			$(".popup").fadeOut("fast");
			$("#text-add, #sign-add, #title-add").val("");
			$("#popup-note").fadeIn("fast");
            $("#popup-note > p").html("Thêm note");
            $("button#note-ok").attr("data-type","note").html("Thêm note");
		});

        $("#popup-idea > div > table, #MAIN #main-mid .status .table").delegate("tr td input.add-idea","click",function() {
            $(".popup").fadeOut("fast");
            $("#text-add, #sign-add, #title-add").val("");
            $("#popup-note").fadeIn("fast");
            $("#popup-note > p").html("Thêm ý tưởng");
            $("button#note-ok").attr("data-type","idea").html("Thêm ý tưởng");
        });
		
		$("#popup-list > div > table, #MAIN #main-mid .status .table").delegate("tr td:last-child span i.fa-square-o","click",function() {
			me = $(this);
			nID = $(this).attr("data-nID");
			if(nID!=0 && $.isNumeric(nID)) {
				$.ajax({
					async: false,
					data: "nID=" + nID + "&status=1",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-mon/",
					success: function(result) {
						me.removeClass("fa-square-o").addClass("fa-check-square-o");
						me.closest("tr").css("opacity","0.3");
					}
				});
			}
		});
		
		$("#popup-list > div > table, #MAIN #main-mid .status .table").delegate("tr td:last-child span i.fa-check-square-o","click",function() {
			me = $(this);
			nID = $(this).attr("data-nID");
			if(nID!=0 && $.isNumeric(nID)) {
				$.ajax({
					async: false,
					data: "nID=" + nID + "&status=0",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-mon/",
					success: function(result) {
						me.removeClass("fa-check-square-o").addClass("fa-square-o");
						me.closest("tr").css("opacity","1");
					}
				});
			}
		});

        $("#popup-list > div > table, #MAIN #main-mid .status .table").delegate("tr td:last-child span i.fa-trash","click",function() {
            if(confirm("Bạn có chắc chắn muốn xoá ghi chú này?")) {
                me = $(this);
                nID = $(this).attr("data-nID");
                if(nID!=0 && $.isNumeric(nID)) {
                    $.ajax({
                        async: false,
                        data: "nID0=" + nID,
                        type: "post",
                        url: "http://hehe.bgo.edu.vn/xuly-mon/",
                        success: function(result) {
                            me.closest("tr").fadeOut("fast");
                        }
                    });
                }
            }
        });
		
		$("#popup-idea > div > table, #MAIN #main-mid .status .table").delegate("tr td:last-child span i.fa-close","click",function() {
			if(confirm("Bạn có chắc chắn muốn xoá ý tưởng này?")) {
				me = $(this);
				oID = $(this).attr("data-oID");
				if(oID!=0 && $.isNumeric(oID)) {
					$.ajax({
						async: false,
						data: "oID0=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-mon/",
						success: function(result) {
							me.closest("tr").fadeOut("fast");
						}
					});
				}
			}
		});

        $("#popup-tien-chi > div > table, #MAIN #main-mid .status .table").delegate("tr td:last-child span i.fa-close","click",function() {
            if(confirm("Bạn có chắc chắn muốn xoá ý tưởng này?")) {
                me = $(this);
                oID = $(this).attr("data-oID");
                if(oID!=0 && $.isNumeric(oID)) {
                    $.ajax({
                        async: false,
                        data: "oID0=" + oID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                        success: function(result) {
                            me.closest("tr").fadeOut("fast");
                        }
                    });
                }
            }
        });
		
		$("div.popup-close").click(function() {
			$(".popup").fadeOut("fast");
            $("#BODY").css("opacity","1");
		});

        $("#gim-ok").click(function() {
            content = $("#text-add").val().trim();
            name = $("#sign-add").val().trim();
            hs = $("#hs-add").val().trim();
            add_note(content,name,1,hs);
        });
		
		$("#note-ok").click(function() {
			var content = $("#text-add").val().trim();
			var name = $("#sign-add").val().trim();
//            hs = $("#hs-add").val().trim();
            var title = $("#title-add").val().trim();
            var hs="";
            var type=$("button#note-ok").attr("data-type");
            if(type=="note") {
                add_note(title, content, name, 0, hs);
            } else if(type=="idea") {
                add_idea(title, content, name);
            } else {
                alert("Không rõ???");
            }
		});



        function add_idea(title,content,name) {
            if(title!="" && content!="" && name!="") {
                $.ajax({
                    async: false,
                    data: "title3=" + title + "&content3=" + content + "&name3=" + name,
                    type: "post",
                    url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                    success: function(result) {
                        $(".popup").fadeOut("fast");
                        get_list_idea();
                    }
                });
            } else {
                alert("Vui lòng nhập đầy đủ thông tin!");
            }
        }

        function add_note(title,content,name,loai,hs) {
            if(title!="" && content!="" && name!="") {
                $.ajax({
                    async: false,
                    data: "title=" + title + "&content=" + content + "&name=" + name + "&loai=" + loai + "&hs=" + hs,
                    type: "post",
                    url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                    success: function(result) {
                        get_list_note();
                    }
                });
            } else {
                alert("Vui lòng nhập đầy đủ thông tin!");
            }
        }
		
		function center(div_con, div_cha) {
			main_width=$(div_cha).width();
			obj_width=$(div_con).width();
			$(div_con).css("left",(main_width / 2) - (obj_width / 2));
		}
		
		function center_top(div_con, div_cha) {
			main_height=$(div_cha).height();
			obj_height=$(div_con).height();
			$(div_con).css("margin-top",(main_height / 2) - (obj_height / 2));
		}
		
		$("#MAIN .main-div .main-chart4").each(function(index, element) {
            p = $(element).find(".chart-info");
			center(p,element);
        });
		center("#MAIN .main-div .main-chart5 .chart-info","#MAIN .main-div .main-chart5");
		//center(".multi-div > nav .multi-right > .chart-me .chart-info",".multi-div > nav .multi-right > .chart-me");
		$(".multi-div > nav .multi-mid > .chart-me").each(function(index, element) {
            p = $(element).find(".chart-info");
			center(p,element);
        });
		center_top(".multi-div > nav .multi-left p",".multi-div > nav .multi-left.back");
		center("#chart-li1 ul li > div#main-star p","#chart-li1 ul li > div#main-star");
		
		$("#change-opa").click(function() {
			$("#popup-opa").fadeIn("fast");
			$("#BODY").css("opacity","0.1");
		});
		
		$("#upload-anh").click(function() {
			$("#popup-anh").fadeIn("fast");
			$("#BODY").css("opacity","0.1");
		});
		
		$("#huy-anh").click(function() {
			$(".popup").fadeOut("fast");
			$("#BODY").css("opacity","1");
			return false;
		});
		
		$("#huy-opa").click(function() {
			$(".popup").fadeOut("fast");
			$("#BODY").css("opacity","1");
			return false;
		});
		
		$("#select-anh").change(function() {
			option = $(this).find("option:selected").val();
			$(".subnav").hide();
			$("#nav-"+option).fadeIn("fast");
			if(option==1) {
				
			} else {
				$("#popup-anh div.subnav li input.radio-anh").prop("checked",false);
			}
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