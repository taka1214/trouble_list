<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-alert border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 active:bg-opacity-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
