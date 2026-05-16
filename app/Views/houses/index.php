<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Houses</h1>
    <a href="/houses/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New House</a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($houses as $house): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $house['name']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $house['address']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap">Rs. <?php echo number_format($house['rent_amount'], 2); ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $house['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo ucfirst($house['status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/houses/edit?id=<?php echo $house['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($houses)): ?>
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No houses found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
