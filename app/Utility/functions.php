<?php
function getValidate($w,$h,$key){
    ob_start();
    $img = imagecreatetruecolor($w,$h);
    $gray = imagecolorallocate($img,255,255,255);
    $black = imagecolorallocate($img,rand(0,200),rand(0,200),rand(0,200));
    $red = imagecolorallocate($img, 255, 0, 0);
    $white = imagecolorallocate($img, 255, 255, 255);
    $green = imagecolorallocate($img, 0, 255, 0);
    $blue = imagecolorallocate($img, 0, 0, 255);
    imagefilledrectangle($img, 0, 0, 210, 70, $black);
    for($i = 0;$i < 80;$i++){
        imagesetpixel($img, rand(0,$w), rand(0,$h), $gray);
    }
    $num1 = mt_rand(51,100);
    $num2 = mt_rand(1,50);
    $rand = getRand();
    $ttf = base_path('public/asset/Moderan.ttf');
    imageTtfText($img, 20,rand(-45,45),20, rand(30,50), $red, $ttf,$num1);
    imageTtfText($img, 20,0,65, rand(30,50), $white, $ttf,$rand);
    imageTtfText($img, 20,rand(-45,45),100, rand(30,50), $green, $ttf,$num2);
    imageTtfText($img, 20,0,135, rand(30,50), $blue, $ttf,"=");
    imageTtfText($img, 20,0,185, rand(30,50), $red, $ttf,"?");
    imagepng($img);
    imagedestroy($img);
    $content = ob_get_clean();
    if($rand == "+"){
        //加
        $result = $num1 + $num2;
    }else{
        //减
        $result = $num1 - $num2;
    }
    Cache::put('image:'.$key, $result, 20);
    return response($content)->header('Content-Type','image/png');

}

function getRand(){
    $code = mt_rand(0,1);
    switch ($code) {
        case 0:
            return "+";
            break;
        case 1:
            return "-";
            break;
        default:
            # code...
            break;
    }
}

function time_tranx($the_time){
    $now_time = time();
    $dur = $now_time - $the_time;
    if($dur <= 0){
        return '刚刚';
    }else{
        if($dur < 60){
            return $dur.'秒前';
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前';
            }else{
                if($dur < 86400){
                    return floor($dur/3600).'小时前';
                }else{
                    if($dur < 259200){ //3天内
                        return floor($dur/86400).'天前';
                    }else{
                        return $the_time;
                    }
                }
            }
        }
    }
}

