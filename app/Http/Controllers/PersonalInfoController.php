<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonalInfoRequest;
use App\Models\PersonalInfo;
use App\Models\City;
use Illuminate\Http\Request;
//use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PersonalInfoController extends Controller
{
    // نمایش فرم
    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('personal-info.create', compact('cities'));
    }

    // ذخیره اطلاعات
    public function store(StorePersonalInfoRequest $request)
    {
        $personalInfo = $this->createPersonalInfo($request);
        $this->storeDocuments($request, $personalInfo);

        return response()->json([
            'success' => true,
            'message' => 'اطلاعات با موفقیت ثبت شد',
            'redirect' => route('personal-info.show', $personalInfo->id)
        ]);
    }

    // نمایش اطلاعات
//    public function show($id)
//    {
//        $info = PersonalInfo::with(['documents', 'issuePlace', 'birthPlace'])->findOrFail($id);
//        return view('personal-info.show', compact('info'));
//    }

    // لیست اطلاعات (ادمین)
    public function index()
    {
        $infos = PersonalInfo::with(['issuePlace', 'birthPlace', 'documents'])
            ->latest()
            ->paginate(15);

        return view('personal-info.index', compact('infos'));
    }

    // نمایش اطلاعات (ادمین)
    public function adminShow($id)
    {
        $info = PersonalInfo::with(['documents', 'issuePlace', 'birthPlace'])->findOrFail($id);
        return view('personal-info.admin.show', compact('info'));
    }

    // تایید اطلاعات
    public function approve($id)
    {
        $info = PersonalInfo::findOrFail($id);
        $info->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'اطلاعات با موفقیت تایید شد');
    }

    // رد اطلاعات
    public function reject($id)
    {
        $info = PersonalInfo::findOrFail($id);
        $info->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'اطلاعات با موفقیت رد شد');
    }

    // ایجاد اطلاعات شخصی
    private function createPersonalInfo($request)
    {
        return PersonalInfo::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'father_name' => $request->father_name,
            'national_code' => $request->national_code,
            'education_level' => $request->education,
            'id_number' => $request->id_number,
            'id_serial' => $request->id_serial_part1 . '-' . $request->id_serial_part2,
//            'id_serial' => $request->id_serial,
            'issue_place_id' => $request->issue_place,
            'birth_place_id' => $request->birth_place,
            'birth_date' => Jalalian::fromFormat('Y/m/d', $request->birth_date)->toCarbon(),
            'status' => 'pending'
        ]);
    }

    // ذخیره مدارک
    private function storeDocuments($request, $personalInfo)
    {
        // عکس پرسنلی
        $photoPath = $this->storeAndResizeImage(
            $request->file('photo'),
            'photos',
            300,
            400
        );
        $personalInfo->documents()->create([
            'type' => 'photo',
            'path' => $photoPath
        ]);

        // کارت ملی
        $nationalCardPath = $this->storeImage(
            $request->file('nationalCard'),
            'national_cards'
        );
        $personalInfo->documents()->create([
            'type' => 'national_card',
            'path' => $nationalCardPath
        ]);

        // صفحات شناسنامه
        if ($request->hasFile('idCard')) {
            foreach ($request->file('idCard') as $file) {
                $path = $this->storeImage($file, 'id_cards');
                $personalInfo->documents()->create([
                    'type' => 'id_card',
                    'path' => $path
                ]);
            }
        }

        // مدارک تحصیلی
        if ($request->hasFile('educationDocs')) {
            foreach ($request->file('educationDocs') as $file) {
                $path = $this->storeImage($file, 'education_docs');
                $personalInfo->documents()->create([
                    'type' => 'education_doc',
                    'path' => $path
                ]);
            }
        }
    }

    // ذخیره و تغییر سایز تصویر
    private function storeAndResizeImage($file, $folder, $width, $height)
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        $image->cover($width, $height);
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }

    // ذخیره تصویر معمولی
    private function storeImage($file, $folder)
    {
        return $file->store($folder, 'public');
    }

    // نمایش جزئیات یک رکورد
    public function show($id)
    {
        $info = PersonalInfo::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'first_name' => $info->first_name,
                'last_name' => $info->last_name,
                'gender' => $info->gender,
                'father_name' => $info->father_name,
                'national_code' => $info->national_code,
                'education' => $info->education,
                'created_at' => $info->created_at,
                'photo_url' => Storage::url($info->photo_path),
                'national_card_url' => Storage::url($info->national_card_path)
            ]
        ]);
    }

    // دریافت داده‌ها برای DataTables
    public function getData()
    {
        $query = PersonalInfo::query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('full_name', function($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('photo_preview', function($row) {
                return '<img src="'.Storage::url($row->photo_path).'" width="50" height="50" class="rounded-circle">';
            })
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-info view-btn" data-id="'.$row->id.'">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning edit-btn" data-id="'.$row->id.'">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                    <i class="fas fa-trash"></i>
                </button>';
            })
            ->rawColumns(['photo_preview', 'action'])
            ->toJson();
    }

// حذف یک رکورد
    public function destroy($id)
    {
        try {
            $info = PersonalInfo::findOrFail($id);

            // حذف فایل‌ها
            Storage::delete([
                $info->photo_path,
                $info->national_card_path
            ]);

            // حذف فایل‌های شناسنامه و مدارک تحصیلی اگر وجود دارند
            if ($info->id_card_paths) {
                Storage::delete(json_decode($info->id_card_paths, true));
            }

            if ($info->education_doc_paths) {
                Storage::delete(json_decode($info->education_doc_paths, true));
            }

            // حذف رکورد از دیتابیس
            $info->delete();

            return response()->json([
                'success' => true,
                'message' => 'رکورد با موفقیت حذف شد'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در حذف رکورد: ' . $e->getMessage()
            ], 500);
        }
    }
}
