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
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💳 Repayment Details - Installment #<?php echo e($repayment->installment_number); ?>

            </h2>
            <a href="<?php echo e(route('repayments.index')); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back to Repayments
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Repayment Status Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">Payment Status</h3>
                        <span class="px-4 py-2 rounded-full text-lg font-bold
                            <?php if($repayment->status === 'completed'): ?> bg-green-100 text-green-700
                            <?php elseif($repayment->status === 'partial'): ?> bg-yellow-100 text-yellow-700
                            <?php else: ?> bg-red-100 text-red-700
                            <?php endif; ?>">
                            <?php echo e(str($repayment->status)->title()); ?>

                        </span>
                    </div>
                </div>

                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Total Due</p>
                        <p class="text-2xl font-bold text-blue-600">UGX <?php echo e(number_format($repayment->amount + $repayment->late_fee, 2)); ?></p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Amount Paid</p>
                        <p class="text-2xl font-bold text-green-600">UGX <?php echo e(number_format($repayment->paid_amount ?? 0, 2)); ?></p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Remaining</p>
                        <p class="text-2xl font-bold text-orange-600">UGX <?php echo e(number_format($repayment->getRemainingAmount(), 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Payment Breakdown</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-4">Installment Details</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Installment Number:</span>
                                    <span class="font-semibold">#<?php echo e($repayment->installment_number); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Principal Amount:</span>
                                    <span class="font-semibold">UGX <?php echo e(number_format($repayment->principal_amount, 2)); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Interest Amount:</span>
                                    <span class="font-semibold">UGX <?php echo e(number_format($repayment->interest_amount, 2)); ?></span>
                                </div>
                                <?php if($repayment->late_fee > 0): ?>
                                <div class="flex justify-between text-red-600">
                                    <span>Late Fee:</span>
                                    <span class="font-semibold">UGX <?php echo e(number_format($repayment->late_fee, 2)); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-semibold">Total Due:</span>
                                    <span class="font-bold">UGX <?php echo e(number_format($repayment->amount + $repayment->late_fee, 2)); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-4">Payment Information</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Due Date:</span>
                                    <span class="font-semibold"><?php echo e($repayment->due_date->format('M d, Y')); ?></span>
                                </div>
                                <?php if($repayment->paid_date): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paid Date:</span>
                                    <span class="font-semibold"><?php echo e($repayment->paid_date->format('M d, Y')); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($repayment->isOverdue()): ?>
                                <div class="flex justify-between text-red-600">
                                    <span>Days Overdue:</span>
                                    <span class="font-semibold"><?php echo e($repayment->getDaysOverdue()); ?> days</span>
                                </div>
                                <?php endif; ?>
                                <?php if($repayment->payment_method): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-semibold"><?php echo e(str($repayment->payment_method)->title()); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($repayment->payment_reference): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Reference:</span>
                                    <span class="font-semibold"><?php echo e($repayment->payment_reference); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Remaining Balance:</span>
                                    <span class="font-semibold">UGX <?php echo e(number_format($repayment->remaining_balance, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Loan Information</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Borrower</p>
                            <p class="text-lg text-gray-900"><?php echo e($repayment->loanApplication->user->name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Loan Amount</p>
                            <p class="text-lg font-bold text-gray-900">UGX <?php echo e(number_format($repayment->loanApplication->amount, 2)); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Interest Rate</p>
                            <p class="text-lg text-gray-900"><?php echo e($repayment->loanApplication->applied_interest_rate ?? 'N/A'); ?>%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Repayment Schedule</p>
                            <p class="text-lg text-gray-900"><?php echo e(str($repayment->loanApplication->repayment_schedule)->replace('_', ' ')->title()); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Record Payment Form -->
            <?php if($repayment->status !== 'completed'): ?>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Record Payment</h3>
                </div>

                <form action="<?php echo e(route('repayments.record', $repayment)); ?>" method="POST" class="px-6 py-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                            <input type="number" step="0.01" name="paid_amount" id="paid_amount" 
                                   value="<?php echo e(old('paid_amount', $repayment->getRemainingAmount())); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                            <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="paid_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                            <input type="date" name="paid_date" id="paid_date" 
                                   value="<?php echo e(old('paid_date', now()->format('Y-m-d'))); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                            <?php $__errorArgs = ['paid_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" id="payment_method" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                                <option value="">Select Method</option>
                                <option value="cash" <?php echo e(old('payment_method') === 'cash' ? 'selected' : ''); ?>>Cash</option>
                                <option value="bank_transfer" <?php echo e(old('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                <option value="mobile_money" <?php echo e(old('payment_method') === 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                <option value="check" <?php echo e(old('payment_method') === 'check' ? 'selected' : ''); ?>>Check</option>
                            </select>
                            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700">Reference Number</label>
                            <input type="text" name="payment_reference" id="payment_reference" 
                                   value="<?php echo e(old('payment_reference')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                   placeholder="Transaction ID, Check Number, etc.">
                            <?php $__errorArgs = ['payment_reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/repayments/show.blade.php ENDPATH**/ ?>