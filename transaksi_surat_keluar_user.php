<?php
// Check session
// session_start();
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
    // Handle actions
    if (isset($_REQUEST['act'])) {
        $act = $_REQUEST['act'];
        switch ($act) {
            case 'add':
                include "tambah_surat_masuk.php";
                break;
            case 'edit':
                include "edit_surat_masuk.php";
                break;
            case 'disp':
                include "disposisi.php";
                break;
            case 'print':
                include "cetak_disposisi.php";
                break;
            case 'del':
                include "hapus_surat_masuk.php";
                break;
        }
    } else {
        // Fetch the number of surat_masuk to display per page
        $query = mysqli_query($config, "SELECT surat_masuk FROM tbl_sett");
        list($surat_masuk) = mysqli_fetch_array($query);

        // Pagination setup
        $limit = $surat_masuk;
        $pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
        $curr = ($pg - 1) * $limit;

        ?>

        <!-- Title Start -->
        <div class="row">
            <div class="col s12">
                <div class="z-depth-1">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <div class="col m7">
                                <ul class="left">
                                    <li class="waves-effect waves-light hide-on-small-only"><a href="?page=tsm" class="judul"><i class="material-icons">mail</i> Surat Keluar</a></li>
                                    <li class="waves-effect waves-light"><a href="?page=tsm&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a></li>
                                </ul>
                            </div>
                            <div class="col m5 hide-on-med-and-down">
                                <form method="post" action="?page=tsm">
                                    <div class="input-field round-in-box">
                                        <input id="search" type="search" name="cari" placeholder="Ketik dan tekan enter mencari data..." required>
                                        <label for="search"><i class="material-icons md-dark">search</i></label>
                                        <input type="submit" name="submit" class="hidden">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Title End -->

        <?php
        // Display success messages
        if (isset($_SESSION['succAdd'])) {
            echo '<div id="alert-message" class="row"><div class="col m12"><div class="card green lighten-5"><div class="card-content notif"><span class="card-title green-text"><i class="material-icons md-36">done</i> '.$_SESSION['succAdd'].'</span></div></div></div></div>';
            unset($_SESSION['succAdd']);
        }
        if (isset($_SESSION['succEdit'])) {
            echo '<div id="alert-message" class="row"><div class="col m12"><div class="card green lighten-5"><div class="card-content notif"><span class="card-title green-text"><i class="material-icons md-36">done</i> '.$_SESSION['succEdit'].'</span></div></div></div></div>';
            unset($_SESSION['succEdit']);
        }
        if (isset($_SESSION['succDel'])) {
            echo '<div id="alert-message" class="row"><div class="col m12"><div class="card green lighten-5"><div class="card-content notif"><span class="card-title green-text"><i class="material-icons md-36">done</i> '.$_SESSION['succDel'].'</span></div></div></div></div>';
            unset($_SESSION['succDel']);
        }
        ?>

        <!-- Row form Start -->
        <div class="row jarak-form">
        <?php
        if (isset($_REQUEST['submit'])) {
            // Search functionality
            $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
            echo '
            <div class="col s12" style="margin-top: -18px;">
                <div class="card blue lighten-5">
                    <div class="card-content">
                    <p class="description">Hasil pencarian untuk kata kunci <strong>"'.stripslashes($cari).'"</strong><span class="right"><a href="?page=tsm"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                    </div>
                </div>
            </div>
            <div class="col m12" id="colres">
            <table class="bordered" id="tbl">
            <thead class="blue lighten-4" id="head">
                <tr>
                    <th width="10%">No. Agenda<br/>Kode</th>
                    <th width="30%">Isi Ringkas<br/> File</th>
                    <th width="24%">Asal Surat</th>
                    <th width="18%">No. Surat<br/>Tgl Surat</th>
                    <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                </tr>
            </thead>
            <tbody>';

            // Modify the query to restrict access based on admin role
            if ($_SESSION['admin'] == 2) {
                // Admin with ID 2 can only see their own entries
                $id_user = $_SESSION['id_user'];
                $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE isi LIKE '%$cari%' AND id_user='$id_user' ORDER BY id_surat DESC LIMIT 15");
            } else {
                // Admin with ID 1 and 3 can see all entries
                $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE isi LIKE '%$cari%' ORDER BY id_surat DESC LIMIT 15");
            }

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_array($query)) {
                    echo '
                    <tr>
                        <td>'.$row['no_agenda'].'<br/><hr/>'.$row['kode'].'</td>
                        <td>'.substr($row['isi'], 0, 200).'<br/><br/><strong>File :</strong>';
                    if (!empty($row['file'])) {
                        echo ' <strong><a href="?page=gsm&act=fsm&id_surat='.$row['id_surat'].'">'.$row['file'].'</a></strong>';
                    } else {
                        echo '<em>Tidak ada file yang di upload</em>';
                    }
                    echo '</td>
                        <td>'.$row['asal_surat'].'</td>
                        <td>'.$row['no_surat'].'<br/><hr/>'.indoDate($row['tgl_surat']).'</td>
                        <td>';
                    if ($_SESSION['id_user'] != $row['id_user'] && $_SESSION['id_user'] != 1) {
                        echo '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank"><i class="material-icons">print</i> PRINT</a>';
                    } else {
                        echo '<a class="btn small blue waves-effect waves-light" href="?page=tsm&act=edit&id_surat='.$row['id_surat'].'"><i class="material-icons">edit</i> EDIT</a>
                            <a class="btn small light-green waves-effect waves-light tooltipped" data-position="left" data-tooltip="Pilih Disp untuk menambahkan Disposisi Surat" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'"><i class="material-icons">description</i> DISP</a>
                            <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank"><i class="material-icons">print</i> PRINT</a>
                            <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=del&id_surat='.$row['id_surat'].'"><i class="material-icons">delete</i> DEL</a>';
                    }
                    echo '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="5"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
            }
            echo '</tbody></table><br/><br/></div></div>';
        } else {
            // Display the table
            echo '
            <div class="col m12" id="colres">
            <table class="bordered" id="tbl">
            <thead class="blue lighten-4" id="head">
                <tr>
                    <th width="10%">No. Agenda<br/>Kode</th>
                    <th width="30%">Isi Ringkas<br/> File</th>
                    <th width="24%">Asal Surat</th>
                    <th width="18%">No. Surat<br/>Tgl Surat</th>
                    <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                </tr>
            </thead>
            <tbody>';

            // Modify the query to restrict access based on admin role
            if ($_SESSION['admin'] == 2) {
                // Admin with ID 2 can only see their own entries
                $id_user = $_SESSION['id_user'];
                $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_user='$id_user' ORDER BY id_surat DESC LIMIT $curr, $limit");
            } else {
                // Admin with ID 1 and 3 can see all entries
                $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk ORDER BY id_surat DESC LIMIT $curr, $limit");
            }

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_array($query)) {
                    echo '
                    <tr>
                        <td>'.$row['no_agenda'].'<br/><hr/>'.$row['kode'].'</td>
                        <td>'.substr($row['isi'], 0, 200).'<br/><br/><strong>File :</strong>';
                    if (!empty($row['file'])) {
                        echo ' <strong><a href="?page=gsm&act=fsm&id_surat='.$row['id_surat'].'">'.$row['file'].'</a></strong>';
                    } else {
                        echo '<em>Tidak ada file yang di upload</em>';
                    }
                    echo '</td>
                        <td>'.$row['asal_surat'].'</td>
                        <td>'.$row['no_surat'].'<br/><hr/>'.indoDate($row['tgl_surat']).'</td>
                        <td>';
                    if ($_SESSION['id_user'] != $row['id_user'] && $_SESSION['id_user'] != 1) {
                        echo '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank"><i class="material-icons">print</i> PRINT</a>';
                    } else {
                        if($_SESSION['admin'] == 2){
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=tsm&act=edit&id_surat='.$row['id_surat'].'"><i class="material-icons">edit</i> EDIT</a>
                            <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=del&id_surat='.$row['id_surat'].'"><i class="material-icons">delete</i> DEL</a>';
                        }else{
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=tsm&act=edit&id_surat='.$row['id_surat'].'"><i class="material-icons">edit</i> EDIT</a>
                                <a class="btn small light-green waves-effect waves-light tooltipped" data-position="left" data-tooltip="Pilih Disp untuk menambahkan Disposisi Surat" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'"><i class="material-icons">description</i> DISP</a>
                                <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank"><i class="material-icons">print</i> PRINT</a>
                                <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=del&id_surat='.$row['id_surat'].'"><i class="material-icons">delete</i> DEL</a>';
                        }
                    }
                    echo '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="5"><center><p class="add">Tidak ada data untuk ditampilkan</p></center></td></tr>';
            }
            echo '</tbody></table></div></div>';

            // Pagination
            $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk");
            $cdata = mysqli_num_rows($query);
            $cpg = ceil($cdata / $limit);

            echo '<br/><ul class="pagination">';
            if ($cdata > $limit) {
                if ($pg > 1) {
                    $prev = $pg - 1;
                    echo '<li><a href="?page=tsm&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                    <li><a href="?page=tsm&pg='.$prev.'"><i class="material-icons md-48">chevron_left</i></a></li>';
                } else {
                    echo '<li class="disabled"><a><i class="material-icons md-48">first_page</i></a></li>
                    <li class="disabled"><a><i class="material-icons md-48">chevron_left</i></a></li>';
                }

                for ($i = 1; $i <= $cpg; $i++) {
                    if ($i != $pg) {
                        echo '<li class="waves-effect waves-dark"><a href="?page=tsm&pg='.$i.'"> '.$i.' </a></li>';
                    } else {
                        echo '<li class="active"><a>'.$i.'</a></li>';
                    }
                }

                if ($pg < $cpg) {
                    $next = $pg + 1;
                    echo '<li><a href="?page=tsm&pg='.$next.'"><i class="material-icons md-48">chevron_right</i></a></li>
                    <li><a href="?page=tsm&pg='.$cpg.'"><i class="material-icons md-48">last_page</i></a></li>';
                } else {
                    echo '<li class="disabled"><a><i class="material-icons md-48">chevron_right</i></a></li>
                    <li class="disabled"><a><i class="material-icons md-48">last_page</i></a></li>';
                }
                echo '</ul>';
            } else {
                echo '';
            }
        }
    }
}
?>
