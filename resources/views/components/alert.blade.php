@props(['type' => 'success'])

<div role="status" {{ $attributes->class([
    'flex items-start gap-3 rounded-2xl border px-5 py-4 text-sm font-medium',
    'border-growth-500/30 bg-growth-50 text-growth-600' => $type === 'success',
    'border-amber-300 bg-amber-50 text-amber-800' => $type === 'warning',
    'border-red-200 bg-red-50 text-red-800' => $type === 'error',
]) }}>
    <x-icon :name="$type === 'success' ? 'check' : 'alert'" class="mt-0.5 size-4 shrink-0" />
    <div>{{ $slot }}</div>
</div>
