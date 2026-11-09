@props(['name', 'label', 'type' => 'text', 'required' => false, 'hint' => null])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="field-label">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-ink-soft">(facultatif)</span>
        @endunless
    </label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="5" class="field-input" @required($required)
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->except('class') }}>{{ old($name) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" class="field-input" @required($required)
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->except('class') }}>
    @endif
    @if ($hint)
        <p class="mt-1.5 text-sm text-ink-soft">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="field-error">{{ $message }}</p>
    @enderror
</div>
