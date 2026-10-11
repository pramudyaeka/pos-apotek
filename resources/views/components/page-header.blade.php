<div class="mb-6">
    <p class="text-sm text-gray-500 mb-2">
        {{ $eyebrow }} <span class="mx-1 text-gray-300">/</span>
        <span class="font-medium text-gray-800" aria-current="page">{{ $heading }}</span>
    </p>
    <h1 class="font-['Space_Grotesk'] text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">{{ $heading }}</h1>
    @if(!empty($description))
        <p class="mt-1.5 max-w-3xl text-sm sm:text-base leading-relaxed text-gray-500">{{ $description }}</p>
    @endif
</div>
