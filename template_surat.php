<?php
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
    // Path ke folder template
    // include($_SERVER['DOCUMENT_ROOT'] .'/');
    $templateFolder = './template/';

    // Mendapatkan daftar file dalam folder
    $files = scandir($templateFolder);

    // Inisialisasi nomor urut
    $no = 1;
?>
    <!-- Title Start -->
    <div class="row">
        <div class="col s12">
            <div class="z-depth-1">
                <nav class="secondary-nav">
                    <div class="nav-wrapper blue-grey darken-1">
                        <div class="col m7">
                            <ul class="left">
                                <li class="waves-effect waves-light hide-on-small-only"><a class="judul"><i class="material-icons">mail</i> Template Surat</a></li>
                                <li class="waves-effect waves-light"><a href="?page=tsm&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Title End -->
    <table class="bordered" id="tbl">
        <thead class="blue lighten-4" id="head">
            <tr>
                <th width="15%">No.</th>
                <th width="30%">Nama Template</th>
                <th width="24%">File</th>
                <th width="18%">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop untuk setiap file dalam folder
            foreach ($files as $file) {
                // Skip . dan .. yang merupakan direktori dalam folder
                if ($file == '.' || $file == '..') continue;

                // Ambil ekstensi file
                $fileExt = pathinfo($file, PATHINFO_EXTENSION);

                // Cek apakah file adalah file Word atau Excel
                if ($fileExt == 'doc' || $fileExt == 'docx' || $fileExt == 'xls' || $fileExt == 'xlsx') {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo basename($file); ?></td>
                        <td><?php echo $file; ?></td>
                        <td>
                            <a class="btn small light-green waves-effect waves-light tooltipped" data-position="left" data-tooltip="Pilih Download untuk mendownload Template Surat" href="<?php echo $templateFolder . $file; ?>" download><i class="material-icons">description</i> DOWNLOAD</a>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
<?php
}
?>
