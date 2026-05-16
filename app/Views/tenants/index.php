<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Tenants</h1>
    <a href="/tenants/create" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add New Tenant</a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($tenants as $tenant): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <?php if ($tenant['profile_photo']): ?>
                                <img class="h-10 w-10 rounded-full object-cover" src="/uploads/tenants/<?php echo $tenant['profile_photo']; ?>" alt="">
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                    <?php echo substr($tenant['name'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo $tenant['name']; ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $tenant['email']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo $tenant['phone']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $tenant['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo ucfirst($tenant['status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="/tenants/show?id=<?php echo $tenant['id']; ?>" class="text-green-600 hover:text-green-900">Statement</a>
                    <a href="/tenants/edit?id=<?php echo $tenant['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($tenants)): ?>
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No tenants found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
