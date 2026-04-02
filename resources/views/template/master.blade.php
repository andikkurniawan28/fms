<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="/sneat/assets/" data-template="vertical-menu-template-free">
  <head>
    @include('template.head')
    @yield('style')
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('template.sidebar')
        <div class="layout-page">
          @include('template.navbar')
          <div class="content-wrapper">
            @yield('content')
            @include('template.footer')
            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('template.script')
    @yield('script')
  </body>
</html>
