{{-- Laravel pagination --}}
<div class="d-flex justify-content-end mt-3">
    @if ($data->lastPage() > 1)
        <nav>
            <ul class="pagination justify-content-end">
                {{-- Previous --}}
                <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $data->previousPageUrl() ?? '#' }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                @php
                    $total = $data->lastPage();
                    $current = $data->currentPage();
                    $start = max(1, $current - 1);
                    $end = min($total, $current + 1);
                @endphp

                {{-- Always show first page --}}
                @if ($start > 1)
                    <li class="page-item {{ 1 == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $data->url(1) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif

                {{-- Pages around current --}}
                @for ($page = $start; $page <= $end; $page++)
                    <li class="page-item {{ $page == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $data->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                {{-- Ellipsis before last 2 pages --}}
                @if ($end < $total - 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif

                {{-- Last 2 pages --}}
                @for ($page = $total - 1; $page <= $total; $page++)
                    @if ($page > $end)
                        <li class="page-item {{ $page == $current ? 'active' : '' }}">
                            <a class="page-link" href="{{ $data->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Next --}}
                <li class="page-item {{ !$data->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $data->nextPageUrl() ?? '#' }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>
