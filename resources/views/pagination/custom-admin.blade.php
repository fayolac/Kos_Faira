@if ($paginator->hasPages())
@php
    $activeTab = request()->query('tab');
@endphp
<nav>
    <ul class="pagination pagination-sm justify-content-end mb-1">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&laquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link"
                   href="{{ $paginator->previousPageUrl() }}{{ $activeTab ? '&tab='.$activeTab : '' }}">
                   &laquo;
                </a>
            </li>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ $url }}{{ $activeTab ? '&tab='.$activeTab : '' }}">
                               {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link"
                   href="{{ $paginator->nextPageUrl() }}{{ $activeTab ? '&tab='.$activeTab : '' }}">
                   &raquo;
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">&raquo;</span>
            </li>
        @endif

    </ul>
    <p style="font-size:0.75rem; color:#aaa; text-align:right; margin:0;">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
        dari {{ $paginator->total() }} data
    </p>
</nav>
@endif