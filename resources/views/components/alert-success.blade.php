@if(session('success'))
    <div class="mb-4 rounded bg-green-50 border border-green-200 text-green-800 p-3">
        {{ session('success') }}
    </div>
@endif
