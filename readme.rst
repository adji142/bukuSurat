###################
SISTEM ADMINISTRASI SURAT CREATED BY AISSYSTEM - aistrick.com
###################

*********************
Installation Web Apps
*********************

cara installasi ke komputer lokal
1. pastikan server sudah running
	- xampp for windows (mysql, apache2) https://www.apachefriends.org/download.html
	- LAMPP for linux
2. clone source code di documment root server (xampp -> xampp/htdocs)
	proses clone
		a. menggunakan git bash
			- buka aplikasi git bash, klik kanan di document root -> git bash
			- ketikan "git clone https://github.com/adji142/bukuSurat.git"
		b. download manual
			# via dekstop
				- klik ke link https://github.com/adji142/bukuSurat
				- click clone and download
				- click download zip
			# via mobile
				- klik ke link https://github.com/adji142/bukuSurat
				- scroll ke bagian bawah, click Dekstop version
				- click clone and download
				- click download zip
3. extract dan copy hasil download an ke directory root anda

*********************
Installation Database
*********************

1. Buat 1 database dengan nama bukusurat
2. buka file bukusurat.sql
3. copy semua isi dari bukusurat.sql
4. buka phpmyadmin dan masuk ke database anda
5. pilih tab sql
6. paste semua isi bukusurat.sql ke tab sql
7. klik go
8. enjoy