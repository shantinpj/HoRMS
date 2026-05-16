<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
    <a href="/users/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-md transition duration-200">Add New User</a>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users as $u): ?>
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo htmlspecialchars($u['name']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo htmlspecialchars($u['email']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?php echo $u['role'] === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($u['role'] === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                        <?php echo ucfirst($u['role']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $u['created_at']; ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/users/edit?id=<?php echo $u['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
