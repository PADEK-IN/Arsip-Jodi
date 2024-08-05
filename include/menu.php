<?php
    //cek session
    if(!empty($_SESSION['admin'])){
?>

<!-- Navbar Menu Start -->
<nav class="blue-grey darken-1">
    <div class="nav-wrapper">
        <!-- menu kalau superadmin -->
        <?php if($_SESSION['admin'] == 1){?>
            <ul class="center hide-on-med-and-down" id="nv">
                <li><a href="./" class="ams hide-on-med-and-down">MPP</a></li>
                <li><div class="grs"></></li>
                <li><a href="./"><i class="material-icons"></i>&nbsp; Beranda</a></li>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="transaksi">Transaksi Surat <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='transaksi' class='dropdown-content'>
                    <li><a href="?page=tsm">Surat Masuk</a></li>
                    <li><a href="?page=tsk">Surat Keluar</a></li>
                </ul>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="agenda">Buku Agenda <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='agenda' class='dropdown-content'>
                    <li><a href="?page=asm">Surat Masuk</a></li>
                    <li><a href="?page=ask">Surat Keluar</a></li>
                </ul>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="agenda">Galeri File <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='agenda' class='dropdown-content'>
                    <li><a href="?page=gsm">Surat Masuk</a></li>
                    <li><a href="?page=gsk">Surat Keluar</a></li>
                </ul>
                <li>
                    <a href="?page=ref">Referensi</a></li>
                </li>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="pengaturan">Pengaturan <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='pengaturan' class='dropdown-content'>
                    <li><a href="?page=sett">Instansi</a></li>
                    <li><a href="?page=sett&sub=usr">User</a></li>
                    <li class="divider"></li>
                    <li><a href="?page=sett&sub=back">Backup Database</a></li>
                    <li><a href="?page=sett&sub=rest">Restore Database</a></li>
                </ul>
                <!-- Profile -->
                <li class="right" style="margin-right: 10px;">
                    <a class="dropdown-button" href="#!" data-activates="logout"><i class="material-icons">account_circle</i> <?php echo $_SESSION['nama']; ?><i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='logout' class='dropdown-content'>
                    <li><a href="?page=pro">Profil</a></li>
                    <li><a href="?page=pro&sub=pass">Ubah Password</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="material-icons">settings_power</i> Keluar</a></li>
                </ul>
            </ul>
        <!-- menu kalau admin -->
        <?php }else if($_SESSION['admin'] == 3){?>
            <ul class="center hide-on-med-and-down" id="nv">
                <li><a href="./" class="ams hide-on-med-and-down">MPP</a></li>
                <li><div class="grs"></></li>
                <li><a href="./"><i class="material-icons"></i>&nbsp; Beranda</a></li>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="transaksi">Transaksi Surat <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='transaksi' class='dropdown-content'>
                    <li><a href="?page=tsm">Surat Masuk</a></li>
                    <li><a href="?page=tsk">Surat Keluar</a></li>
                </ul>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="agenda">Buku Agenda <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='agenda' class='dropdown-content'>
                    <li><a href="?page=asm">Surat Masuk</a></li>
                    <li><a href="?page=ask">Surat Keluar</a></li>
                </ul>
                <li>
                    <a class="dropdown-button" href="#!" data-activates="agenda">Galeri File <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='agenda' class='dropdown-content'>
                    <li><a href="?page=gsm">Surat Masuk</a></li>
                    <li><a href="?page=gsk">Surat Keluar</a></li>
                </ul>
                <li>
                    <a href="?page=ref">Referensi</a></li>
                </li>
                
                <li>
                    <a class="dropdown-button" href="#!" data-activates="pengaturan">Pengaturan <i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='pengaturan' class='dropdown-content'>
                    <li><a href="?page=sett">Instansi</a></li>
                    <li><a href="?page=sett&sub=usr">User</a></li>
                </ul>
                <!-- Profile -->
                <li class="right" style="margin-right: 10px;">
                    <a class="dropdown-button" href="#!" data-activates="logout"><i class="material-icons">account_circle</i> <?php echo $_SESSION['nama']; ?><i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='logout' class='dropdown-content'>
                    <li><a href="?page=pro">Profil</a></li>
                    <li><a href="?page=pro&sub=pass">Ubah Password</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="material-icons">settings_power</i> Keluar</a></li>
                </ul>
            </ul>
        <!-- menu kalau user -->
        <?php } else {?>
            <ul class="center hide-on-med-and-down" id="nv">
                <li><a href="./" class="ams hide-on-med-and-down">MPP</a></li>
                <li><div class="grs"></></li>
                <li><a href="./"><i class="material-icons"></i>&nbsp; Beranda</a></li>
                <li>
                    <a href="?page=tsk_user">Transaksi Surat</a></li>
                </li>
                <li>
                    <a href="?page=template">Template Surat</a></li>
                </li>
                <!-- Profile -->
                <li class="right" style="margin-right: 10px;">
                    <a class="dropdown-button" href="#!" data-activates="logout"><i class="material-icons">account_circle</i> <?php echo $_SESSION['nama']; ?><i class="material-icons md-18">arrow_drop_down</i></a>
                </li>
                <ul id='logout' class='dropdown-content'>
                    <li><a href="?page=pro">Profil</a></li>
                    <li><a href="?page=pro&sub=pass">Ubah Password</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="material-icons">settings_power</i> Keluar</a></li>
                </ul>
            </ul>
        <?php }?>
    </div>
</nav>
<!-- Navbar Menu End -->

<?php
    } else {
        header("Location: ../");
        die();
    }
?>
