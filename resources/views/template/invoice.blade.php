<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="/sneat/assets/" data-template="vertical-menu-template-free">

<head>
    @include('template.head')
    @yield('style')
</head>

<body>
    <div class="content-wrapper">
        @yield('content')
    </div>
    @include('template.script')
    @yield('script')
</body>

</html>
