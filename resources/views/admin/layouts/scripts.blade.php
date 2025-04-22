<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/tobii/js/tobii.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/profile.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "language": {
                "search": "بحث:",
                "lengthMenu": "عرض _MENU_ سجل لكل صفحة",
                "zeroRecords": "لا توجد نتائج",
                "info": "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                "infoEmpty": "لا توجد بيانات متاحة",
                "infoFiltered": "(تمت تصفيته من إجمالي _MAX_ سجل)"
            }
        });
    });
</script>


@if (Session::has('success') || Session::has('error'))
    <script>
        Swal.mixin({
            toast: !0,
            position: "{{ app()->getLocale() == 'ar' ? 'top-start' : 'top-end' }}",
            showConfirmButton: !1,
            timer: 3e3,
            timerProgressBar: !0,
            didOpen: (e) => {
                e.addEventListener("mouseenter", Swal.stopTimer),
                    e.addEventListener("mouseleave", Swal.resumeTimer);
            },
        }).fire({
            text: "{{ Session::has('success') ? Session::get('success') : Session::get('error') }}",
            icon: "{{ Session::has('success') ? 'success' : 'error' }}",
        });
    </script>
@endif














@yield('scripts')
