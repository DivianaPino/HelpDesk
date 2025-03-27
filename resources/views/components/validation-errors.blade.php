@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium" style="color:#EF9A9A;">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mt-3 list-disc list-inside" style="color:#EF9A9A; font-size:10px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
