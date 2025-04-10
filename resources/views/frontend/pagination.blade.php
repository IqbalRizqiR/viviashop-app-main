@if ($paginator->hasPages())
    <div class="col-12">
        <div class="pagination d-flex justify-content-center mt-5">
            <a href="#" class="rounded">&laquo;</a>
            <a href="#" class="active rounded">1</a>
            <a href="#" class="rounded">2</a>
            <a href="#" class="rounded">3</a>
            <a href="#" class="rounded">4</a>
            <a href="#" class="rounded">5</a>
            <a href="#" class="rounded">6</a>
            <a href="#" class="rounded">&raquo;</a>
        </div>
    </div>
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="rounded">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="rounded">&laquo;</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="rounded">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="rounded">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
