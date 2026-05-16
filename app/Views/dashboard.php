<h1 class="text-3xl font-bold mb-8">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <h2 class="text-gray-500 text-sm font-semibold uppercase">Total Houses</h2>
        <p class="text-3xl font-bold mt-2"><?php echo $housesCount; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
        <h2 class="text-gray-500 text-sm font-semibold uppercase">Active Tenants</h2>
        <p class="text-3xl font-bold mt-2"><?php echo $tenantsCount; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
        <h2 class="text-gray-500 text-sm font-semibold uppercase">Total Rent Collected</h2>
        <p class="text-3xl font-bold mt-2">Rs. <?php echo number_format($totalRent, 2); ?></p>
    </div>
</div>

<div class="mt-12">
    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
    <div class="flex space-x-4">
        <a href="/houses/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New House</a>
        <a href="/tenants/create" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add New Tenant</a>
        <a href="/leases/create" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">New Lease</a>
        <a href="/payments/create" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Record Payment</a>
    </div>
</div>
