<h1 class="text-3xl font-bold mb-8">Edit Lease Agreement</h1>

<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <form action="/leases/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $lease['id']; ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="house_id">Select House</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="house_id" name="house_id" required>
                <?php foreach ($houses as $house): ?>
                    <option value="<?php echo $house['id']; ?>" data-rent="<?php echo $house['rent_amount']; ?>" <?php echo $house['id'] == $lease['house_id'] ? 'selected' : ''; ?>>
                        <?php echo $house['name']; ?> - Rs. <?php echo number_format($house['rent_amount'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="tenant_id">Select Tenant</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tenant_id" name="tenant_id" required>
                <?php foreach ($tenants as $tenant): ?>
                    <option value="<?php echo $tenant['id']; ?>" <?php echo $tenant['id'] == $lease['tenant_id'] ? 'selected' : ''; ?>>
                        <?php echo $tenant['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" name="start_date" type="date" value="<?php echo $lease['start_date']; ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">End Date</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_date" name="end_date" type="date" value="<?php echo $lease['end_date']; ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rent_amount">Agreed Rent Amount (Rs.)</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rent_amount" name="rent_amount" type="number" step="0.01" value="<?php echo $lease['rent_amount']; ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="electricity_rate">Electricity Rate (Rs./Unit)</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="electricity_rate" name="electricity_rate" type="number" step="0.01" value="<?php echo $lease['electricity_rate']; ?>" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Lease Status</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status" name="status" required>
                <option value="active" <?php echo $lease['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $lease['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update Lease
            </button>
            <a href="/leases" class="text-gray-600 hover:text-gray-800 font-bold">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('house_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const rent = selectedOption.getAttribute('data-rent');
        if (rent) {
            document.getElementById('rent_amount').value = rent;
        }
    });
</script>
