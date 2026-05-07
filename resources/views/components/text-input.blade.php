@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 rounded-lg shadow-sm transition-all duration-300 ease-out bg-gray-50 focus:bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400']) }}>

