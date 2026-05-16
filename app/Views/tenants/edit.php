<h1 class="text-3xl font-bold mb-8">Edit Tenant</h1>

<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <form action="/tenants/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $tenant['id']; ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="<?php echo htmlspecialchars($tenant['name']); ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" value="<?php echo htmlspecialchars($tenant['email']); ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone Number</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="text" value="<?php echo htmlspecialchars($tenant['phone']); ?>" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="profile_photo">Profile Photo</label>
                <?php if ($tenant['profile_photo']): ?>
                    <img src="/uploads/tenants/<?php echo $tenant['profile_photo']; ?>" class="w-20 h-20 object-cover rounded mb-2">
                <?php endif; ?>
                <input class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" id="profile_photo" name="profile_photo" type="file" accept="image/*">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="id_proof">ID Proof</label>
                <?php if ($tenant['id_proof']): ?>
                    <a href="/uploads/tenants/<?php echo $tenant['id_proof']; ?>" target="_blank" class="text-blue-600 hover:underline block mb-2 text-sm">View Current ID Proof</a>
                <?php endif; ?>
                <input class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="id_proof" name="id_proof" type="file">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status" name="status">
                <option value="active" <?php echo $tenant['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $tenant['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update Tenant
            </button>
            <a href="/tenants" class="text-gray-600 hover:text-gray-800 font-bold">Cancel</a>
        </div>
    </form>
</div>
