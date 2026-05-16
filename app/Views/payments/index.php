<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Payments</h1>
    <a href="/payments/create" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 shadow-md transition duration-200">Record Payment</a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant / House</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Charge Breakdown</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($payments as $payment): ?>
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-gray-900"><?php echo $payment['tenant_name']; ?></div>
                    <div class="text-xs text-gray-500"><?php echo $payment['house_name']; ?></div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-xs space-y-1">
                        <?php if ($payment['electricity_amount'] > 0): ?>
                        <div class="flex justify-between text-blue-700">
                            <span>Electricity (<?php echo $payment['electricity_units']; ?> x <?php echo $payment['electricity_rate']; ?>):</span>
                            <span class="font-semibold">Rs. <?php echo number_format($payment['electricity_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($payment['cleaning_amount'] > 0): ?>
                        <div class="flex justify-between text-green-700">
                            <span>Cleaning (<?php echo $payment['cleaning_qty']; ?> x <?php echo $payment['cleaning_rate']; ?>):</span>
                            <span class="font-semibold">Rs. <?php echo number_format($payment['cleaning_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($payment['water_amount'] > 0): ?>
                        <div class="flex justify-between text-teal-700">
                            <span>Water (<?php echo $payment['water_qty']; ?> x <?php echo $payment['water_rate']; ?>):</span>
                            <span class="font-semibold">Rs. <?php echo number_format($payment['water_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-blue-600">Rs. <?php echo number_format($payment['amount'], 2); ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <?php echo $payment['payment_date']; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <?php echo ucfirst($payment['status']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="/payments/edit?id=<?php echo $payment['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    <a href="/payments/delete?id=<?php echo $payment['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this payment record?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($payments)): ?>
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No payment records found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
