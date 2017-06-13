<div id="NAVBAR">
	<ul>
        <?php
			$resultx=get_all_mon();
			while($datax=mysqli_fetch_assoc($resultx)) {
				if($monID==$datax["ID_MON"]) {
					echo"<li style='background:#FFF;'><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-tong-quan/$hsID/$monID/' title='Môn $datax[name]'>$datax[name]</a></li>";
				} 
			}
		?>
        <li style='background:#FFF;'><a href="javascript:void(0)" id="upload-anh" title="Up ảnh"><i class="fa fa-photo"></i></a></li>
        <li style='background:#FFF;'><a href="javascript:void(0)" id="change-opa" title="Opacity"><i class="fa fa-sort-amount-desc"></i></a></li>
        <li style='background:#FFF;'><a href="http://localhost/www/TDUONG/thaygiao/background/" title="Quay ra"><i class="fa fa-sign-out"></i></a></li>
    </ul>
</div>

<div class="popup" id="popup-anh" style="max-height:500px;overflow-y:auto;top:10%">
	<form action="http://localhost/www/TDUONG/thaygiao/change-back/<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>/" method="post" enctype="multipart/form-data">
        <select class="input" style="width:96%;background:#000;padding:2%;" name="select-anh" id="select-anh">
            <option value="1">Up ảnh mới (jpg, jpeg, png)</option>
            <option value="2">Chọn ảnh trong kho</option>
        </select>
        <input type="submit" class="submit2" id="ok-anh" name="ok-anh" style="margin-top:10px;" value="OK" />
        <input type="submit" class="submit2" id="huy-anh" style="margin-top:10px;" value="Hủy" />
        
        <div class="subnav" id="nav-1" style="margin:10px 0 10px 0;">
            <input type="file" class="input" name="form-anh" id="form-anh" style="background:#000;padding:2%;width:96%;" />
        </div>
        <div class="subnav" id="nav-2" style="margin:10px 0 10px 0;">
            <ul>
            <?php
                $result=get_background();
                while($data=mysqli_fetch_assoc($result)) {
                    echo"<li style='width:100%;margin-bottom:5px;position:relative;'><img src='https://localhost/www/TDUONG/images/$data[content]' style='width:70%;float:left;' /><input type='radio' name='chon-anh' value='$data[ID_O]' class='radio-anh' style='height:20px;width:29%;position:absolute;top:40%;right:0;' /><div class='clear'></div></li>";
                }
            ?>
            </ul>
        </div>
   	</form>
</div>

<div class="popup" id="popup-opa" style="top:10%;">
	<form action="http://localhost/www/TDUONG/thaygiao/change-opa/<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>/" method="post">
    	<input type="range" class="input" style="background:#000;" name="range-opa" value="<?php echo $opacity; ?>" min="0.15" max="0.7" step="0.05" />
        <select class="input" style="width:96%;background:#000;padding:2%;" name="select-opa" id="select-opa">
            <option value="1" <?php if($backall=="255,255,255") {echo"selected='selected'";} ?>>Trắng</option>
            <option value="2" <?php if($backall=="0,0,0") {echo"selected='selected'";} ?>>Đen</option>
        </select>
        <input type="submit" class="submit2" id="ok-opa" name="ok-opa" style="margin-top:10px;" value="OK" />
        <input type="submit" class="submit2" id="huy-opa" style="margin-top:10px;" value="Hủy" />
    </form>
</div>

<div id="myback"></div>
