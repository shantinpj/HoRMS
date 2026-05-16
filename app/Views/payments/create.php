<h1 class="text-3xl font-bold mb-8">Record Rent Payment</h1>

<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
    <form action="/payments/store" method="POST" id="paymentForm">
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="lease_id">Select Active Lease (House - Tenant)</label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lease_id" name="lease_id" required>
                <option value="">-- Select Lease --</option>
                <?php foreach ($leases as $lease): ?>
                    <option value="<?php echo $lease['id']; ?>" data-rate="<?php echo $lease['electricity_rate']; ?>">
                        <?php echo $lease['house_name']; ?> - <?php echo $lease['tenant_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Base Rent Amount (Rs.)</label>
            <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="amount" name="amount" type="number" step="0.01" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Electricity Charges -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h3 class="font-bold text-blue-800 mb-3 border-b border-blue-200 pb-1">Electricity</h3>
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-blue-600 uppercase mb-1">Units Used</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="electricity_units" id="elec_qty" type="number" step="0.01" value="0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-600 uppercase mb-1">Rate (Rs./Unit)</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="electricity_rate" id="elec_rate" type="number" step="0.01" value="0">
                </div>
            </div>

            <!-- Cleaning Charges -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <h3 class="font-bold text-green-800 mb-3 border-b border-green-200 pb-1">Cleaning</h3>
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-green-600 uppercase mb-1">Qty (Months/Times)</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="cleaning_qty" id="clean_qty" type="number" step="0.01" value="0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-green-600 uppercase mb-1">Rate (Rs./Qty)</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="cleaning_rate" id="clean_rate" type="number" step="0.01" value="50">
                </div>
            </div>

            <!-- Water Usage -->
            <div class="bg-teal-50 p-4 rounded-lg border border-teal-100">
                <h3 class="font-bold text-teal-800 mb-3 border-b border-teal-200 pb-1">Water</h3>
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-teal-600 uppercase mb-1">Qty (Months/Units)</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="water_qty" id="water_qty" type="number" step="0.01" value="0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-teal-600 uppercase mb-1">Rate (Rs./Qty)</label>
                    <input class="shadow border rounded w-full py-1 px-2 text-sm" name="water_rate" id="water_rate" type="number" step="0.01" value="200">
                </div>
            </div>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded-lg mb-8 flex justify-between items-center shadow-inner">
            <span class="text-lg font-semibold">Grand Total:</span>
            <span class="text-2xl font-bold text-yellow-400" id="grand_total_display">Rs. 0.00</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_date">Payment Date</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="payment_date" name="payment_date" type="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="transaction_id">Transaction ID / Reference (Optional)</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="transaction_id" name="transaction_id" type="text">
            </div>
        </div>

        <div class="flex items-center justify-between border-t pt-6">
            <button class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-8 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 shadow-md" type="submit">
                Submit Payment Record
            </button>
            <a href="/payments" class="text-gray-600 hover:text-gray-800 font-bold transition duration-200">Cancel</a>
        </div>
    </form>
</div>

<script>
    const inputs = [
        'amount', 'elec_qty', 'elec_rate', 
        'clean_qty', 'clean_rate', 
        'water_qty', 'water_rate'
    ];
    
    const leaseSelect = document.getElementById('lease_id');
    const grandTotalDisplay = document.getElementById('grand_total_display');

    function calculateGrandTotal() {
        const baseRent = parseFloat(document.getElementById('amount').value) || 0;
        
        const elecAmount = (parseFloat(document.getElementById('elec_qty').value) || 0) * (parseFloat(document.getElementById('elec_rate').value) || 0);
        const cleanAmount = (parseFloat(document.getElementById('clean_qty').value) || 0) * (parseFloat(document.getElementById('clean_rate').value) || 0);
        const waterAmount = (parseFloat(document.getElementById('water_qty').value) || 0) * (parseFloat(document.getElementById('water_rate').value) || 0);
        
        const total = baseRent + elecAmount + cleanAmount + waterAmount;
        grandTotalDisplay.innerText = 'Rs. ' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    inputs.forEach(id => {
        document.getElementById(id).addEventListener('input', calculateGrandTotal);
    });

    leaseSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const rate = selectedOption.getAttribute('data-rate');
        if (rate) {
            document.getElementById('elec_rate').value = rate;
        }
        calculateGrandTotal();
    });
</script>
