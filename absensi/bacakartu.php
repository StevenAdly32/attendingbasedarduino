<?php 
	include "koneksi.php";
	//baca tabel status untuk mode absensi
	$sql = mysqli_query($konek, "select * from status");
	$data = mysqli_fetch_array($sql);
	$mode_absen = $data['mode'];

	//uji mode absen
	$mode = "";
	if($mode_absen==1)
		$mode = "Masuk";
	else if($mode_absen==2)
		$mode = "Pulang";


	//baca tabel tmprfid
	$baca_kartu = mysqli_query($konek, "select * from tmprfid");
	$data_kartu = mysqli_fetch_array($baca_kartu);
	$nokartu    = $data_kartu['nokartu'];
?>


<div class="container-fluid" style="text-align: center;">
	<?php if($nokartu=="") { ?>

	<h3>Absen : <?php echo $mode; ?> </h3>
	<h3>Silahkan Tempelkan Kartu  Anda</h3>
	<img src="images/rfid.png" style="width: 200px"> <br>
	<img src="images/animasi2.gif">

	<?php } else {
		//cek nomor kartu RFID tersebut apakah terdaftar di tabel pegawai
		$cari_pegawai = mysqli_query($konek, "select * from pegawai where nokartu='$nokartu'");
		$jumlah_data = mysqli_num_rows($cari_pegawai);

		if($jumlah_data==0)
			echo "<h1>Maaf! Kartu Ini Tidak Terdata</h1>";
		else
		{
			//ambil nama pegawai
			$data_pegawai = mysqli_fetch_array($cari_pegawai);
			$nama = $data_pegawai['nama'];

			//tanggal dan jam hari ini
			date_default_timezone_set('Asia/Jakarta') ;
			$tanggal = date('Y-m-d');
			$jam     = date('H:i:s');

			//cek di tabel absensi, apakah nomor kartu tersebut sudah ada sesuai tanggal saat ini. Apabila belum ada, maka dianggap absen masuk, tapi kalau sudah ada, maka update data sesuai mode absensi
			$cari_absen = mysqli_query($konek, "select * from absensi where nokartu='$nokartu' and tanggal='$tanggal'");
			//hitung jumlah datanya
			$jumlah_absen = mysqli_num_rows($cari_absen);
			if($jumlah_absen == 0)
			{
				echo "<h1>Selamat Datang dan Selamat Bekerja <br> $nama</h1>";
				mysqli_query($konek, "insert into absensi(nokartu, tanggal, jam_masuk)values('$nokartu', '$tanggal', '$jam')");
			}
			else
			{
				//update sesuai pilihan mode absen
				if($mode_absen == 2)
				{
					echo "<h1>Sampai Jumpa dan Hati-Hati Dijalan <br> $nama</h1>";
					mysqli_query($konek, "update absensi set jam_pulang='$jam' where nokartu='$nokartu' and tanggal='$tanggal'");
				}
				
			}
		}

		//kosongkan tabel tmprfid
		mysqli_query($konek, "delete from tmprfid");
	} ?>

</div>