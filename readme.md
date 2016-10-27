# PopojiCMS
Free Content Management System - Indonesia
Contact : info@popojicms.org

## Cara instalasi PopojiCMS

1. Extract file popojicms.v.x.x.x.zip di directory web Anda.
2. Buatlah database baru yang nantinya sebagai tempat instalasi tabel-tabel.
3. Melalui browser Anda, masuk ke alamat web dimana file popojicms.v.x.x.x.zip tadi diextract.
4. Ikuti petunjuk instalasi dengan benar.
5. Jika instalasi berhasil, hapuslah atau rename file install.php dan hapus README file ini dari directory web Anda.
6. PopojiCMS siap untuk digunakan.

### Catatan (harap dibaca)

#### Localhost
Jika diinstall pada localhost maka pastikan settingan ``rewrite_module = on``

#### Error 500
Jika terjadi error ``500 internal server error`` (web telah di hosting), kemungkinan karena pada file ``.htaccess`` belum ada baris code ``RewriteBase /``. Solusinya adalah dengan menambahkan baris code ``RewriteBase /`` sebelum code ``RewriteEngine on``

#### Masalah Redirect
Jika terjadi error ``The page isn't redirecting properly`` atau ``This webpage has a redirect loop`` maka langkah yang bisa dilakukan adalah sebagai berikut:
* Coba periksa kembali apakah ``rewrite_module`` sudah on atau belum.
* Periksa apakah file ``.htaccess`` tercopy pada server local atau hosting dengan baik.
* Setelah itu clear cache browser Anda.

#### Kemungkinan File error
Jika terdapat error yang lain, mungkin karena hasil extract file yang tidak sempurna, silahkan replace file-file yang error tersebut.

#### Permission
Untuk di hosting, lakukan perubahan user permission untuk folder po-upload menjadi 775 (po-content --> uploads).

## Login backend PopojiCMS
* Masuk ke alamat http://nama.web.anda/po-admin
* Masukkan data login sebagai berikut :
    Username : seperti yg telah diinputkan pada saat proses instalasi.
    Password : seperti yg telah diinputkan pada saat proses instalasi.


# Terima Kasih Kepada
1. Tuhan Yang Maha Esa
2. Orang-orang yang berada di belakang PopojiCMS
3. Aries sebagai pembuat template backend v.1.0.1 - v.1.1.1
4. Aquincum sebagai pembuat template backend v.1.1.2 - v.1.2.2
5. ProUI sebagai pembuat template backend v.1.2.3 - v.1.3.0
6. Enews, Magazine, Andia, Brownie, Wiretree, Neon, Pressroom dan Canvas sebagai pembuat template frontend
7. StructureCore Installation sebagai referensi modul instalasi
8. Easy Menu Manager sebagai pembuat component menu manager
9. FluentPDO, Bramus, Plates dan semua library php yang dipakai pada PopojiCMS
10. Jquery, Bootstrap dan semua plugins jquery yang dipakai pada PopojiCMS
