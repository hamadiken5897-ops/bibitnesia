@if ($produk->hasPages())
    <div class="pagination-wrapper d-flex justify-content-center mt-4">

        {{-- Tombol Previous --}}
        @if ($produk->onFirstPage())
            <span class="btn btn-light mx-1 disabled">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $produk->previousPageUrl() }}" class="btn btn-light mx-1">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif


        {{-- Nomor Halaman --}}
        @foreach ($produk->links()->elements[0] as $page => $url)
            @if ($page == $produk->currentPage())
                <span class="btn btn-success mx-1 text-white">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="btn btn-light mx-1">{{ $page }}</a>
            @endif
        @endforeach


        {{-- Tombol Next --}}
        @if ($produk->hasMorePages())
            <a href="{{ $produk->nextPageUrl() }}" class="btn btn-light mx-1">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="btn btn-light mx-1 disabled">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </div>
@endif
