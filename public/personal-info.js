document.addEventListener('DOMContentLoaded', function() {
    // Persian Datepicker
    $('#birth_date').pDatepicker({
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

    // پیش‌نمایش تصویر
    function previewImage(input, previewId, fileNameId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.classList.remove('hidden');

                document.getElementById(fileNameId).textContent = file.name;
            }
            reader.readAsDataURL(file);
        }
    }

    // پیش‌نمایش چند فایل
    function previewMultipleFiles(input, containerId, countId) {
        const files = input.files;
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        if (files.length > 0) {
            document.getElementById(countId).textContent = `${files.length} فایل انتخاب شده`;

            for (let i = 0; i < Math.min(files.length, 5); i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'border border-gray-200 rounded p-1';
                    previewDiv.innerHTML = `
                        <div class="relative pb-full">
                            <img src="${e.target.result}" class="absolute h-full w-full object-cover">
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-1">${files[i].name}</p>
                    `;
                    container.appendChild(previewDiv);
                }
                reader.readAsDataURL(files[i]);
            }
        } else {
            document.getElementById(countId).textContent = 'هیچ فایلی انتخاب نشده است';
        }
    }

    // رویدادهای آپلود فایل
    document.getElementById('photo').addEventListener('change', function() {
        previewImage(this, 'photoPreview', 'photoFileName');
    });

    document.getElementById('national_card_image').addEventListener('change', function() {
        previewImage(this, 'nationalCardPreview', 'nationalCardFileName');
    });

    document.getElementById('id_card_images').addEventListener('change', function() {
        previewMultipleFiles(this, 'idCardFilesContainer', 'idCardFilesCount');
    });

    document.getElementById('education_documents').addEventListener('change', function() {
        previewMultipleFiles(this, 'educationFilesContainer', 'educationFilesCount');
    });

    // ارسال فرم با AJAX
    document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> در حال ارسال...';

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'موفقیت‌آمیز!',
                        text: data.message,
                        confirmButtonText: 'باشه'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا!',
                        text: data.message,
                        confirmButtonText: 'متوجه شدم'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطا!',
                    text: 'مشکلی در ارتباط با سرور پیش آمده است.',
                    confirmButtonText: 'متوجه شدم'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    });
});
