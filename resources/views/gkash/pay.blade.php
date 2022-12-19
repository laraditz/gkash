@extends('gkash::layouts.app')

@section('content')
<div class="container max-w-6xl mx-auto mt-20">
    <div class="flex flex-col justify-center items-center">
        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="mt-4">Redirecting to payment page. Please do not close this page.</div>
        <div class="mt-4">Click below button if it did not redirect within 5 seconds.</div>

        <form method="POST" action="{{ $formAction }}" id="form-payment">
            @foreach($formInputs as $name=>$value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="form-control mt-4
                block
                w-full
                px-3
                py-1.5
                text-base
                font-normal
                text-gray-700
                bg-white bg-clip-padding
                border border-solid border-gray-300
                rounded
                transition
                ease-in-out
                m-0
            focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
            />
            @endforeach
            <button type="submit" class="mt-4 inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-md leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Proceed</button>
        </form>
    </div>
</div>
<script>
    setTimeout(()=>document.getElementById("form-payment").submit(), 2000);
</script>
@endsection
