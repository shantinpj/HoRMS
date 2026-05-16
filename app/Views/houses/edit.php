<h1 class="text-3xl font-bold mb-8">Edit House</h1>

<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <form action="/houses/update" method="POST">
        <input type="hidden" name="id" value="<?php echo $house['id']; ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">House Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="<?php echo htmlspecialchars($house['name']); ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Address</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" name="address" type="text" value="<?php echo htmlspecialchars($house['address']); ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="3"><?php echo htmlspecialchars($house['description']); ?></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="rent_amount">Rent Amount (Rs.)</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rent_amount" name="rent_amount" type="number" step="0.01" value="<?php echo $house['rent_amount']; ?>" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status" name="status">
                <option value="available" <?php echo $house['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="occupied" <?php echo $house['status'] === 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                <option value="maintenance" <?php echo $house['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
            </select>
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update House
            </button>
            <a href="/houses" class="text-gray-600 hover:text-gray-800 font-bold">Cancel</a>
        </div>
    </form>
</div>
