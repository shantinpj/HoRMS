<h1 class="text-3xl font-bold mb-8 text-gray-800">System Audit Logs</h1>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Timestamp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">IP Address</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($logs as $log): ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?php echo $log['created_at']; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                        <?php echo $log['user_name'] ?: '<span class="text-gray-400 italic">Guest</span>'; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                            <?php 
                                echo strpos($log['action'], 'Delete') !== false ? 'bg-red-100 text-red-800' : 
                                    (strpos($log['action'], 'Update') !== false ? 'bg-yellow-100 text-yellow-800' : 
                                    (strpos($log['action'], 'Create') !== false ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'));
                            ?>">
                            <?php echo $log['action']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs" title="<?php echo htmlspecialchars($log['details']); ?>">
                        <?php echo htmlspecialchars($log['details']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-mono"><?php echo $log['ip_address']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No audit logs found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
