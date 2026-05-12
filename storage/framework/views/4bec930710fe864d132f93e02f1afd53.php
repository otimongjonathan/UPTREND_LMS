<?php $__env->startSection('content'); ?>
    <!-- Application Form Header -->
    <div class="mb-8 bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold uppercase tracking-wide">New Loan Application</h1>
                <p class="text-orange-100 mt-1 text-lg">Complete the form below to apply for a loan</p>
            </div>
        </div>
    </div>

    
    <?php if($product): ?>
        <div class="mb-8 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-orange-300 p-6 shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-700 mb-1">Selected Loan Product</p>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3"><?php echo e($product->name); ?></h2>
                    <p class="text-gray-700 mb-4"><?php echo e($product->description); ?></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Amount Range</p>
                            <p class="text-lg font-bold text-orange-600">UGX <?php echo e(number_format($product->min_amount, 0)); ?> - <?php echo e(number_format($product->max_amount, 0)); ?></p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Term Duration</p>
                            <p class="text-lg font-bold text-gray-900"><?php echo e($product->min_term); ?>-<?php echo e($product->max_term); ?> months</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Interest Rate</p>
                            <p class="text-lg font-bold text-orange-600"><?php echo e(number_format($product->interest_rate, 2)); ?>%</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Processing Fee</p>
                            <p class="text-lg font-bold text-gray-900"><?php echo e(number_format($product->processing_fee_percent, 2)); ?>%</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Insurance Premium</p>
                            <p class="text-lg font-bold text-gray-900"><?php echo e(number_format($product->insurance_premium_percent, 2)); ?>%</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Late Payment Fee</p>
                            <p class="text-lg font-bold text-red-600"><?php echo e(number_format($product->late_payment_fee_percent, 2)); ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('status')): ?>
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-700 px-6 py-4 flex items-center gap-3">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold"><?php echo e(session('status')); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('customer.loans.store', absolute: false)); ?>" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>

        <!-- Hidden field for loan product if coming from loan products page -->
        <?php if($product): ?>
            <input type="hidden" name="loan_product_id" value="<?php echo e($product->id); ?>">
        <?php elseif(request('product_id')): ?>
            <input type="hidden" name="loan_product_id" value="<?php echo e(request('product_id')); ?>">
        <?php endif; ?>

        <!-- Applicant Details -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Applicant Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name (As on ID)</label>
                    <input type="text" name="applicant_full_name" value="<?php echo e(old('applicant_full_name')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['applicant_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="dob" value="<?php echo e(old('dob')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                    <select name="gender" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select gender</option>
                        <option value="male" <?php if(old('gender') === 'male'): echo 'selected'; endif; ?>>Male</option>
                        <option value="female" <?php if(old('gender') === 'female'): echo 'selected'; endif; ?>>Female</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Marital Status</label>
                    <select name="marital_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="single" <?php if(old('marital_status') === 'single'): echo 'selected'; endif; ?>>Single</option>
                        <option value="married" <?php if(old('marital_status') === 'married'): echo 'selected'; endif; ?>>Married</option>
                        <option value="divorced" <?php if(old('marital_status') === 'divorced'): echo 'selected'; endif; ?>>Divorced</option>
                        <option value="widowed" <?php if(old('marital_status') === 'widowed'): echo 'selected'; endif; ?>>Widowed</option>
                    </select>
                    <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employment Status</label>
                    <select name="employment_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="employed" <?php if(old('employment_status') === 'employed'): echo 'selected'; endif; ?>>Employed</option>
                        <option value="self_employed" <?php if(old('employment_status') === 'self_employed'): echo 'selected'; endif; ?>>Self Employed</option>
                        <option value="unemployed" <?php if(old('employment_status') === 'unemployed'): echo 'selected'; endif; ?>>Unemployed</option>
                        <option value="student" <?php if(old('employment_status') === 'student'): echo 'selected'; endif; ?>>Student</option>
                        <option value="retired" <?php if(old('employment_status') === 'retired'): echo 'selected'; endif; ?>>Retired</option>
                    </select>
                    <?php $__errorArgs = ['employment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="<?php echo e(old('occupation')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['occupation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Residence Address -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Residence Address
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">District/City</label>
                    <input type="text" name="district_city" value="<?php echo e(old('district_city')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['district_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">County</label>
                    <input type="text" name="county" value="<?php echo e(old('county')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['county'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub County</label>
                    <input type="text" name="sub_county" value="<?php echo e(old('sub_county')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['sub_county'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Parish</label>
                    <input type="text" name="parish" value="<?php echo e(old('parish')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['parish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Village</label>
                    <input type="text" name="village" value="<?php echo e(old('village')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['village'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Residence Status</label>
                    <select name="residence_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="permanent" <?php if(old('residence_status') === 'permanent'): echo 'selected'; endif; ?>>Permanent</option>
                        <option value="temporary" <?php if(old('residence_status') === 'temporary'): echo 'selected'; endif; ?>>Temporary</option>
                    </select>
                    <?php $__errorArgs = ['residence_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">PO Box</label>
                    <input type="text" name="po_box" value="<?php echo e(old('po_box')); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['po_box'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Loan Request Details -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Loan Request Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Type</label>
                    <select name="loan_type" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select loan type</option>
                        <?php
                            $preselectedType = '';
                            if($product) {
                                $productName = strtolower($product->name);
                                if(str_contains($productName, 'salary')) {
                                    $preselectedType = 'salary_loan';
                                } elseif(str_contains($productName, 'business')) {
                                    $preselectedType = 'business_loan';
                                } elseif(str_contains($productName, 'education')) {
                                    $preselectedType = 'education_loan';
                                }
                            }
                        ?>
                        <option value="salary_loan" <?php if(old('loan_type') === 'salary_loan' || $preselectedType === 'salary_loan'): echo 'selected'; endif; ?>>Salary Loan</option>
                        <option value="business_loan" <?php if(old('loan_type') === 'business_loan' || $preselectedType === 'business_loan'): echo 'selected'; endif; ?>>Business Loan</option>
                        <option value="education_loan" <?php if(old('loan_type') === 'education_loan' || $preselectedType === 'education_loan'): echo 'selected'; endif; ?>>Education Loan</option>
                    </select>
                    <?php $__errorArgs = ['loan_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Loan Amount (UGX)
                        <?php if($product): ?>
                            <span class="text-xs text-orange-600 ml-2">(Min: <?php echo e(number_format($product->min_amount, 0)); ?> - Max: <?php echo e(number_format($product->max_amount, 0)); ?>)</span>
                        <?php endif; ?>
                    </label>
                    <?php
                        $minAmount = $product ? $product->min_amount : 1;
                        $maxAmount = $product ? $product->max_amount : 999999;
                        $defaultAmount = ($product && !old('amount')) ? $product->min_amount : old('amount', '');
                    ?>
                    <input type="number" step="0.01" min="<?php echo e($minAmount); ?>" max="<?php echo e($maxAmount); ?>" name="amount" value="<?php echo e($defaultAmount); ?>" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($product): ?>
                        <p class="text-xs text-gray-500 mt-1">Amount must be between UGX <?php echo e(number_format($product->min_amount, 0)); ?> and <?php echo e(number_format($product->max_amount, 0)); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Repayment Schedule</label>
                    <select name="repayment_schedule" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select schedule</option>
                        <option value="weekly" <?php if(old('repayment_schedule') === 'weekly'): echo 'selected'; endif; ?>>Weekly</option>
                        <option value="bi_weekly" <?php if(old('repayment_schedule') === 'bi_weekly'): echo 'selected'; endif; ?>>Bi-Weekly</option>
                        <option value="monthly" <?php if(old('repayment_schedule') === 'monthly'): echo 'selected'; endif; ?>>Monthly</option>
                        <option value="quarterly" <?php if(old('repayment_schedule') === 'quarterly'): echo 'selected'; endif; ?>>Quarterly</option>
                    </select>
                    <?php $__errorArgs = ['repayment_schedule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Purpose</label>
                    <textarea name="purpose" rows="3" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2"><?php echo e(old('purpose')); ?></textarea>
                    <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Required Documents (PDF Only)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Police Letter</label>
                    <input type="file" name="police_letter" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['police_letter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Financial Statement</label>
                    <input type="file" name="financial_statement" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['financial_statement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">National ID Copy</label>
                    <input type="file" name="national_id" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Guarantee 1</label>
                    <input type="file" name="loan_guarantee_one" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['loan_guarantee_one'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Guarantee 2</label>
                    <input type="file" name="loan_guarantee_two" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['loan_guarantee_two'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Proof of Residence (LC1 Letter)</label>
                    <input type="file" name="proof_of_residence" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    <?php $__errorArgs = ['proof_of_residence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <a href="<?php echo e(route('customer.loans.index')); ?>" class="px-6 py-3 rounded-xl border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Submit Application
            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/customer/loans/apply.blade.php ENDPATH**/ ?>