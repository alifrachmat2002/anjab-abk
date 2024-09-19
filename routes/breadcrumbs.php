<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\Jabatan;
use App\Models\UnitKerja;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// create a breadcrumb for buat ajuan page
Breadcrumbs::for('buat-ajuan', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Buat Ajuan', route('anjab.ajuan.create'));
});



// Create Breadcumb for Ajuan Analisis Jabatan
Breadcrumbs::for('ajuan-analisis-jabatan', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Daftar Ajuan Analisis Jabatan', route('anjab.ajuan.index'));
});

// Create a breadcrumb for lihat ajuan analisis jabatan page, parent-ing the ajuan analisis jabatan breadcrumb
Breadcrumbs::for('lihat-ajuan-anjab', function (BreadcrumbTrail $trail, $ajuan) {
    $trail->parent('ajuan-analisis-jabatan');
    $trail->push('Ajuan Analisis Jabatan ' . $ajuan->tahun, route('anjab.ajuan.show', ['ajuan' => $ajuan, 'id' => $ajuan->id]));
});
// create breadcrumbs for lihat ajuan anjab jabatan, parent-ing the lihat ajuan anjab breadcrumb
Breadcrumbs::for('lihat-ajuan-anjab-jabatan', function (BreadcrumbTrail $trail, $ajuan, $jabatan) {
    $trail->parent('lihat-ajuan-anjab', $ajuan);
    $trail->push($jabatan->nama, route('anjab.ajuan.jabatan.show', ['ajuan' => $ajuan, 'jabatan' => $jabatan]));
});
Breadcrumbs::for('edit-ajuan-anjab', function (BreadcrumbTrail $trail, $ajuan) {
    $trail->parent('ajuan-analisis-jabatan');
    $trail->push('Edit Ajuan Analisis Jabatan '. $ajuan->tahun, route('anjab.ajuan.edit',['id' => $ajuan->id, 'tahun' => $ajuan->tahun]));
});
Breadcrumbs::for('edit-ajuan-anjab-jabatan', function (BreadcrumbTrail $trail, $ajuan, $jabatan) {
    $trail->parent('edit-ajuan-anjab', $ajuan);
    $trail->push('Edit Informasi Jabatan '. $jabatan->nama, route('anjab.ajuan.jabatan.edit.1', ['ajuan' => $ajuan, 'jabatan' => $jabatan]));
    $trail->push('isi Informasi Umum', route('anjab.ajuan.jabatan.edit.1', ['ajuan' => $ajuan, 'jabatan' => $jabatan]));
});
Breadcrumbs::for('edit-ajuan-anjab-jabatan-2', function (BreadcrumbTrail $trail, $ajuan, $jabatan) {
    $trail->parent('edit-ajuan-anjab-jabatan', $ajuan, $jabatan);
    $trail->push('isi Detail Jabatan');
});

Breadcrumbs::for('lihat-ajuan-analisis-jabatan-unitkerja', function (BreadcrumbTrail $trail, UnitKerja $unit_kerja) {
    $trail->parent('lihat-ajuan-analisis-jabatan');
    $trail->push($unit_kerja->nama);
});
Breadcrumbs::for('lihat-ajuan-an', function (BreadcrumbTrail $trail, UnitKerja $unit_kerja) {
    $trail->parent('lihat-ajuan-analisis-jabatan');
    $trail->push($unit_kerja->nama);
});

// create a breadcrumb for data jabatan page, parent-ing the buat ajuan breadcrumb
Breadcrumbs::for('data-jabatan', function (BreadcrumbTrail $trail) {
    $trail->parent('buat-ajuan');
    $trail->push('Data Jabatan', "/anjab/data-jabatan");
});

// create a breadcrumb for ubah informasi jabatan page, parent-ing the data jabatan breadcrumb
Breadcrumbs::for('ubah-informasi-jabatan', function (BreadcrumbTrail $trail, $jabatan) {
    $trail->parent('buat-ajuan');
    $trail->push('Ubah Informasi Jabatan ' . $jabatan->nama, route('anjab.jabatan.edit.1', $jabatan));
});
Breadcrumbs::for('isi-informasi-umum', function (BreadcrumbTrail $trail, $jabatan) {
    $trail->parent('ubah-informasi-jabatan', $jabatan);
    $trail->push('Isi Informasi Umum', route('anjab.jabatan.edit.1', $jabatan));
});
Breadcrumbs::for('isi-detail-jabatan', function (BreadcrumbTrail $trail, $jabatan) {
    $trail->parent('isi-informasi-umum', $jabatan);
    $trail->push('Isi Detail Jabatan', route('anjab.jabatan.edit.2', $jabatan));
});

// create a bredcrumb for buat ajuan abk page, parent-ing the home breadcrumb
Breadcrumbs::for('buat-ajuan-abk', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Buat Ajuan ABK', "/abk/ajuan/create");
});

// create a breadcrumb for isi informasi abk page, parent-ing the buat ajuan abk breadcrumb
Breadcrumbs::for('isi-informasi-abk', function (BreadcrumbTrail $trail) {
    $trail->parent('buat-ajuan-abk');
    $trail->push('Isi Informasi ABK', "/abk/ajuan/data-abk");
});

