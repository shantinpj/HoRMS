<div class="flex justify-between items-start mb-8">
    <div class="flex items-center">
        <div class="mr-6">
            <?php if ($tenant['profile_photo']): ?>
                <img src="/uploads/tenants/<?php echo $tenant['profile_photo']; ?>" class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-lg">
            <?php else: ?>
                <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center text-gray-500 text-4xl border-4 border-white shadow-lg">
                    <?php echo substr($tenant['name'], 0, 1); ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800"><?php echo htmlspecialchars($tenant['name']); ?></h1>
            <p class="text-gray-500 text-lg"><?php echo htmlspecialchars($tenant['email']); ?> | <?php echo htmlspecialchars($tenant['phone']); ?></p>
            <div class="mt-2">
                <span class="px-3 py-1 text-sm font-semibold rounded-full <?php echo $tenant['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo ucfirst($tenant['status']); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="flex space-x-3">
        <a href="/tenants/edit?id=<?php echo $tenant['id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Edit Profile</a>
        <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Print Statement</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Lease Details -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-600">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Current Lease
            </h2>
            <?php if ($lease): ?>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-400 uppercase font-bold">House</label>
                        <p class="text-lg font-semibold text-gray-700"><?php echo $lease['house_name']; ?></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold">Start Date</label>
                            <p class="font-medium"><?php echo $lease['start_date']; ?></p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 uppercase font-bold">End Date</label>
                            <p class="font-medium"><?php echo $lease['end_date']; ?></p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 uppercase font-bold">Monthly Rent</label>
                        <p class="text-2xl font-bold text-blue-600">Rs. <?php echo number_format($lease['rent_amount'], 2); ?></p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 uppercase font-bold">Electricity Rate</label>
                        <p class="font-medium">Rs. <?php echo number_format($lease['electricity_rate'], 2); ?> / unit</p>
                    </div>
                    <?php if ($tenant['id_proof']): ?>
                        <div class="pt-4 border-t">
                            <a href="/uploads/tenants/<?php echo $tenant['id_proof']; ?>" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                View ID Proof Document
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 italic">No active lease found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Statement / Payment History -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border-t-4 border-green-600">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-xl font-bold">Account Statement</h2>
                <div class="text-right">
                    <?php 
                        $totalPaid = array_sum(array_column($payments, 'amount'));
                    ?>
                    <span class="text-xs text-gray-400 uppercase font-bold block">Total Paid to Date</span>
                    <span class="text-2xl font-bold text-green-600">Rs. <?php echo number_format($totalPaid, 2); ?></span>
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breakdown</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo $payment['payment_date']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs space-y-1 text-gray-500">
                                    <div class="flex justify-between">
                                        <span>Base Rent:</span>
                                        <span>Rs. <?php echo number_format($payment['amount'] - $payment['electricity_amount'] - $payment['cleaning_amount'] - $payment['water_amount'], 2); ?></span>
                                    </div>
                                    <?php if ($payment['electricity_amount'] > 0): ?>
                                        <div class="flex justify-between">
                                            <span>Elec (<?php echo $payment['electricity_units']; ?> units):</span>
                                            <span>Rs. <?php echo number_format($payment['electricity_amount'], 2); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($payment['cleaning_amount'] > 0): ?>
                                        <div class="flex justify-between">
                                            <span>Cleaning:</span>
                                            <span>Rs. <?php echo number_format($payment['cleaning_amount'], 2); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($payment['water_amount'] > 0): ?>
                                        <div class="flex justify-between">
                                            <span>Water:</span>
                                            <span>Rs. <?php echo number_format($payment['water_amount'], 2); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-800">
                                Rs. <?php echo number_format($payment['amount'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">No payment history available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    nav, .bg-gray-800, footer, .flex.space-x-3 {
        display: none !important;
    }
    body {
        background-color: white !important;
    }
    .shadow-md {
        box-shadow: none !important;
        border: 1px solid #eee !important;
    }
    .container {
        width: 100% !important;
        max-width: none !important;
    }
}
</style>
