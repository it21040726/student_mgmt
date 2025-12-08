@include('students.head')
<!--begin::App Wrapper-->
<div class="app-wrapper">
    <!--begin::Header-->
    @include('students.navbar')
    <!--end::Header-->
    <!--begin::Sidebar-->
    @include('students.sidebar')
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                @yield('content')

{{--                <div class="row">--}}
{{--                    <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>--}}

{{--                    --}}{{--<div class="col-sm-6">--}}
{{--                        <ol class="breadcrumb float-sm-end">--}}
{{--                            <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
{{--                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>--}}
{{--                        </ol>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!--end::Row--><div class="row">--}}
{{--                    <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>--}}

{{--                    --}}{{--<div class="col-sm-6">--}}
{{--                        <ol class="breadcrumb float-sm-end">--}}
{{--                            <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
{{--                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>--}}
{{--                        </ol>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!--end::Row-->--}}
            </div>
            <!--end::Container-->
                </div>
    </main>
{{--        </div>--}}
{{--        <!--end::App Content Header-->--}}
{{--        <!--begin::App Content-->--}}
{{--        <div class="app-content">--}}
{{--            <!--begin::Container-->--}}
{{--            <div class="container-fluid">--}}
{{--                --}}

{{--            </div>--}}
{{--            <!--end::Container-->--}}
{{--        </div>--}}
{{--        <!--end:: App Content-->--}}
{{--    </main>--}}
<!--end::App Main-->
<!--begin::Footer-->
@include('students.footer')
<!--end::Footer-->
</div>
<!--end:: App Wrapper-->
<!--begin::Script-->
<!--begin:: Third Party Plugin (OverlayScrollbars)-->
@include('students.script')