Breadcrumbs::for('ajuan-abk-jabatan', function (BreadcrumbTrail $trail, $ajuan, $unit_kerja, $jabatan) {
    $trail->parent('lihat-ajuan-abk-unitkerja', $ajuan, $unit_kerja);
    $trail->push($jabatan->nama, route('abk.jabatan.show',[$ajuan, $unit_kerja, $jabatan]));
});
Breadcrumbs::for('edit-ajuan-abk-jabatan', function (BreadcrumbTrail $trail, $ajuan, $unit_kerja, $jabatan) {
    $trail->parent('edit-ajuan-abk-unitkerja', $ajuan, $unit_kerja);
    $trail->push($jabatan->nama, route('abk.jabatan.show',[$ajuan, $unit_kerja, $jabatan]));
});


// create a breadcrumb for daftar ajuan abk page, parent-ing the home breadcrumb
Breadcrumbs::for('daftar-ajuan-abk', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Daftar Ajuan ABK', route('abk.ajuans'));
});


// create a breadcrumb for lihat ajuan abk page, parent-ing the buat ajuan abk breadcrumb
Breadcrumbs::for('lihat-ajuan-abk', function (BreadcrumbTrail $trail, $ajuan) {
    $trail->parent('daftar-ajuan-abk');
    $trail->push('Ajuan ABK ' . $ajuan->tahun, route('abk.ajuan.show', ['abk' => $ajuan->id]));
});
Breadcrumbs::for('ajuan-abk-unitkerja', function (BreadcrumbTrail $trail, $periode, $unit_kerja) {
    $trail->parent('lihat-ajuan-abk' , $periode);
    $trail->push($unit_kerja->nama, route('abk.unitkerja.show',[$periode, $unit_kerja]));
});
Breadcrumbs::for('edit-ajuan-abk-unitkerja', function (BreadcrumbTrail $trail, $periode, $unit_kerja) {
    $trail->parent('daftar-ajuan-abk');
    $trail->push("Edit ABK ". $unit_kerja->nama . " " . $periode->tahun, route('abk.unitkerja.edit',[$periode, $unit_kerja]));
});

Breadcrumbs::for('lihat-ajuan-abk-unitkerja', function (BreadcrumbTrail $trail, $periode, $unit_kerja) {
    $trail->parent('daftar-ajuan-abk');
    $trail->push("ABK ". $unit_kerja->nama . " " . $periode->tahun, route('abk.unitkerja.show',['abk' => $periode, 'unit_kerja' => $unit_kerja]));
});


Breadcrumbs::for('edit-ajuan-abk', function (BreadcrumbTrail $trail) {
    $trail->parent('daftar-ajuan-abk');
    $trail->push('Edit Ajuan ABK', "");
});

// create a breadcrumb for Buat Informasi Beban Kerja page, parent-ing the buat ajuan abk breadcrumb
Breadcrumbs::for('buat-informasi-beban-kerja', function (BreadcrumbTrail $trail) {
    $trail->parent('buat-ajuan-abk');
    $trail->push('Buat Informasi Beban Kerja', "/abk/jabatan/{jabatan:id}/create");
});

// create a breadcrumb for Admin Dashboard
Breadcrumbs::for('admin-dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Admin Dashboard', "/admin/dashboard");
});
Breadcrumbs::for('user-dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('admin-dashboard');
    $trail->push('User Dashboard', route('admin.users.index'));
});
Breadcrumbs::for('user-create', function (BreadcrumbTrail $trail) {
    $trail->parent('user-dashboard');
    $trail->push('Create User', route('admin.users.create'));
});
Breadcrumbs::for('user-edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user-dashboard');
    $trail->push('Edit User ' . $user->name, route('admin.users.edit',['user' => $user]));
});

// create breadcrumbs for Jabatan Dashboard, parenting the admin dashboard breadcrumb
Breadcrumbs::for('jabatan-dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('admin-dashboard');
    $trail->push('Jabatan Dashboard', route('admin.jabatans.index'));
});

// create breadcrumbs for laporan page, not parenting any breadcrumb
Breadcrumbs::for('laporan', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Laporan', route('laporan.index'));
});
Breadcrumbs::for('laporan-anjab', function (BreadcrumbTrail $trail, $anjab) {
    $trail->parent('laporan');
    $trail->push('Analisis Jabatan ' . $anjab->tahun, route('laporan.anjab', ['tahun' => $anjab->tahun, 'anjab' => $anjab]));
});
Breadcrumbs::for('petajabatan', function (BreadcrumbTrail $trail, $anjab) {
    $trail->parent('laporan');
    $trail->push('Peta Jabatan ', route('laporan.petajabatan',['anjab' => $anjab]));
});

Breadcrumbs::for('petajabatan-unitkerja', function (BreadcrumbTrail $trail, $anjab, $unit_kerja) {
    $trail->parent('petajabatan', $anjab);
    $trail->push($unit_kerja->nama);
});


// Home > Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category));
});
