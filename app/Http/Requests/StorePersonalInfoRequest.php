<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonalInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'father_name' => 'required|string|max:50',
            'national_code' => [
                'required',
                'digits:10',
                'unique:personal_infos,national_code',
                function ($attribute, $value, $fail) {
                    if (!$this->validateNationalCode($value)) {
                        $fail('کد ملی وارد شده معتبر نیست.');
                    }
                }
            ],
            'education_level' => 'required|in:diploma,associate,bachelor,master,phd',
            'id_number' => 'required|string|max:20',
//            'id_serial_part1' => 'required|string|size:1|regex:/^[الف-ی]$/u',
//            'id_serial_part2' => 'required|digits:6',
//            'issue_place' => 'required|exists:cities,id',
//            'birth_place' => 'required|exists:cities,id',
            'birth_date' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:ratio=3/4',
            'nationalCard' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'idCard' => 'nullable|max:5',
            'idCard.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'educationDocs' => 'nullable|max:3',
            'educationDocs.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'فیلد :attribute الزامی است.',
            'photo.dimensions' => 'نسبت ابعاد عکس باید 3 در 4 باشد.',
            'national_code.digits' => 'کد ملی باید 10 رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلا ثبت شده است.',
            'id_serial_part1.regex' => 'بخش حرفی سریال شناسنامه باید حرف فارسی باشد.',
            'id_serial_part2.digits' => 'بخش عددی سریال شناسنامه باید 1 رقم باشد.',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'father_name' => 'نام پدر',
            'national_code' => 'کد ملی',
            'education_level' => 'مدرک تحصیلی',
            'id_number' => 'شماره شناسنامه',
            'id_serial_part1' => 'حرف سریال شناسنامه',
            'id_serial_part2' => 'عدد سریال شناسنامه',
            'issue_place' => 'محل صدور',
            'birth_place' => 'محل تولد',
            'birth_date' => 'تاریخ تولد',
            'photo' => 'عکس پرسنلی',
            'nationalCard' => 'تصویر کارت ملی',
            'idCard' => 'صفحات شناسنامه',
            'educationDocs' => 'مدارک تحصیلی'
        ];
    }

    private function validateNationalCode($code)
    {
        if (!preg_match('/^\d{10}$/', $code)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $code[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $controlDigit = (int) $code[9];

        if ($remainder < 2) {
            return $controlDigit === $remainder;
        } else {
            return $controlDigit === (11 - $remainder);
        }
    }
}