function randIcons()
{

	$icons = [
		'https://lovepicture.nosdn.127.net/8814425931195142227?imageView&thumbnail=127y127&quality=85',
		'https://lovepicture.nosdn.127.net/-2938031258272153021?imageView&thumbnail=238y238&quality=85',
		'https://lovepicture.nosdn.127.net/-3736890641936212495?imageView&thumbnail=238y238&quality=85',
		'https://lovepicture.nosdn.127.net/421243273678009447?imageView',
		'https://lovepicture.nosdn.127.net/3255199884390267295?imageView',
		'https://lovepicture.nosdn.127.net/7259506587830317577?imageView',
		'https://lovepicture.nosdn.127.net/8206724380878302162?imageView',
		'https://lovepicture.nosdn.127.net/-4344300205786863478?imageView',
		'https://lovepicture.nosdn.127.net/-7979581211781744711?imageView',
		'https://lovepicture.nosdn.127.net/4697127335281528040?imageView',
		'https://lovepicture.nosdn.127.net/8318259750208461078?imageView',
		'https://lovepicture.nosdn.127.net/1810396337464327332?imageView',
		'https://lovepicture.nosdn.127.net/367036025462625845?imageView',
		'https://lovepicture.nosdn.127.net/-1602426767614710336?imageView',
		'https://lovepicture.nosdn.127.net/-8592386226288078264?imageView',
		'https://lovepicture.nosdn.127.net/-6799925296984499706?imageView',
		'https://lovepicture.nosdn.127.net/8688181059073330117?imageView',
		'https://lovepicture.nosdn.127.net/807320746298592423?imageView',
		'https://lovepicture.nosdn.127.net/-1850163430969686743?imageView',
		'https://lovepicture.nosdn.127.net/4546068045856877285?imageView',
		'https://lovepicture.nosdn.127.net/5516051328345897263?imageView',
		'https://lovepicture.nosdn.127.net/-2620003841988132266?imageView',
		'https://lovepicture.nosdn.127.net/-7448531318866898867?imageView',
		'https://lovepicture.nosdn.127.net/1022382986073067096?imageView',
		'https://lovepicture.nosdn.127.net/-3285471731439789806?imageView',
		'https://lovepicture.nosdn.127.net/2402064079405554389?imageView',
		'https://lovepicture.nosdn.127.net/3781815285890242230?imageView',
		'https://lovepicture.nosdn.127.net/8904959701116805236?imageView',
		'https://lovepicture.nosdn.127.net/7181463171447212063?imageView',
		'https://lovepicture.nosdn.127.net/3347319654616881822?imageView',
		'https://lovepicture.nosdn.127.net/-4983158765513899746?imageView',
		'https://lovepicture.nosdn.127.net/2794474067316903665?imageView',
		'https://lovepicture.nosdn.127.net/7285497098702404782?imageView',
		'https://lovepicture.nosdn.127.net/-1671303542733565692?imageView',
		'https://lovepicture.nosdn.127.net/-1564193159908095003?imageView',
		'https://lovepicture.nosdn.127.net/-9216418357521891141?imageView',
		'https://lovepicture.nosdn.127.net/4324189126872934834?imageView',
		'https://lovepicture.nosdn.127.net/8462442780971033575?imageView',
		'https://lovepicture.nosdn.127.net/-6421855798778513627?imageView',
		'https://lovepicture.nosdn.127.net/925580096079463952?imageView',
		'https://lovepicture.nosdn.127.net/7997611500798244997?imageView',
		'https://lovepicture.nosdn.127.net/6101888039067777032?imageView',
		'https://lovepicture.nosdn.127.net/-7696617157115197256?imageView',
		'https://lovepicture.nosdn.127.net/-2799301563911425952?imageView',
		'https://lovepicture.nosdn.127.net/1136783182376835849?imageView',
		'https://lovepicture.nosdn.127.net/4581239960405619804?imageView',
		'https://lovepicture.nosdn.127.net/8830682116895681784?imageView',
		'https://lovepicture.nosdn.127.net/9150035178683953710?imageView',
		'https://lovepicture.nosdn.127.net/-8649069678326103978?imageView',
		'https://lovepicture.nosdn.127.net/747009516106271319?imageView',
		'https://lovepicture.nosdn.127.net/-6893270152831801755?imageView',
		'https://lovepicture.nosdn.127.net/2512225557882185072?imageView',
		'https://lovepicture.nosdn.127.net/-6758659703351107851?imageView',
		'https://lovepicture.nosdn.127.net/7473278334881456547?imageView',
		'https://lovepicture.nosdn.127.net/-957081019825454751?imageView',
		'https://lovepicture.nosdn.127.net/-7648336831480492650?imageView',
		'https://lovepicture.nosdn.127.net/8381802155433290214?imageView',
		'https://lovepicture.nosdn.127.net/505926086162499554?imageView',
		'https://lovepicture.nosdn.127.net/1539182332689549260?imageView',
		'https://lovepicture.nosdn.127.net/-4682449277768031978?imageView',
		'https://lovepicture.nosdn.127.net/709465392526717173?imageView',
		'https://lovepicture.nosdn.127.net/-5774302127381556046?imageView',
		'https://lovepicture.nosdn.127.net/3958469809970219979?imageView',
		'https://lovepicture.nosdn.127.net/177481597902362917?imageView',
		'https://lovepicture.nosdn.127.net/-2042028038944494940?imageView',
		'https://lovepicture.nosdn.127.net/7586601309751819211?imageView',
		'https://lovepicture.nosdn.127.net/-8427470710130600080?imageView',
		'https://lovepicture.nosdn.127.net/4931280063170963688?imageView',
		'https://lovepicture.nosdn.127.net/-8553801123610617498?imageView',
		'https://lovepicture.nosdn.127.net/3195743135710007220?imageView',
		'https://lovepicture.nosdn.127.net/-6999234130506221931?imageView',
		'https://lovepicture.nosdn.127.net/-7639601465754650268?imageView',
		'https://lovepicture.nosdn.127.net/4686405982646928497?imageView',
		'https://lovepicture.nosdn.127.net/-631018497656681268?imageView',
		'https://lovepicture.nosdn.127.net/-6172609985704151011?imageView',
		'https://lovepicture.nosdn.127.net/-9039560913184348316?imageView',
		'https://lovepicture.nosdn.127.net/-2921973453862927924?imageView',
		'https://lovepicture.nosdn.127.net/8539627778752217550?imageView',
		'https://lovepicture.nosdn.127.net/3624469987476862458?imageView',
		'https://lovepicture.nosdn.127.net/2937578067770641169?imageView',
		'https://lovepicture.nosdn.127.net/-4649548906030987120?imageView',
		'https://lovepicture.nosdn.127.net/6478191549580253281?imageView',
		'https://lovepicture.nosdn.127.net/-6792953631022122164?imageView',
		'https://lovepicture.nosdn.127.net/1315377432218293875?imageView',
		'https://lovepicture.nosdn.127.net/2286911497488254637?imageView',
		'https://lovepicture.nosdn.127.net/-7621186243055603198?imageView',
		'https://lovepicture.nosdn.127.net/-5939693684089487459?imageView',
		'https://lovepicture.nosdn.127.net/-5983958652974214500?imageView',
		'https://lovepicture.nosdn.127.net/4086954907445243758?imageView',
		'https://lovepicture.nosdn.127.net/4790251807565304185?imageView',
		'https://lovepicture.nosdn.127.net/-8010539877818302691?imageView',
		'https://lovepicture.nosdn.127.net/74246391420216281?imageView',
		'https://lovepicture.nosdn.127.net/6978098326722179243?imageView',
		'https://lovepicture.nosdn.127.net/8480047123196554117?imageView',
		'https://lovepicture.nosdn.127.net/4915490702444738532?imageView',
		'https://lovepicture.nosdn.127.net/2364105342027182189?imageView',
		'https://lovepicture.nosdn.127.net/8320951241340408318?imageView',
		'https://lovepicture.nosdn.127.net/-3197985704246147584?imageView',
		'https://lovepicture.nosdn.127.net/7490834780936640531?imageView',
		'https://lovepicture.nosdn.127.net/4089029754002420312?imageView',
		'https://lovepicture.nosdn.127.net/-132481038518985230?imageView',
		'https://lovepicture.nosdn.127.net/5978759617736982313?imageView',
		'https://lovepicture.nosdn.127.net/-3984059394739144885?imageView',
		'https://lovepicture.nosdn.127.net/9130907320819694092?imageView',
		'https://lovepicture.nosdn.127.net/-3104566079701706838?imageView',
		'https://lovepicture.nosdn.127.net/8609121592172918681?imageView',
		'https://lovepicture.nosdn.127.net/5941201847785529980?imageView',
		'https://lovepicture.nosdn.127.net/796293748683704871?imageView',
		'https://lovepicture.nosdn.127.net/-5782366838002939075?imageView',
		'https://lovepicture.nosdn.127.net/3873982018789950626?imageView',
		'https://lovepicture.nosdn.127.net/2037635339446425441?imageView',
		'https://lovepicture.nosdn.127.net/8641124205714052552?imageView',
		'https://lovepicture.nosdn.127.net/3829356384292508805?imageView',
		'https://lovepicture.nosdn.127.net/-7737761790901539321?imageView',
		'https://lovepicture.nosdn.127.net/386460679735511153?imageView',
		'https://lovepicture.nosdn.127.net/1108799505152631594?imageView',
		'https://lovepicture.nosdn.127.net/5804509239347959909?imageView',
		'https://lovepicture.nosdn.127.net/-230211871978808289?imageView',
		'https://lovepicture.nosdn.127.net/-8343836475015480225?imageView',
		'https://lovepicture.nosdn.127.net/2498492210913749998?imageView',
		'https://lovepicture.nosdn.127.net/-50703201534428740?imageView',
		'https://lovepicture.nosdn.127.net/2145787677918782453?imageView',
		'https://lovepicture.nosdn.127.net/-6450227150741602001?imageView',
		'https://lovepicture.nosdn.127.net/6444459594949921994?imageView',
		'https://lovepicture.nosdn.127.net/-8023205706584692820?imageView',
		'https://lovepicture.nosdn.127.net/-2693606364953359179?imageView',
		'https://lovepicture.nosdn.127.net/3233870955379790350?imageView',
		'https://lovepicture.nosdn.127.net/-3067416345943547348?imageView',
		'https://lovepicture.nosdn.127.net/6940679386050799708?imageView',
		'https://lovepicture.nosdn.127.net/4300629713048602071?imageView',
		'https://lovepicture.nosdn.127.net/-3751855867841109410?imageView',
		'https://lovepicture.nosdn.127.net/4373798085847101576?imageView',
		'https://lovepicture.nosdn.127.net/-8291911889767273252?imageView',
		'https://lovepicture.nosdn.127.net/8097460381828751436?imageView',
		'https://lovepicture.nosdn.127.net/8021716512696727615?imageView',
		'https://lovepicture.nosdn.127.net/2670773201586610754?imageView',
		'https://lovepicture.nosdn.127.net/-1932190821009975353?imageView',
		'https://lovepicture.nosdn.127.net/5876865162947290254?imageView',
		'https://lovepicture.nosdn.127.net/3053914104952785165?imageView',
		'https://lovepicture.nosdn.127.net/-7473110269891938512?imageView',
		'https://lovepicture.nosdn.127.net/-8423364926281871195?imageView',
		'https://lovepicture.nosdn.127.net/-4484550686478886163?imageView',
		'https://lovepicture.nosdn.127.net/1597537970438231421?imageView',
		'https://lovepicture.nosdn.127.net/-1454489758228052217?imageView',
		'https://lovepicture.nosdn.127.net/-2854934299622605420?imageView',
		'https://lovepicture.nosdn.127.net/-5936446531840632141?imageView',
		'https://lovepicture.nosdn.127.net/8891503850432222531?imageView',
		'https://lovepicture.nosdn.127.net/4378624425962012850?imageView',
		'https://lovepicture.nosdn.127.net/4320215680473437778?imageView',
		'https://lovepicture.nosdn.127.net/-5265629520073878840?imageView',
	];

	return $icons[array_rand($icons)];
}
function isMobile()
{
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
		return true;
	}
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
			'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
			'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave',
			'nexusone', 'cldc', 'midp', 'wap', 'mobile');
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}