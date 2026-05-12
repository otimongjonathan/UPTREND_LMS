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
                Repayment Schedule - Loan #<?php echo e($schedule->loan_application_id); ?>

            </h2>
            <a href="<?php echo e(route('schedules.index')); ?>" class="text-blue-600 hover:text-blue-900">
                ← Back to All Schedules
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Total Repayable</div>
                    <div class="text-2xl font-bold">UGX <?php echo e(number_format($schedule->total_repayable, 2)); ?></div>
                    <div class="text-xs text-gray-500 mt-1">
                        Principal: <?php echo e(number_format($schedule->total_loan_amount, 2)); ?><br>
                        Interest: <?php echo e(number_format($schedule->total_interest, 2)); ?><br>
                        Fees: <?php echo e(number_format($schedule->total_fees ?? 0, 2)); ?><br>
                        Taxes: <?php echo e(number_format($schedule->total_taxes ?? 0, 2)); ?>

                    </div>
                </div>
                <div class="bg-green-50 p-6 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Total Paid</div>
                    <div class="text-2xl font-bold text-green-600">UGX <?php echo e(number_format($schedule->total_paid, 2)); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($schedule->installments_paid); ?>/<?php echo e($schedule->total_installments); ?> installments</div>
                </div>
                <div class="bg-red-50 p-6 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Outstanding</div>
                    <div class="text-2xl font-bold text-red-600">UGX <?php echo e(number_format($schedule->total_outstanding, 2)); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($schedule->installments_overdue); ?> overdue</div>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Grace Period</div>
                    <div class="text-lg font-bold text-blue-600"><?php echo e($schedule->grace_period_months); ?> months</div>
                    <div class="text-xs text-gray-500">Ended: <?php echo e($schedule->grace_period_end_date->format('M d, Y')); ?></div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Loan Details</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">Borrower:</span>
                            <p class="font-medium"><?php echo e($schedule->loanApplication->user->name); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Loan Amount:</span>
                            <p class="font-medium">UGX <?php echo e(number_format($schedule->total_loan_amount, 2)); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Interest:</span>
                            <p class="font-medium">UGX <?php echo e(number_format($schedule->total_interest, 2)); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Payment Frequency:</span>
                            <p class="font-medium"><?php echo e(ucfirst($schedule->payment_frequency)); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">First Payment:</span>
                            <p class="font-medium"><?php echo e($schedule->first_payment_date->format('M d, Y')); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Final Payment:</span>
                            <p class="font-medium"><?php echo e($schedule->final_payment_date->format('M d, Y')); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Installment Amount:</span>
                            <p class="font-medium">UGX <?php echo e(number_format($schedule->installment_amount, 2)); ?></p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Status:</span>
                            <p class="font-medium">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php if($schedule->status === 'completed'): ?> bg-green-100 text-green-800
                                    <?php elseif($schedule->status === 'defaulted'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-blue-100 text-blue-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($schedule->status)); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="<?php echo e(route('schedules.regenerate', $schedule->loanApplication)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Regenerate Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Installments Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Installments</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Principal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interest</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $schedule->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php if($installment['status'] === 'overdue'): ?> bg-red-50 <?php endif; ?>">
                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e($installment['number']); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($installment['due_date'])->format('M d, Y')); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e(number_format($installment['principal_amount'], 2)); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e(number_format($installment['interest_amount'], 2)); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold"><?php echo e(number_format($installment['total_amount'], 2)); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-green-600"><?php echo e(number_format($installment['paid_amount'], 2)); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e(number_format($installment['remaining_balance'], 2)); ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php if($installment['status'] === 'paid'): ?> bg-green-100 text-green-800
                                            <?php elseif($installment['status'] === 'overdue'): ?> bg-red-100 text-red-800
                                            <?php elseif($installment['status'] === 'partial'): ?> bg-yellow-100 text-yellow-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst($installment['status'])); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <?php if($installment['status'] !== 'paid'): ?>
                                        <button onclick="openPaymentModal(<?php echo e($installment['number']); ?>, <?php echo e($installment['total_amount'] - $installment['paid_amount']); ?>)" 
                                                class="text-blue-600 hover:text-blue-900 text-sm">
                                            Record Payment
                                        </button>
                                        <?php else: ?>
                                        <span class="text-xs text-gray-500">Paid: <?php echo e(\Carbon\Carbon::parse($installment['paid_date'])->format('M d, Y')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4">Record Payment</h3>
            <form id="paymentForm" action="<?php echo e(route('schedules.payment', $schedule)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="installment_number" id="installmentNumber">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" id="paymentAmount" step="0.01" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reference</label>
                    <input type="text" name="payment_reference" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="2" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closePaymentModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(installmentNumber, amount) {
            document.getElementById('installmentNumber').value = installmentNumber;
            document.getElementById('paymentAmount').value = amount;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
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
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/repayment-schedules/show.blade.php ENDPATH**/ ?>