<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <div class="w-64 bg-gray-800 min-h-screen text-white p-5">
            <h2 class="text-2xl font-bold mb-5">MENU</h2>
            <ul>
                <li class="mb-2"><a href="/admin/staff" class="text-orange-400">Nhân viên</a></li>
                <li class="mb-2"><a href="#">Khách hàng</a></li>
            </ul>
        </div>
        <div class="p-10 flex-1">
            @yield('content')
        </div>
    </div>
</body>
</html>