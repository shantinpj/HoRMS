<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Rent Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <?php if (App\Core\Auth::check()): ?>
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <a href="/" class="text-2xl font-bold">HouseRentM</a>
                <div class="hidden md:flex space-x-4">
                    <a href="/" class="hover:underline">Dashboard</a>
                    <a href="/houses" class="hover:underline">Houses</a>
                    <a href="/tenants" class="hover:underline">Tenants</a>
                    <a href="/leases" class="hover:underline">Leases</a>
                    <a href="/payments" class="hover:underline">Payments</a>
                    <?php if (App\Core\Auth::isAdmin()): ?>
                        <a href="/users" class="hover:underline">Users</a>
                    <?php endif; ?>
                    <?php if (App\Core\Auth::isSuperAdmin()): ?>
                        <a href="/audit" class="hover:underline">Audit Logs</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm bg-blue-700 px-3 py-1 rounded-full"><?php echo htmlspecialchars(App\Core\Auth::user()['name']); ?> (<?php echo ucfirst(App\Core\Auth::user()['role']); ?>)</span>
                <a href="/logout" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Logout</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <main class="container mx-auto px-4 py-8 flex-grow">
