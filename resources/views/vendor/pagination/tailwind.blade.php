@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1 mt-4" aria-label="Pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-xs text-gray-300 bg-gray-50 rounded-xl cursor-not-allowed border border-gray-100">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-xs text-gray-600 bg-white hover:bg-purple-50 hover:text-purple-700 rounded-xl border border-gray-200 transition">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2 text-xs text-gray-400">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3.5 py-2 text-xs font-bold text-white rounded-xl" style="background:#7B2FBE;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 text-xs text-gray-600 bg-white hover:bg-purple-50 hover:text-purple-700 rounded-xl border border-gray-200 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-xs text-gray-600 bg-white hover:bg-purple-50 hover:text-purple-700 rounded-xl border border-gray-200 transition">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-2 text-xs text-gray-300 bg-gray-50 rounded-xl cursor-not-allowed border border-gray-100">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
