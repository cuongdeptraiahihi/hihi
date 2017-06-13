
            
<div id="NAVBAR">
	<ul>
    	<li style="background:#FFF;"><a href="http://localhost/www/TDUONG/mobile/trang-chu/" title="Trang chủ"><i class="fa fa-home"></i></a></li>
        <li style="background:#FFF;" class="li-con"><a href="http://localhost/www/TDUONG/mobile/dang-xuat/" title="Đăng xuất"><i class="fa fa-sign-out"></i></a></li>
    </ul>
</div>

<div id="myback"></div>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '967757889982912',
            cookie     : true,
            xfbml      : true,
            version    : 'v2.8'
        });
        FB.AppEvents.logPageView();

        FB.Event.subscribe('send_to_messenger', function(response) {
            // callback for events triggered by the plugin
            console.log(response);
            if(response.event == "clicked") {
//                $.ajax({
//                    async: true,
//                    data: "event=clicked&data=" + response.ref,
//                    type: "post",
//                    url: "https://localhost/www/TDUONG/xuly-mon/",
//                    success: function(result) {
//                        if(result == "ok") {
////                            location.reload();
//                        } else {
//                            alert("Có lỗi đã xảy ra! Bạn phải inbox Fanpage!");
//                        }
//                    }
//                });
            } else if(response.event == "rendered" && response.is_after_optin) {
//                location.reload();
            } else if(response.event == "not_you") {
                window.location.href="http://localhost/www/TDUONG/mobile/dang-xuat/";
            }
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>