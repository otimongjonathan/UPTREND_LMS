<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-4 border-primary-600 text-sm font-bold leading-5 text-primary-700 focus:outline-none focus:border-primary-800 transition-all duration-200 ease-in-out uppercase tracking-wide'
            : 'inline-flex items-center px-3 pt-1 border-b-4 border-transparent text-sm font-semibold leading-5 text-gray-600 hover:text-primary-700 hover:border-primary-200 focus:outline-none focus:text-primary-700 focus:border-primary-200 transition-all duration-200 ease-in-out uppercase tracking-wide hover:bg-primary-50 rounded-t-lg';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/components/nav-link.blade.php ENDPATH**/ ?>