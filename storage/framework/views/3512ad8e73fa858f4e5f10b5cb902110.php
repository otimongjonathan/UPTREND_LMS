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
                💰 Loan Details
            </h2>
            <a href="<?php echo e(route('loans.index')); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back to Loans
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Loan Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900">Loan Application #<?php echo e($loan->id); ?></h3>
                    <p class="text-gray-600 text-sm mt-1">Submitted on <?php echo e($loan->application_date?->format('M d, Y')); ?></p>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Applicant Name</p>
                        <p class="text-lg text-gray-900"><?php echo e($loan->applicant_full_name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Borrower Account</p>
                        <p class="text-lg text-gray-900"><?php echo e($loan->user?->name); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Loan Amount</p>
                        <p class="text-lg font-bold text-green-600">UGX <?php echo e(number_format($loan->amount, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Loan Type</p>
                        <p class="text-lg text-gray-900"><?php echo e(str($loan->loan_type)->replace('_', ' ')->title()); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Loan Purpose</p>
                        <p class="text-lg text-gray-900"><?php echo e($loan->purpose); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Repayment Schedule</p>
                        <p class="text-lg text-gray-900"><?php echo e(str($loan->repayment_schedule)->replace('_', ' ')->title()); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Status</p>
                        <p class="text-lg">
                            <span class="px-3 py-1 rounded-full text-sm font-bold
                                <?php if($loan->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($loan->status === 'approved'): ?> bg-blue-100 text-blue-700
                                <?php elseif($loan->status === 'issued'): ?> bg-green-100 text-green-700
                                <?php elseif($loan->status === 'rejected'): ?> bg-red-100 text-red-700
                                <?php endif; ?>">
                                <?php echo e(str($loan->status)->title()); ?>

                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Term (Months)</p>
                        <p class="text-lg text-gray-900"><?php echo e($loan->term_months); ?></p>
                    </div>
                </div>

                <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-sm text-gray-600 font-semibold mb-2">Notes</p>
                    <p class="text-gray-900"><?php echo e($loan->notes ?? 'No notes.'); ?></p>
                </div>
            </div>

            <!-- Applicant Details -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Date of Birth</p>
                        <p class="text-gray-900"><?php echo e($loan->dob?->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Gender</p>
                        <p class="text-gray-900"><?php echo e(str($loan->gender)->title()); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Marital Status</p>
                        <p class="text-gray-900"><?php echo e(str($loan->marital_status)->replace('_', ' ')->title()); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Employment Status</p>
                        <p class="text-gray-900"><?php echo e(str($loan->employment_status)->replace('_', ' ')->title()); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Occupation</p>
                        <p class="text-gray-900"><?php echo e($loan->occupation); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Monthly Income</p>
                        <p class="text-gray-900">UGX <?php echo e(number_format($loan->monthly_income, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Address Information</h3>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">District/City</p>
                        <p class="text-gray-900"><?php echo e($loan->district_city); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">County</p>
                        <p class="text-gray-900"><?php echo e($loan->county); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Sub-County</p>
                        <p class="text-gray-900"><?php echo e($loan->sub_county); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Parish</p>
                        <p class="text-gray-900"><?php echo e($loan->parish); ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Village</p>
                        <p class="text-gray-900"><?php echo e($loan->village); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">P.O. Box</p>
                        <p class="text-gray-900"><?php echo e($loan->po_box); ?></p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 font-semibold">Residence Status</p>
                        <p class="text-gray-900"><?php echo e(str($loan->residence_status)->title()); ?></p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <?php if($loan->status !== 'pending'): ?>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Attached Documents</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-4">
                        <?php
                            $documents = [
                                'police_letter_path' => 'Police Letter',
                                'financial_statement_path' => 'Financial Statement',
                                'national_id_path' => 'National ID',
                                'loan_guarantee_one_path' => 'Loan Guarantee One',
                                'loan_guarantee_two_path' => 'Loan Guarantee Two',
                                'proof_of_residence_path' => 'Proof of Residence',
                            ];
                        ?>

                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($loan->$field): ?>
                                <a href="<?php echo e(route('loans.download', [$loan->id, $field])); ?>" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 16.5a1 1 0 11-2 0 1 1 0 012 0z"/>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0015.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                    </svg>
                                    <span class="text-blue-900 font-semibold text-sm"><?php echo e($label); ?></span>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Repayments -->
            <?php if($loan->status === 'issued' && $loan->repayments->count() > 0): ?>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Repayment Schedule</h3>
                </div>

                <div class="px-6 py-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Due Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Amount Due</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Paid Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Paid Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php $__currentLoopData = $loan->repayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repayment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($repayment->due_date?->format('M d, Y')); ?></td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">UGX <?php echo e(number_format($repayment->amount, 2)); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        <?php if($repayment->status === 'completed'): ?> bg-green-100 text-green-700
                                        <?php elseif($repayment->status === 'partial'): ?> bg-yellow-100 text-yellow-700
                                        <?php else: ?> bg-red-100 text-red-700
                                        <?php endif; ?>">
                                        <?php echo e(str($repayment->status)->title()); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">UGX <?php echo e(number_format($repayment->paid_amount ?? 0, 2)); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($repayment->paid_date?->format('M d, Y') ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <?php if($loan->status === 'pending'): ?>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="flex gap-3">
                        <form action="<?php echo e(route('applications.approve', $loan->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600 font-semibold">
                                ✓ Approve
                            </button>
                        </form>
                        <form action="<?php echo e(route('applications.reject', $loan->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold">
                                ✗ Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php elseif($loan->status === 'approved'): ?>
            <!-- Issue Loan (Disbursement) Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Ready to Issue Loan
                    </h3>
                </div>
                <div class="px-8 py-8 bg-blue-50">
                    <div class="mb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Loan Approved ✓</h4>
                        <p class="text-gray-700 text-lg mb-4">
                            This loan has been approved. Click below to proceed with disbursement and automatically generate the repayment schedule.
                        </p>
                        <div class="bg-blue-100 border-l-4 border-blue-600 p-4 rounded">
                            <p class="text-blue-900 font-semibold mb-2">
                                Next Steps:
                            </p>
                            <ul class="text-blue-800 space-y-1 ml-4">
                                <li>1️⃣ Complete disbursement details (bank transfer, cash, etc.)</li>
                                <li>2️⃣ Configure repayment schedule (frequency, installments)</li>
                                <li>3️⃣ System will automatically:
                                    <ul class="ml-6 mt-1">
                                        <li>✓ Disburse the loan</li>
                                        <li>✓ Generate repayment schedule with 2-month grace period</li>
                                        <li>✓ Notify the customer</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="<?php echo e(route('disbursements.create', $loan)); ?>" 
                           class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            ISSUE LOAN & DISBURSE
                        </a>
                        <a href="<?php echo e(route('loans.index')); ?>" 
                           class="px-8 py-4 bg-gray-400 hover:bg-gray-500 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                            Back to Loans
                        </a>
                    </div>
                </div>
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
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/loans/show.blade.php ENDPATH**/ ?>