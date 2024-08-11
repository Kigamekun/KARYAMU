<div class="card">

    <div class="card-body px-4 py-2 d-flex justify-content-between align-items-center">
        <h4 style="font-weight: bolder" class="mt-2">
            {{ $title }}
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-right mt-3">
                @foreach ($breadcrumbs as $key => $breadcrumb)
                    @if ($key == count($breadcrumbs) - 1)
                        <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</div>
