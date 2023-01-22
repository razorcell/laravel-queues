<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @livewireStyles
</head>

<body class="antialiased">

    <h1>Hello World!</h1>

    <livewire:job-button />


    @livewireScripts
</body>

</html>