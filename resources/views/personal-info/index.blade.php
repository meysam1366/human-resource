@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12" style="text-align: center;">
                <h2>لیست اطلاعات ثبت شده</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="text-align: center;">
                        <h4>اطلاعات شخصی</h4>
                        <a href="{{ route('personal-info.create') }}" class="btn btn-primary" style="color: red; font-weight: bold">
                            <i class="fas fa-plus"></i> ثبت جدید
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="personalInfoTable" border="1" class="table-auto table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>عکس</th>
                                <th>نام کامل</th>
                                <th>کد ملی</th>
                                <th>شماره شناسنامه</th>
                                <th>تاریخ ثبت</th>
{{--                                <th>عملیات</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach($infos as $info)
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a target="_blank" href="{{ env('APP_URL').'/'.$info->documents->where('type', 'photo')->first()->path }}">تصویر پرسنلی</a></td>
                                    <td>{{ $info->first_name . ' ' . $info->last_name }}</td>
                                    <td>{{ $info->national_code }}</td>
                                    <td>{{ $info->id_number }}</td>
                                    <td>{{ $info->created_at }}</td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal برای نمایش جزئیات -->
{{--    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">جزئیات اطلاعات</h5>--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                </div>--}}
{{--                <div class="modal-body" id="modalBody">--}}
{{--                    <!-- محتوای جزئیات اینجا نمایش داده می‌شود -->--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable

            $('#personalInfoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('personal-info.data') }}",
                    type: "GET",
                    dataSrc: function(json) {
                        console.log(json); // برای بررسی ساختار پاسخ
                        return json.data; // یا json.data.data بسته به ساختار پاسخ
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'photo_preview',
                        name: 'photo_preview',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'national_code',
                        name: 'national_code'
                    },
                    {
                        data: 'id_number',
                        name: 'id_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString('fa-IR') : '';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Persian.json"
                }
            });

            // مشاهده جزئیات
            $('#personalInfoTable').on('click', '.view-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '/personal-info/' + id,
                    type: 'GET',
                    success: function(response) {
                        // ساخت محتوای مودال
                        var content = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${response.photo_url}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            <img src="${response.national_card_url}" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">نام کامل</th>
                                    <td>${response.first_name} ${response.last_name}</td>
                                </tr>
                                <tr>
                                    <th>جنسیت</th>
                                    <td>${response.gender === 'male' ? 'مرد' : 'زن'}</td>
                                </tr>
                                <tr>
                                    <th>نام پدر</th>
                                    <td>${response.father_name}</td>
                                </tr>
                                <tr>
                                    <th>کد ملی</th>
                                    <td>${response.national_code}</td>
                                </tr>
                                <tr>
                                    <th>مدرک تحصیلی</th>
                                    <td>${getEducationText(response.education)}</td>
                                </tr>
                                <tr>
                                    <th>تاریخ ثبت</th>
                                    <td>${new Date(response.created_at).toLocaleDateString('fa-IR')}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;

                        $('#modalBody').html(content);
                        $('#detailsModal').modal('show');
                    }
                });
            });

            // تابع برای تبدیل مقدار مدرک تحصیلی به متن
            function getEducationText(education) {
                const educationMap = {
                    'diploma': 'دیپلم',
                    'associate': 'فوق دیپلم',
                    'bachelor': 'کارشناسی',
                    'master': 'کارشناسی ارشد',
                    'phd': 'دکترا'
                };
                return educationMap[education] || education;
            }

            // حذف رکورد
            $('#personalInfoTable').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'آیا مطمئن هستید؟',
                    text: "این عمل قابل بازگشت نیست!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله، حذف شود!',
                    cancelButtonText: 'انصراف'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/personal-info/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'حذف شد!',
                                        'رکورد با موفقیت حذف شد.',
                                        'success'
                                    );
                                    table.draw();
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
