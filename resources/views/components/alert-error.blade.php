@if ($errors->any())
    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-800 p-3">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
