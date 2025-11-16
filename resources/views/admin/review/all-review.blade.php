@extends('admin.layouts.app')
@section('content')



<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Ajax Sourced Server-side -->
        <div class="card">
            <h5 class="card-header pb-0 text-md-start text-center">Ajax Sourced Server-side</h5>
            <div class="card-datatable text-nowrap">
                <table class="datatables-ajax table table-bordered">
                    <thead>
                        <tr>
                            <th>Full name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Ajax Sourced Server-side -->




        <hr class="my-12" />


    </div>
    <!-- / Content -->



    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->


@endsection('content')