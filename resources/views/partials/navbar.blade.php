    <nav class="navbar navbar-expand-lg  bg-body-tertiary p-0 navbar-dark">
        <div class="container-fluid header1 px-3">
            <div class="logo">
                <a class="navbar-brand " href="/">
                    <img src="/assets/undip-logo.svg" alt="Logo" width="100"
                        class="d-inline-block align-text-center" />
                    Universitas Diponegoro <br />
                </a>
            </div>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="/assets/default-picture.svg" alt="Profile Picture" width="30" />

                            <p class="text-white d-inline ms-2">{{ auth()->user()->name }}</p>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="nav-item">
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Log Out</button>
                                </form>

                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white d-inline" href="/login" role="button" aria-expanded="false">
                            Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid mx-3">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav gap-3">
                    @if (auth()->user()->hasRole('superadmin'))
                        <li><a class="nav-link" href="/"><img data-feather="home" width="20px"> Dashboard</a>
                        <li><a class="nav-link" href="{{ route('admin.users.index') }}"><img data-feather="user"
                                    width="20px"></img>User</a>
                        </li>
                        <li><a class="nav-link" href="{{ route('admin.tugas-tambahan.index') }}"><img data-feather="edit" width="20px">Tugas
                                Tambahan</a></li>
                        <li><a class="nav-link" href="{{ route('admin.jabatan.index') }}"><img data-feather="file"
                                    width="20px">Jabatan</a></li>
                        <li><a class="nav-link" href="{{ route('admin.unsur.index') }}"><img data-feather="home" width="20px">Unsur</a>
                        </li>
                        <li><a class="nav-link" href="{{ route('admin.unit-kerja.index') }}"><img data-feather="home" width="20px">Unit Kerja</a>
                        </li>
                    @else
                        <li><a class="nav-link" href="/"><img data-feather="home" width="20px"> Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <p class="nav-link " href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="" width=20px data-feather="edit" alt="">Anjab
                            </p>
                            <ul class="dropdown-menu">
                                @can('make anjab')
                                    <li><a class="dropdown-item me-3 my-1" href="{{ route('anjab.ajuan.create') }}">Buat
                                            Ajuan
                                            Informasi Jabatan Baru</a></li>
                                @endcan
                                <li><a class="dropdown-item me-3 my-1" href="{{ route('anjab.ajuan.index') }}">Lihat
                                        Ajuan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"><img
                                    data-feather="edit" width="20px"></img> ABK</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item me-3 my-1"
                                        href="{{ route('anjab.ajuan.index', ['abk' => true]) }}">Buat Ajuan Baru</a>
                                </li>
                                <li><a class="dropdown-item me-3 my-1" href="{{ route('abk.ajuans') }}">Lihat Ajuan</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="nav-link" href="{{ route('laporan.index') }}"><img data-feather="file"
                                    width="20px"></img> Laporan</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
