{{-- Status Badge Partial --}}
{{-- Usage: @include('admin.partials._status_badge', ['status' => $item->status, 'publishedAt' => $item->published_at]) --}}

@php
    $badgeConfig = match($status ?? 'published') {
        'draft' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-400', 'label' => 'Draft'],
        'scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-400', 'label' => 'Dijadwalkan'],
        'published' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-400', 'label' => 'Published'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-400', 'label' => 'Unknown'],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }}">
    {{ $badgeConfig['label'] }}
    @if($status === 'scheduled' && isset($publishedAt))
        <span class="text-[10px] font-normal opacity-75">
            ({{ \Carbon\Carbon::parse($publishedAt)->format('d M Y, H:i') }})
        </span>
    @endif
</span>
