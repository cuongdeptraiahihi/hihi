<?php
    ob_start();
    //session_start();
    require_once("model/open_db.php");
    require_once("model/model.php");
    session_start();
    require_once("model/is_mobile.php");
?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-4">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NHỮNG ĐIỀU CẦN BIẾT</title>
    <meta name="description" content="Bookblock: A Content Flip Plugin - Demo 4" />
    <meta name="keywords" content="javascript, jquery, plugin, css3, flip, page, 3d, booklet, book, perspective" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="https://localhost/www/TDUONG/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/default.css" />
    <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/bookblock.css" />
    <!-- custom demo style -->
    <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/demo4.css" />
    <script src="https://localhost/www/TDUONG/js/modernizr.custom.js"></script>
</head>
<body>
<div class="container">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">
            <div class="bb-item">
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh1.jpg" style="height:100%;" />
                </div>
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh2.jpg" style="height:100%;" />
                </div>
            </div>
            <div class="bb-item">
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh3.jpg" style="height:100%;" />
                </div>
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh4.jpg" style="height:100%;" />
                </div>
            </div>
            <div class="bb-item">
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh5.jpg" style="height:100%;" />
                </div>
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh6.jpg" style="height:100%;" />
                </div>
            </div>
            <div class="bb-item">
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh7.jpg" style="height:100%;" />
                </div>
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh8.jpg" style="height:100%;" />
                </div>
            </div>
            <div class="bb-item">
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh9.jpg" style="height:100%;" />
                </div>
                <div class="bb-custom-side">
                    <img src="https://localhost/www/TDUONG/images/anh10.jpg" style="height:100%;" />
                </div>
            </div>
        </div>

        <nav>
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
        </nav>

    </div>

</div><!-- /container -->
<script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
<script src="https://localhost/www/TDUONG/js/jquerypp.custom.js"></script>
<script src="https://localhost/www/TDUONG/js/jquery.bookblock.js"></script>
<script>
    var Page = (function() {

        var config = {
                $bookBlock : $( '#bb-bookblock' ),
                $navNext : $( '#bb-nav-next' ),
                $navPrev : $( '#bb-nav-prev' ),
                $navFirst : $( '#bb-nav-first' ),
                $navLast : $( '#bb-nav-last' )
            },
            init = function() {
                config.$bookBlock.bookblock( {
                    speed : 1000,
                    shadowSides : 0.8,
                    shadowFlip : 0.4
                } );
                initEvents();
            },
            initEvents = function() {

                var $slides = config.$bookBlock.children();

                // add navigation events
                config.$navNext.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'next' );
                    return false;
                } );

                config.$navPrev.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'prev' );
                    return false;
                } );

                config.$navFirst.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'first' );
                    return false;
                } );

                config.$navLast.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'last' );
                    return false;
                } );

                // add swipe events
                $slides.on( {
                    'swipeleft' : function( event ) {
                        config.$bookBlock.bookblock( 'next' );
                        return false;
                    },
                    'swiperight' : function( event ) {
                        config.$bookBlock.bookblock( 'prev' );
                        return false;
                    }
                } );

                // add keyboard events
                $( document ).keydown( function(e) {
                    var keyCode = e.keyCode || e.which,
                        arrow = {
                            left : 37,
                            up : 38,
                            right : 39,
                            down : 40
                        };

                    switch (keyCode) {
                        case arrow.left:
                            config.$bookBlock.bookblock( 'prev' );
                            break;
                        case arrow.right:
                            config.$bookBlock.bookblock( 'next' );
                            break;
                    }
                } );
            };

        return { init : init };

    })();
</script>
<script>
    Page.init();
</script>
</body>
</html>
<?php
ob_end_flush();
require_once("model/close_db.php");
?>