@if (session('exito'))
    <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-green-800">
        {{ session('exito') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">
        <p class="font-semibold">Hay errores en el formulario:</p>
        <ul class="list-disc ps-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
