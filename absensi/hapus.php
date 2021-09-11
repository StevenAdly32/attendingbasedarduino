<?php
	include "koneksi.php";

	//baca id data yang akan dihapus
	$id = $_GET['id'];

	//hapus data
	$hapus = mysqli_query($konek, "delete from pegawai where id='$id'");

	//jika berhasil terhapus tampilkan pesan Terhapus
	//kemudian kembali ke data pegawai
	if($hapus)
	{
		echo "
			<script>
				alert('Terhapus');
				location.replace('datapegawai.php');
			</script>
		";
	}
	else
	{
		echo "
			<script>
				alert('Gagal Terhapus');
				location.replace('datapegawai.php');
			</script>
		";
	}

?>