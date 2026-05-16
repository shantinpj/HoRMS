<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Leases</h1>
    <a href="/leases/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">New Lease Agreement</a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">House</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($leases as $lease): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $lease['tenant_name']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $lease['house_name']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $lease['start_date']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $lease['end_date']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap">Rs. <?php echo number_format($lease['rent_amount'], 2); ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $lease['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo ucfirst($lease['status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="/leases/edit?id=<?php echo $lease['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    <a href="/leases/delete?id=<?php echo $lease['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this lease? This will also set the house as available.')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($leases)): ?>
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No leases found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
