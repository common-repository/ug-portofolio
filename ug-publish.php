<?php
set_time_limit(0);
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11');
//curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5); // Many login forms redirect at least once.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

function publish($username,$password,$post_url, $post_title,$matakuliah,$dosen,$tipe, $nama, $npm, $kelas, $u_email, $mail_content){
	$url = "http://studentsite.gunadarma.ac.id/login.php";
	$post = array(
		'passwd_type'=>'text',
		'account_type'=>'u',
		'login'=>$username,
		'passwd'=>$password,
		'submitit'=>'Login'
		);
	$res = call_url($url,$post,true);
	if($tipe=="tugas"){
		$url="http://studentsite.gunadarma.ac.id/home/index.php?stateid=tugas";
		$data = array(
			'title'=>$post_title,
			'url'=>$post_url,
			'matakuliah'=>$matakuliah,
			'action'=>'new',
			'stateid'=>'tugas',
			'substateid'=>'add'
			);
		$res = call_url($url,$data,true);
	} else {
		$url="http://studentsite.gunadarma.ac.id/home/index.php?stateid=tugasblog2";
		$data = array(
			'title'=>$post_title,
			'url'=>$post_url,
			'action'=>'new',
			'stateid'=>'tugasblog2',
			'substateid'=>'add'
			);
		$res = call_url($url,$data,true);
	}
	if($dosen!=""){
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$subject = "Konfirmasi Tugas Softskills : ".$nama.' ('.$kelas.'-'.$npm.')';
		$message = str_replace("\n","<br />",$mail_content);
		$message = str_replace("[ug-url]",'<a href="'.$post_url.'">'.$post_url.'</a>',$message);
		$message = str_replace("[ug-matkul]",$matakuliah,$message);
		$headers = 'From: '.$nama.'<'.$u_email.'>'."\r\n";		
		wp_mail($dosen, $subject, $message, $headers);
	}
	/*} else {
		echo "Selamat ! Data anda berhasil dikirimkan !";
		echo "</br>Data - datanya adalah : </br>";
		echo "$username</br>";
		echo "$password";
		echo "Judul Artikel (Title) : $post_title </br>";
		echo "Link (URL) : $post_url </br>";
		//echo "Link (URL GENERATED) : $link2 </br>";
		echo "Mata Kuliah : $matakuliah </br>";
		echo "Email Dosen : $dosen </br>";
		echo "Tipe : $tipe </br>";
		echo "DONE !";
	}*/
}
function call_url($url, $post = false, $cookies = true) {
	global $ch;
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/cookie.txt");

	//$post = implode('&', $post);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	
	$contents = curl_exec($ch);
	//if (empty($contents) && $cookies) { echo $url; die; }
	return $contents;
}

?>