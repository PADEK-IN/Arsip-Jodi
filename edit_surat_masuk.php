<?php
// Check session
// session_start();
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
    $admin = $_SESSION['admin'];
    $id_user = $_SESSION['id_user'];

    // Debugging output
    echo "Admin role: " . $admin . "<br>";
    echo "ID user from session: " . $id_user . "<br>";

    if (isset($_REQUEST['submit'])) {
        // Validate empty form
        $requiredFields = ['no_agenda', 'no_surat', 'asal_surat', 'isi', 'kode', 'indeks', 'tgl_surat', 'keterangan'];
        foreach ($requiredFields as $field) {
            if (empty($_REQUEST[$field])) {
                $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                echo '<script language="javascript">window.history.back();</script>';
                exit();
            }
        }

        $no_agenda = $_REQUEST['no_agenda'];
        $no_surat = $_REQUEST['no_surat'];
        $asal_surat = $_REQUEST['asal_surat'];
        $isi = $_REQUEST['isi'];
        $kode = substr($_REQUEST['kode'], 0, 30);
        $nkode = trim($kode);
        $indeks = $_REQUEST['indeks'];
        $tgl_surat = $_REQUEST['tgl_surat'];
        $keterangan = $_REQUEST['keterangan'];

        // Validate input data
        $patterns = [
            'no_agenda' => "/^[0-9]*$/",
            'no_surat' => "/^[a-zA-Z0-9.\/ -]*$/",
            'asal_surat' => "/^[a-zA-Z0-9.,() \/ -]*$/",
            'isi' => "/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/",
            'kode' => "/^[a-zA-Z0-9., ]*$/",
            'indeks' => "/^[a-zA-Z0-9., -]*$/",
            'tgl_surat' => "/^[0-9.-]*$/",
            'keterangan' => "/^[a-zA-Z0-9.,()\/ -]*$/"
        ];

        foreach ($patterns as $field => $pattern) {
            if (!preg_match($pattern, $$field)) {
                $_SESSION['e' . $field] = "Form " . ucfirst(str_replace('_', ' ', $field)) . " mengandung karakter yang tidak valid!";
                echo '<script language="javascript">window.history.back();</script>';
                exit();
            }
        }

        $ekstensi = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf'];
        $file = $_FILES['file']['name'];
        $x = explode('.', $file);
        $eks = strtolower(end($x));
        $ukuran = $_FILES['file']['size'];
        $target_dir = "upload/surat_masuk/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // If file is not empty, handle file upload
        if ($file != "") {
            $rand = rand(1, 10000);
            $nfile = $rand . "-" . $file;

            // Validate file
            if (in_array($eks, $ekstensi)) {
                if ($ukuran < 2300000) {
                    $id_surat = $_REQUEST['id_surat'];
                    $query = mysqli_query($config, "SELECT file FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
                    list($file) = mysqli_fetch_array($query);

                    if (!empty($file)) {
                        unlink($target_dir . $file);
                    }

                    move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);
                    $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_agenda='$no_agenda', no_surat='$no_surat', asal_surat='$asal_surat', isi='$isi', kode='$nkode', indeks='$indeks', tgl_surat='$tgl_surat', file='$nfile', keterangan='$keterangan', id_user='$id_user' WHERE id_surat='$id_surat'");

                    if ($query) {
                        $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                        header("Location: ./admin.php?page=tsm");
                        exit();
                    } else {
                        $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                        echo '<script language="javascript">window.history.back();</script>';
                    }
                } else {
                    $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                    echo '<script language="javascript">window.history.back();</script>';
                }
            } else {
                $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
                echo '<script language="javascript">window.history.back();</script>';
            }
        } else {
            // If file is empty, update the record without changing the file
            $id_surat = $_REQUEST['id_surat'];
            $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_agenda='$no_agenda', no_surat='$no_surat', asal_surat='$asal_surat', isi='$isi', kode='$nkode', indeks='$indeks', tgl_surat='$tgl_surat', keterangan='$keterangan', id_user='$id_user' WHERE id_surat='$id_surat'");

            if ($query) {
                $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                header("Location: ./admin.php?page=tsm");
                exit();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                echo '<script language="javascript">window.history.back();</script>';
            }
        }
    } else {
        $id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);
        $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
        $row = mysqli_fetch_array($query);

        // Debugging output
        echo "ID user from database: " . $row['id_user'] . "<br>";

        // Access control based on role
        if (($admin == 2 || $admin == 3) && $id_user != $row['id_user']) {
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
                    window.location.href="./admin.php?page=tsm";
                  </script>';
        } else {
            ?>
            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">edit</i> Edit Data Surat Masuk</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
            // Display error messages
            $errors = ['errQ', 'errEmpty'];
            foreach ($errors as $error) {
                if (isset($_SESSION[$error])) {
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> ' . $_SESSION[$error] . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION[$error]);
                }
            }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">
                <!-- Form START -->
                <form class="col s12" method="POST" action="?page=tsm&act=edit" enctype="multipart/form-data">
                    <!-- Row in form START -->
                    <div class="row">
                        <?php
                        $fields = [
                            ['no_agenda', 'text', 'looks_one', 'Nomor Agenda', 'Nomor Agenda hanya boleh mengandung angka'],
                            ['kode', 'text', 'looks_two', 'Kode Klasifikasi', 'Kode Klasifikasi hanya boleh mengandung angka dan titik(.)'],
                            ['indeks', 'text', 'looks_3', 'Indeks Berkas', 'Indeks Berkas hanya boleh mengandung angka dan huruf'],
                            ['no_surat', 'text', 'looks_4', 'Nomor Surat', 'Nomor Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), garis miring(/) dan minus(-)'],
                            ['asal_surat', 'text', 'account_box', 'Asal Surat', 'Asal Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-) dan garis miring(/)'],
                            ['tgl_surat', 'text', 'date_range', 'Tanggal Surat', 'Tanggal Surat hanya boleh mengandung angka dan minus(-)'],
                            ['isi', 'textarea', 'description', 'Isi Ringkas', 'Isi Ringkas hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), kurung(), garis miring(/), petik tunggal(‘) dan petik ganda(“)'],
                            ['keterangan', 'textarea', 'description', 'Keterangan', 'Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), kurung() dan garis miring(/)']
                        ];

                        foreach ($fields as $field) {
                            echo '<div class="input-field col s6" style="display:flex; align-items:center;">';
                            echo '<i class="material-icons prefix md-prefix">' . $field[2] . '</i>';
                            echo '<label for="' . $field[0] . '">' . $field[3] . '</label>';
                            echo $field[1] === 'textarea' ? '<textarea id="' . $field[0] . '" class="materialize-textarea" name="' . $field[0] . '">' . $row[$field[0]] . '</textarea>' : '<input id="' . $field[0] . '" type="' . $field[1] . '" class="validate" name="' . $field[0] . '" value="' . $row[$field[0]] . '" required>';

                            // Error message
                            if (isset($_SESSION['e' . $field[0]])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $_SESSION['e' . $field[0]] . '</div>';
                                unset($_SESSION['e' . $field[0]]);
                            }

                            echo '</div>';
                        }
                        ?>
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="input-field col s6">
                            <div class="file-field input-field tooltipped" data-position="top" data-tooltip="Jika tidak ada file yang diupload, abaikan saja!">
                                <div class="btn light-green darken-1">
                                    <span>File</span>
                                    <input type="file" name="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload file/scan gambar surat masuk">
                                </div>
                            </div>
                            <?php
                            if (isset($_SESSION['errSize'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $_SESSION['errSize'] . '</div>';
                                unset($_SESSION['errSize']);
                            }

                            if (isset($_SESSION['errFormat'])) {
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $_SESSION['errFormat'] . '</div>';
                                unset($_SESSION['errFormat']);
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Row in form START -->
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="hidden" name="id_surat" value="<?php echo $row['id_surat']; ?>">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">save</i></button>
                            <a href="?page=tsm" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                        </div>
                    </div>
                    <!-- Row in form END -->
                </form>
                <!-- Form END -->
            </div>
            <!-- Row form END -->
            <?php
        }
    }
}
?>
