<!DOCTYPE html>
<html lang="en">

    @include('frontend.partials.frontend.style')

    <body>




        @include('frontend.partials.frontend.navbar')


        @yield('content')


        @include('frontend.partials.frontend.footer')



        @include('frontend.partials.frontend.script')
        @stack('script-alt')
    </body>

</html>
