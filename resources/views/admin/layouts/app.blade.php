<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">





    <style>
        body {
            background: #f4f6f9;
        }
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #212529;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 12px 16px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="d-flex">
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <h5 class="text-white p-3 mb-0">Warranty Admin</h5>

        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.users') }}">Users</a>
        <a href="{{ route('admin.produk_qr.index') }}">Products Qr</a>
        <a href="{{ route('admin.warranty.index') }}">Warranty History</a>
        <a href="{{ route('admin.user-history.index') }}">User History</a>

        <hr class="text-secondary">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-link text-danger w-100 text-start px-3">
                Logout
            </button>
        </form>
    </div>

    {{-- CONTENT --}}
    <div class="flex-fill p-4">
        <h4 class="mb-4">@yield('page-title')</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@stack('scripts')
</body>
</html>
