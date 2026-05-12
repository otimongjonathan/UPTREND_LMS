<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Disburse Loan #<?php echo e($loan->id); ?>

            </h2>
            <a href="<?php echo e(route('loans.show', $loan)); ?>" class="text-blue-600 hover:text-blue-900">
                ← Back to Loan
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Loan Summary -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Loan Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Borrower</p>
                        <p class="font-semibold text-gray-900"><?php echo e($loan->user->name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loan Amount</p>
                        <p class="font-semibold text-gray-900">UGX <?php echo e(number_format($loan->amount, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loan Product</p>
                        <p class="font-semibold text-gray-900"><?php echo e($loan->product->name ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Interest Rate</p>
                        <p class="font-semibold text-gray-900"><?php echo e($loan->product->interest_rate ?? 0); ?>% per annum</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Disbursement & Repayment Configuration</h3>
                    
                    <form action="<?php echo e(route('disbursements.store', $loan)); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <!-- Disbursement Details -->
                        <div class="border-b pb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Disbursement Details</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="disbursement_amount" class="block text-sm font-medium text-gray-700">Disbursement Amount *</label>
                                    <input type="number" id="disbursement_amount" name="disbursement_amount" 
                                           value="<?php echo e(old('disbursement_amount', $loan->amount)); ?>" 
                                           step="0.01" max="<?php echo e($loan->amount); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <?php $__errorArgs = ['disbursement_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label for="disbursement_date" class="block text-sm font-medium text-gray-700">Disbursement Date *</label>
                                    <input type="date" id="disbursement_date" name="disbursement_date" 
                                           value="<?php echo e(old('disbursement_date', now()->format('Y-m-d'))); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <?php $__errorArgs = ['disbursement_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-span-2">
                                    <label for="disbursement_method" class="block text-sm font-medium text-gray-700">Disbursement Method *</label>
                                    <select id="disbursement_method" name="disbursement_method" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            onchange="toggleMethodFields(this.value)" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="bank_transfer" <?php echo e(old('disbursement_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="mobile_money" <?php echo e(old('disbursement_method') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                        <option value="cash" <?php echo e(old('disbursement_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                                        <option value="cheque" <?php echo e(old('disbursement_method') == 'cheque' ? 'selected' : ''); ?>>Cheque</option>
                                    </select>
                                    <?php $__errorArgs = ['disbursement_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bank_fields" class="mt-4 grid grid-cols-2 gap-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                                    <input type="text" name="bank_name" value="<?php echo e(old('bank_name')); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" value="<?php echo e(old('account_holder_name', $loan->user->name)); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" name="account_number" value="<?php echo e(old('account_number')); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                    <input type="text" name="transaction_id" value="<?php echo e(old('transaction_id')); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <!-- Cash Fields -->
                            <div id="cash_fields" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-gray-700">Cash Received By</label>
                                <input type="text" name="cash_received_by" value="<?php echo e(old('cash_received_by')); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Repayment Schedule Configuration -->
                        <div class="border-b pb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Repayment Schedule Configuration</h4>
                            
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Grace Period:</strong> 2 months from disbursement date. First payment will be due 2 months after disbursement on the same day of the month.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_frequency" class="block text-sm font-medium text-gray-700">Payment Frequency *</label>
                                    <select id="payment_frequency" name="payment_frequency" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            onchange="calculateInstallments()" required>
                                        <option value="">-- Select Frequency --</option>
                                        <option value="weekly" <?php echo e(old('payment_frequency') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                        <option value="bi-weekly" <?php echo e(old('payment_frequency') == 'bi-weekly' ? 'selected' : ''); ?>>Bi-Weekly</option>
                                        <option value="monthly" <?php echo e(old('payment_frequency', 'monthly') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                        <option value="quarterly" <?php echo e(old('payment_frequency') == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                    </select>
                                    <?php $__errorArgs = ['payment_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label for="number_of_installments" class="block text-sm font-medium text-gray-700">Number of Installments *</label>
                                    <input type="number" id="number_of_installments" name="number_of_installments" 
                                           value="<?php echo e(old('number_of_installments', $loan->term_months)); ?>" 
                                           min="1" max="360"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <?php $__errorArgs = ['number_of_installments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('notes')); ?></textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                                💰 Disburse Loan & Generate Schedule
                            </button>
                            <a href="<?php echo e(route('loans.show', $loan)); ?>" 
                               class="px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold text-sm transition-all duration-200">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMethodFields(method) {
            document.getElementById('bank_fields').classList.add('hidden');
            document.getElementById('cash_fields').classList.add('hidden');
            
            if (method === 'bank_transfer') {
                document.getElementById('bank_fields').classList.remove('hidden');
            } else if (method === 'cash') {
                document.getElementById('cash_fields').classList.remove('hidden');
            }
        }

        function calculateInstallments() {
            const frequency = document.getElementById('payment_frequency').value;
            const termMonths = <?php echo e($loan->term_months); ?>;
            let installments = termMonths;

            if (frequency === 'weekly') {
                installments = termMonths * 4;
            } else if (frequency === 'bi-weekly') {
                installments = termMonths * 2;
            } else if (frequency === 'quarterly') {
                installments = Math.max(1, Math.floor(termMonths / 3));
            }

            document.getElementById('number_of_installments').value = installments;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const method = document.getElementById('disbursement_method').value;
            if (method) toggleMethodFields(method);
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/disbursements/create.blade.php ENDPATH**/ ?>