<?php
set_time_limit(0);
require("ug-publish.php");
/*
Plugin Name: Gunadarma University (UG) Protofolio 
Plugin URI: http://acid.it-kosongsatu.com
Description: This plugin is used to automatically publish your post into your student portofolio at Gunadarma University Studentsite. 
Version: 1.3
Author: Acid
Author URI: http://acid.it-kosongsatu.com
License: Copyright 2010  Gunadarma University  (email : acid@it-kosongsatu.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

add_action( 'admin_menu', 'UG_Portofolio');
add_action('submitpost_box', 'ugportofolio_sidebar');
add_action( 'publish_post', 'publish_portofolio');

function UG_Portofolio() {

	add_options_page('UG Portofolio Setting', 'UG Portofolio Setting', 'manage_options', 'ug-portofolio', 'UG_Portofolio_setting');
	$GLOBALS['password'] = $_POST['ug_password'];
	/*if ($_POST['ug_password']){
		$GLOBALS['password'] = $_POST['ug_password'];
		$_POST['ug_password'] = sha1(md5($_POST['ug_password']));
	}*/
	add_action( 'admin_init', 'register_mysettings' );
}

function UG_Portofolio_setting() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('Anda tidak punya hak untuk mengubah aturan Plugin ini') );
  }  
	$username = get_option('ug_username');
	$password = get_option('ug_password');
	$new = ($username&&$password) ? 'New ' : '';
	
	
  ?>
	<div class="wrap">
			<h2><b>UG Portofolio Setting Page</b></h2>
			<br />
			Please fill form below using your studentsite username and password: 
			<form method="post" action="options.php">
				<?php wp_nonce_field('update-options'); ?>
				<?php settings_fields( 'portofolio-group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Username</th>
						<td><input type="text" id="nama" name="ug_username" value="<?php echo get_option('ug_username'); ?>" /><i> tanpa '@student.gunadarma.ac.id'</i></td>
						
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo $new?>Password</th>
						<td><input type="password" id="pass" name="ug_password" value="" /></td>
					</tr>
				</table><br />
			<b>Note : DO NOT SHARE YOUR USERNAME OR PASSWORD WITH ANYONE. We won't be responsible if anything goes wrong.</b>
			<br /><br /><br />
			<b>User Profile : </b>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Nama</th>
						<td><input type="text" id="nm" name="ug_name" value="<?php echo get_option('ug_name'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">NPM</th>
						<td><input type="text" id="npm" name="ug_npm" value="<?php echo get_option('ug_npm'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Kelas</th>
						<td><input type="text" id="kelas" name="ug_kelas" value="<?php echo get_option('ug_kelas'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Email</th>
						<td><input type="text" id="u_email" name="ug_user_email" value="<?php echo get_option('ug_user_email'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Isi Email</th>
						<td><textarea name="ug_mail_content" rows="15" cols="80" class="wide"><?php echo get_option('ug_mail_content'); ?></textarea></td>
					</tr>
				</table>
				<br />
				<br />
<b>Note: </b><br />
Gunakan tanda '<b>[ug-url]</b>' (tanpa kutip) dalam isi email kamu untuk memasukkan URL dari artikel yang kamu kirim.</i><br />
Gunakan tanda '<b>[ug-matkul]</b>' (tanpa kutip) dalam isi email kamu untuk memasukkan nama matakuliah terkait dengan artikel kamu.</i><br />
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="ug_username,ug_password, ug_name, ug_kelas, ug_npm, ug_user_email, ug_mail_content" />
				
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p> 
				<br />
				<i><b>Cara Penggunaan : </b></i><br />
				1. Isi form di atas dengan menggunakan ID kalian untuk login ke Studentsite. <br />
				2. Saat kalian menulis artikel baru, sebelum mempublish artikel tersebut, berikan tanda checklist pada pilihan "Integrasikan dengan Studentsite".<br />
				3. Pilih kategori dari artikel yang sedang kalian tulis, apakah itu 'Tugas' atau 'Tulisan'.<br />
				4. Isi matakuliah sesuai dengan artikel yang kalian kerjakan.<br />
				5. Publish !<br />
				<b>PERHATIAN ! Jangan lakukan langkah - langkah di atas ketika artikel tersebut SUDAH PERNAH kalian kirim ke portofolio atau kalian sedang melakukan update artikel kalian.</b><br />
				<b>Hal tersebut akan menyebabkan terjadinya kerangkapan data pada portofolio kalian. </b>
			</form>
	</div>
<?php	
}

function register_mysettings() { // whitelist options

	register_setting( 'portofolio-group', 'ug_username' );
	register_setting( 'portofolio-group', 'ug_password' );
	register_setting( 'portofolio-group', 'ug_name' );
	register_setting( 'portofolio-group', 'ug_npm' );
	register_setting( 'portofolio-group', 'ug_kelas' );
	register_setting( 'portofolio-group', 'ug_user_email' );
	register_setting( 'portofolio-group', 'ug_mail_content' );
 }

function ugportofolio_sidebar() {
?>
<!-- <div id="side-sortables" class="meta-box-sortables ui-sortable"> -->
	<div class="postbox" id="submitdiv">
		<div title="Click to toggle" class="handlediv"><br></div>
		<h3 class="hndle"><span>UG Student Portofolio</span></h3>
		<div class="inside">
			<br />
			<input type="checkbox" name="integrasi" value="y" /> Integrasikan Dengan Studentsite ?<br />
			<table class="form-table">
				<tr valign="top">
					<td><input type="radio" name="tipe" value="tugas" />Tugas<br />
					<td><input type="radio" name="tipe" value="tulisan" />Tulisan</td>
				</tr>
				<tr valign="top">
					<th scope="row">Mata Kuliah</th>
					<td><input type="text" name="mtkul" value="" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Email Dosen</th>
					<td><input type="text" name="email_dosen" value="" /></td>
				</tr>
			</table>
		</div>
	</div>
<!-- </div> -->
<?php
}

function publish_portofolio(){

	$username = get_option('ug_username');
	$password = get_option('ug_password');
	$u_email = get_option('ug_user_email');
	$mail_content = get_option('ug_mail_content');
	$u_nm = get_option('ug_name');
	$u_npm = get_option('ug_npm');
	$u_kelas = get_option('ug_kelas');
	$judul = $_POST['post_title'];
	$link = get_permalink();
	$tipe = $_POST['tipe'];
	$nm_mk = $_POST['mtkul'];
	$email = $_POST['email_dosen'];
		if(array_key_exists('integrasi',$_POST)){
			//taro publish di isni
			publish($username,$password,$link, $judul,$nm_mk,$email,$tipe,$u_nm,$u_npm,$u_kelas,$u_email,$mail_content);
			/*
			echo "Selamat ! Data anda berhasil dikirimkan !";
			echo "</br>Data - datanya adalah : </br>";
			echo "$username</br>";
			echo "$password";
			echo "Judul Artikel (Title) : $judul </br>";
			echo "Link (URL) : $link </br>";
			//echo "Link (URL GENERATED) : $link2 </br>";
			echo "Mata Kuliah : $nm_mk </br>";
			echo "Email Dosen : $email </br>";
			echo "DONE !";
			*/
		}

//print_r($GLOBALS);
//die();
}

?>