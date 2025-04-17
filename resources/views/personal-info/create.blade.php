<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرم اطلاعات شخصی</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(135deg, #4b6cb7, #182848);
            color: white;
            padding: 20px;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #4b6cb7;
            border-bottom: 3px solid #4b6cb7;
        }
        .tab-content {
            padding: 20px;
        }
        .file-upload-btn {
            background-color: #e9ecef;
            border: 1px dashed #adb5bd;
            cursor: pointer;
        }
        .file-upload-btn:hover {
            background-color: #dee2e6;
        }
        .preview-image {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="form-container">
        <!-- هدر فرم -->
        <div class="form-header">
            <h2>فرم اطلاعات شخصی</h2>
            <p class="mb-0">لطفا اطلاعات را با دقت تکمیل نمایید</p>
        </div>

        <!-- تب‌ها -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                    <i class="fas fa-user me-2"></i>اطلاعات شخصی
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                    <i class="fas fa-file-alt me-2"></i>مدارک
                </button>
            </li>
        </ul>

        <!-- فرم اصلی -->
        <form id="personalInfoForm" enctype="multipart/form-data">
            @csrf
            <div class="tab-content" id="myTabContent">
                <!-- تب اطلاعات شخصی -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="row g-3">
                        <!-- نام و نام خانوادگی -->
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">نام <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="firstName" id="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lastName" id="lastName" required>
                        </div>

                        <!-- جنسیت و نام پدر -->
                        <div class="col-md-6">
                            <label class="form-label">جنسیت <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" checked>
                                    <label class="form-check-label" for="male">مرد</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                    <label class="form-check-label" for="female">زن</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fatherName" class="form-label">نام پدر <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="fatherName" id="fatherName" required>
                        </div>

                        <!-- کد ملی و مدرک تحصیلی -->
                        <div class="col-md-6">
                            <label for="nationalCode" class="form-label">کد ملی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nationalCode" id="nationalCode" pattern="\d{10}" maxlength="10" required>
                        </div>
                        <div class="col-md-6">
                            <label for="education" class="form-label">مدرک تحصیلی <span class="text-danger">*</span></label>
                            <select class="form-select" id="education" required name="education">
                                <option value="" selected disabled>-- انتخاب کنید --</option>
                                <option value="diploma">دیپلم</option>
                                <option value="associate">فوق دیپلم</option>
                                <option value="bachelor">کارشناسی</option>
                                <option value="master">کارشناسی ارشد</option>
                                <option value="phd">دکترا</option>
                            </select>
                        </div>

                        <!-- شماره شناسنامه و سریال -->
                        <div class="col-md-6">
                            <label for="idNumber" class="form-label">شماره شناسنامه <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="idNumber" name="idNumber" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">سریال شناسنامه <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" class="form-control text-center" id="idSerialPart1" name="idSerialPart1" maxlength="1" style="width: 60px;" required>
                                <span>-</span>
                                <input type="text" class="form-control text-center" id="idSerialPart2" name="idSerialPart2" maxlength="6" style="width: 100px;" required>
                            </div>
                        </div>

                        <!-- محل صدور و تولد -->
                        <div class="col-md-6">
                            <label for="issuePlace" class="form-label">محل صدور <span class="text-danger">*</span></label>
                            <select class="form-select" id="issuePlace" name="issuePlace" required>
                                <option value="" selected disabled>-- انتخاب شهر --</option>
                                <option value="1">تهران</option>
                                <option value="2">مشهد</option>
                                <option value="3">اصفهان</option>
                                <option value="4">شیراز</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="birthPlace" class="form-label">محل تولد <span class="text-danger">*</span></label>
                            <select class="form-select" id="birthPlace" name="birthPlace" required>
                                <option value="" selected disabled>-- انتخاب شهر --</option>
                                <option value="1">تهران</option>
                                <option value="2">مشهد</option>
                                <option value="3">اصفهان</option>
                                <option value="4">شیراز</option>
                            </select>
                        </div>

                        <!-- تاریخ تولد -->
                        <div class="col-12">
                            <label for="birthDate" class="form-label">تاریخ تولد <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-50" id="birthDate" name="birthDate" required readonly>
                        </div>
                    </div>

                    <!-- دکمه بعدی -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary next-tab">
                            مرحله بعد <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- تب مدارک -->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <!-- عکس پرسنلی -->
                    <div class="mb-4">
                        <label class="form-label">عکس ۳ در ۴ <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <input type="file" id="photo" name="photo" accept="image/*" class="d-none">
                            <button type="button" class="btn file-upload-btn me-3" id="photoBtn">
                                <i class="fas fa-camera me-2"></i>انتخاب فایل
                            </button>
                            <span id="photoFileName">هیچ فایلی انتخاب نشده است</span>
                        </div>
                        <div id="photoPreview" class="preview-image" style="width: 100px; height: 133px; display: none;"></div>
                    </div>

                    <!-- تصویر کارت ملی -->
                    <div class="mb-4">
                        <label class="form-label">تصویر کارت ملی <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <input type="file" id="nationalCard" name="nationalCard" accept="image/*" class="d-none">
                            <button type="button" class="btn file-upload-btn me-3" id="nationalCardBtn">
                                <i class="fas fa-id-card me-2"></i>انتخاب فایل
                            </button>
                            <span id="nationalCardFileName">هیچ فایلی انتخاب نشده است</span>
                        </div>
                        <div id="nationalCardPreview" class="preview-image" style="max-width: 300px; display: none;"></div>
                    </div>

                    <!-- صفحات شناسنامه -->
                    <div class="mb-4">
                        <label class="form-label">صفحات شناسنامه (حداکثر ۵ فایل)</label>
                        <div class="d-flex align-items-center">
                            <input type="file" id="idCard" name="idCard" multiple accept="image/*" class="d-none">
                            <button type="button" class="btn file-upload-btn me-3" id="idCardBtn">
                                <i class="fas fa-images me-2"></i>انتخاب فایل‌ها
                            </button>
                            <span id="idCardFilesCount">هیچ فایلی انتخاب نشده است</span>
                        </div>
                        <div id="idCardFilesContainer" class="row mt-2 g-2"></div>
                    </div>

                    <!-- مدارک تحصیلی -->
                    <div class="mb-4">
                        <label class="form-label">مدارک تحصیلی (حداکثر ۳ فایل)</label>
                        <div class="d-flex align-items-center">
                            <input type="file" id="educationDocs" name="educationDocs" multiple accept="image/*" class="d-none">
                            <button type="button" class="btn file-upload-btn me-3" id="educationDocsBtn">
                                <i class="fas fa-graduation-cap me-2"></i>انتخاب فایل‌ها
                            </button>
                            <span id="educationDocsCount">هیچ فایلی انتخاب نشده است</span>
                        </div>
                        <div id="educationDocsContainer" class="row mt-2 g-2"></div>
                    </div>

                    <!-- دکمه‌های ناوبری -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary prev-tab">
                            <i class="fas fa-arrow-right me-2"></i> مرحله قبل
                        </button>
                        <button type="submit" class="btn btn-success">
                            ثبت نهایی <i class="fas fa-check ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- اسکریپت‌های مورد نیاز -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize datepicker
        $("#birthDate").pDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false,
            observer: true,
            calendar: {
                persian: {
                    locale: 'persian',
                    showHint: true
                }
            }
        });

        // Tab navigation
        $('.next-tab').click(function() {
            $('#documents-tab').tab('show');
        });

        $('.prev-tab').click(function() {
            $('#personal-tab').tab('show');
        });

        // File upload buttons
        $('#photoBtn').click(function() {
            $('#photo').click();
        });

        $('#nationalCardBtn').click(function() {
            $('#nationalCard').click();
        });

        $('#idCardBtn').click(function() {
            $('#idCard').click();
        });

        $('#educationDocsBtn').click(function() {
            $('#educationDocs').click();
        });

        // Preview images
        $('#photo').change(function() {
            const file = this.files[0];
            if (file) {
                $('#photoFileName').text(file.name);
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photoPreview').html('<img src="' + e.target.result + '" class="w-100 h-100 object-cover">').show();
                }
                reader.readAsDataURL(file);
            }
        });

        $('#nationalCard').change(function() {
            const file = this.files[0];
            if (file) {
                $('#nationalCardFileName').text(file.name);
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#nationalCardPreview').html('<img src="' + e.target.result + '" class="w-100">').show();
                }
                reader.readAsDataURL(file);
            }
        });

        $('#idCard').change(function() {
            const files = this.files;
            $('#idCardFilesContainer').empty();
            if (files.length > 0) {
                $('#idCardFilesCount').text(files.length + ' فایل انتخاب شده');
                for (let i = 0; i < Math.min(files.length, 5); i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#idCardFilesContainer').append(`
                            <div class="col-md-4 col-6">
                                <div class="border rounded p-1">
                                    <div style="height: 120px; overflow: hidden;">
                                        <img src="${e.target.result}" class="w-100 h-100 object-cover">
                                    </div>
                                    <p class="text-truncate small mt-1 mb-0">${files[i].name}</p>
                                </div>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(files[i]);
                }
            } else {
                $('#idCardFilesCount').text('هیچ فایلی انتخاب نشده است');
            }
        });

        $('#educationDocs').change(function() {
            const files = this.files;
            $('#educationDocsContainer').empty();
            if (files.length > 0) {
                $('#educationDocsCount').text(files.length + ' فایل انتخاب شده');
                for (let i = 0; i < Math.min(files.length, 3); i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#educationDocsContainer').append(`
                            <div class="col-md-4 col-6">
                                <div class="border rounded p-1">
                                    <div style="height: 120px; overflow: hidden;">
                                        <img src="${e.target.result}" class="w-100 h-100 object-cover">
                                    </div>
                                    <p class="text-truncate small mt-1 mb-0">${files[i].name}</p>
                                </div>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(files[i]);
                }
            } else {
                $('#educationDocsCount').text('هیچ فایلی انتخاب نشده است');
            }
        });

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

        // Form submission
        $('#personalInfoForm').submit(function(e) {
            e.preventDefault();

            // Validate form
            if (!$('#photo').val()) {
                alert('لطفا عکس پرسنلی را انتخاب کنید');
                return;
            }

            if (!$('#nationalCard').val()) {
                alert('لطفا تصویر کارت ملی را انتخاب کنید');
                return;
            }

            // Validate national code
            const nationalCode = $('#nationalCode').val();
            if (!/^\d{10}$/.test(nationalCode)) {
                alert('کد ملی باید 10 رقم باشد');
                return;
            }

            // National code validation algorithm
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(nationalCode[i]) * (10 - i);
            }

            const remainder = sum % 11;
            const controlDigit = parseInt(nationalCode[9]);

            const isValid = (remainder < 2 && controlDigit === remainder) ||
                (remainder >= 2 && controlDigit === (11 - remainder));

            if (!isValid) {
                alert('کد ملی وارد شده معتبر نیست');
                return;
            }

            // Create FormData
            const formData = new FormData(this);

            // Add form values
            formData.append('first_name', $('#firstName').val());
            formData.append('last_name', $('#lastName').val());
            formData.append('gender', $('input[name="gender"]:checked').val());
            formData.append('father_name', $('#fatherName').val());
            formData.append('national_code', nationalCode);
            formData.append('education_level', $('#education').val());
            formData.append('id_number', $('#idNumber').val());
            formData.append('idSerial', $('#idSerialPart1').val() + '-' + $('#idSerialPart2').val());
            formData.append('id_serial_part1', $('#idSerialPart1').val());
            formData.append('id_serial_part2', $('#idSerialPart2').val());
            formData.append('issue_place', $('#issuePlace').val());
            formData.append('birth_place', $('#birthPlace').val());
            formData.append('birth_date', $('#birthDate').val());

            // Here you would normally send the formData to server
            console.log('Form submitted!', Object.fromEntries(formData));
            alert('فرم با موفقیت ارسال شد!');

            // Reset form
            this.reset();
            $('#photoPreview, #nationalCardPreview').hide().empty();
            $('#idCardFilesContainer, #educationDocsContainer').empty();
            $('#photoFileName, #nationalCardFileName').text('هیچ فایلی انتخاب نشده است');
            $('#idCardFilesCount, #educationDocsCount').text('هیچ فایلی انتخاب نشده است');
            $('#personal-tab').tab('show');

            // Send AJAX request
            $.ajax({
                url: "personal-info",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'موفق',
                            text: 'اطلاعات با موفقیت ثبت شد',
                            confirmButtonText: 'باشه'
                        }).then(() => {
                            // Reset form
                            $('#personalInfoForm')[0].reset();
                            $('#photoPreview, #nationalCardPreview').hide().empty();
                            $('#idCardFilesContainer, #educationDocsContainer').empty();
                            $('#photoFileName, #nationalCardFileName').text('هیچ فایلی انتخاب نشده است');
                            $('#idCardFilesCount, #educationDocsCount').text('هیچ فایلی انتخاب نشده است');
                            $('#personal-tab').tab('show');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا',
                            text: response.message || 'خطا در ثبت اطلاعات'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'خطا در ارسال اطلاعات';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        // Show errors under each field
                        $.each(errors, function(key, value) {
                            const field = key.replace(/\./g, '_');
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}Error`).text(value[0]);
                        });
                        errorMessage = 'لطفا خطاهای فرم را برطرف کنید';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: errorMessage
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('ثبت نهایی <i class="fas fa-check ms-2"></i>');
                }
            });
        });
    });
</script>
</body>
</html>
