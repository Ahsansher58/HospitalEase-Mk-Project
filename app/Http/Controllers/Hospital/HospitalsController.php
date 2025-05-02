<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Advertisements;
use App\Models\FavoriteHospital;
use App\Models\HospitalAppointment;
use App\Models\HospitalReview;
use App\Models\HospitalsProfile;
use App\Models\DoctorAwardAchievement;
use App\Models\DoctorEducationalQualification;
use App\Models\DoctorAppointment;
use App\Models\MainCategory;
use App\Models\HospitalDoctor;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\LinkRegisteredDoctorConfirmationMail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HospitalsController extends Controller
{

  public function index(Request $request, $medical_system = '')
  {

    $query = HospitalsProfile::with('hospitalSetting')
      ->whereHas('hospital', function ($q) {
        $q->where('verify', 1);
      });

    // Apply filter if a medical system is selected
    if ($medical_system != '') {
      $medical_system = strtolower($medical_system);

      $query->where(function ($q) use ($medical_system) {
        $q->WhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(medical_system, "$[*]"))) LIKE ?', ['%' . $medical_system . '%']);
      });
    }

    // Apply city and location search filters
    if (isset($request->search_by_city) || isset($request->search_by_location)) {
      $query->where(function ($query) use ($request) {
        // Search by city
        if (isset($request->search_by_city)) {
          $search_by_city = strtolower($request->search_by_city);
          $query->whereRaw('LOWER(city) LIKE ?', ['%' . $search_by_city . '%']);
        }

        // Search by location or locality
        if (isset($request->search_by_location)) {
          $search_by_location = strtolower($request->search_by_location);
          $query->where(function ($query) use ($search_by_location) {
            $query->whereRaw('LOWER(location) LIKE ?', ['%' . $search_by_location . '%'])
              ->orWhereRaw('LOWER(locality) LIKE ?', ['%' . $search_by_location . '%']);
          });
        }
      });
    }

    if ($request->has('search_by_rating')) {
      $rating = $request->search_by_rating;

      $query->with(['hospitalReview' => function ($query) {
        $query->select('hospital_id', DB::raw('AVG(rating) as avg_rating'))
          ->where('status', 1)
          ->groupBy('hospital_id');
      }]);

      $query->leftJoinSub(
        DB::table('hospital_reviews')
          ->select('hospital_id', DB::raw('AVG(rating) as avg_rating'))
          ->where('status', 1)
          ->groupBy('hospital_id'),
        'ratings',
        'hospitals_profile.hospital_id',
        '=',
        'ratings.hospital_id'
      );

      if ($rating === 'low_to_high') {
        $query->orderBy('ratings.avg_rating', 'asc');
      } elseif ($rating === 'high_to_low') {
        $query->orderBy('ratings.avg_rating', 'desc');
      } elseif (is_numeric($rating)) {
        $query->where(function ($query) use ($rating) {
          $query->where('ratings.avg_rating', '>=', (int)$rating)
            ->orWhereNull('ratings.avg_rating');
        });
      }
    }



    // Apply facilities filter if selected and not 'all'
    if ($request->has('search_by_facilities') && !empty($request->search_by_facilities)) {
      $selectedFacilities = is_array($request->search_by_facilities) ? $request->search_by_facilities : explode(',', $request->search_by_facilities);

      if (!in_array('all', $selectedFacilities)) {
        $query->where(function ($query) use ($selectedFacilities) {
          foreach ($selectedFacilities as $facility) {
            $facility = trim($facility);
            $query->orWhereRaw('JSON_CONTAINS(facilities, ?)', [json_encode($facility)]);
          }
        });
      }
    }

    // Apply specialization filter if selected and not 'all'
    if ($request->has('search_by_specialization') && !empty($request->search_by_specialization)) {
      $selectedSpecialization = is_array($request->search_by_specialization) ? $request->search_by_specialization : explode(',', $request->search_by_specialization);

      if (!in_array('all', $selectedSpecialization)) {
        $query->where(function ($query) use ($selectedSpecialization) {
          foreach ($selectedSpecialization as $specialization) {
            $specialization = trim($specialization);
            $query->orWhereRaw('JSON_CONTAINS(specialization, ?)', [json_encode($specialization)]);
          }
        });
      }
    }




    // Fetch paginated hospitals
    $hospitals = $query->paginate(5);

    // Fetch total count of hospitals after applying the same filters
    $totalCount = $query->clone()->count();

    $all_facilities = SubCategory::where('id', 9)->pluck('value')->first();
    $all_specialization = SubCategory::where('id', 3)->pluck('value')->first();

    foreach ($hospitals as $hospital) {
      $averageRating = HospitalReview::where('hospital_id', $hospital->hospital_id)
        ->where('status', 1)
        ->avg('rating');
      $averageRating = round($averageRating, 1);

      $reviewsCount = HospitalReview::where('hospital_id', $hospital->hospital_id)
        ->where('status', 1)
        ->count();

      $hospital->averageRating = $averageRating;
      $hospital->reviewsCount = $reviewsCount;
    }

    // Fetch main categories, ordered by sort order
    $main_cats = MainCategory::orderBy('sort_order', 'asc')->get();

    // Fetch and group subcategories by main category ID
    $sub_cats = SubCategory::orderBy('sort_order', 'asc')->get()->groupBy('main_category_id');

    // Check if the user is authenticated
    if (!Auth::check()) {
      $favoriteHospitals = [];
      return view('frontend.content.hospitals.hospital-list', compact(
        'hospitals',
        'totalCount',
        'favoriteHospitals',
        'all_facilities',
        'all_specialization',
        'main_cats',
        'sub_cats'
      ));
    }

    // Get favorite hospitals for authenticated user
    $user = Auth::user();
    $favoriteHospitals = $user->favoriteHospitals->pluck('hospital_id')->toArray();

    return view('frontend.content.hospitals.hospital-list', compact(
      'user',
      'hospitals',
      'totalCount',
      'favoriteHospitals',
      'all_facilities',
      'all_specialization',
      'main_cats',
      'sub_cats'
    ));
  }



  public function show($hospital_slug)
  {
    $favoriteHospitals = [];
    $hospital = HospitalsProfile::with([
      'hospitalSetting',
      'hospitalReview' => function ($query) {
        $query->where('status', 1)->orderBy('id', 'desc');
      }
    ])->where('hospital_slug', $hospital_slug)->first();

    $averageRating = HospitalReview::where('hospital_id', $hospital->hospital_id)->where('status', 1)->avg('rating');
    $averageRating = number_format($averageRating, 1);
    $reviewsCount = HospitalReview::where('hospital_id', $hospital->hospital_id)->where('status', 1)->count();

    // Add these values to the hospital object
    $hospital->averageRating = $averageRating;
    $hospital->reviewsCount = $reviewsCount;

    $all_facilities = SubCategory::where('id', 9)->pluck('value')->first();
    $all_sub_categories = SubCategory::where('status', 1)
      ->select('id', 'sub_category_name')
      ->get();

    /*Get ads */
    $currentDate = Carbon::now()->toDateString();
    $advertisement = Advertisements::where('placement', 1)->where('status', 1)->whereDate('start_date', '<=', $currentDate)
      ->whereDate('end_date', '>=', $currentDate)->inRandomOrder()->first();

    if (!$hospital) {
      return redirect()->route('hospital.all')->with('error', 'Hospital not found');
    }
    if (!Auth::check()) {
      return view('frontend.content.hospitals.hospital-single', compact('hospital', 'all_facilities', 'favoriteHospitals', 'all_sub_categories', 'advertisement'));
    }
    $user = Auth::user();
    $favoriteHospitals = $user->favoriteHospitals->pluck('hospital_id')->toArray();
    return view('frontend.content.hospitals.hospital-single', compact('user', 'hospital', 'all_facilities', 'favoriteHospitals', 'favoriteHospitals', 'all_facilities', 'all_sub_categories', 'advertisement'));
  }


  public function hospitalProfile()
  {
    // Redirect if the user is not authenticated
    if (!Auth::check()) {
      return redirect('/');
    }

    // Get the authenticated user
    $user = Auth::user();

    // Get the current user's hospital ID
    $currentUserHospitalId = $user->id;

    // Fetch and process hospital profile data
    $hospitalProfileData = HospitalsProfile::where('hospital_id', $currentUserHospitalId)
      ->get()
      ->map(function ($profile) {
        $profileArray = $profile->toArray();
        $otherData = $profileArray['other_data'] ?? null;

        // Decode other_data if it's a string
        if (is_string($otherData)) {
          $otherData = json_decode($otherData, true) ?? []; // Decode JSON, default to empty array
        } elseif (!is_array($otherData)) {
          $otherData = []; // Ensure it's always an array
        }

        // Remove the other_data field from the main array
        unset($profileArray['other_data']);

        // Merge the main profile data with decoded other_data
        return array_merge($profileArray, $otherData);
      })
      ->toArray();

    // Ensure hospitalProfileData is an array even if no profiles are found
    if (empty($hospitalProfileData)) {
      $hospitalProfileData = [];
    }

    // Fetch main categories, ordered by sort order
    $main_cats = MainCategory::orderBy('sort_order', 'asc')->get();

    // Fetch and group subcategories by main category ID
    $main_sub_cats = SubCategory::orderBy('sort_order', 'asc')->get()->groupBy('main_category_id');

    // Return the hospital profile view with all necessary data
    return view('frontend.content.hospitals.hospital-profile', compact('user', 'main_cats', 'main_sub_cats', 'hospitalProfileData'));
  }

  public function hospitalBookAppointment(Request $request)
  {
    // Validate the incoming request data
    $validated = $request->validate([
      'patient_name' => 'required|string|max:255',
      'phone_number' => 'required|numeric|digits_between:10,15',
      'email' => 'required|email|max:255',
      'appointment_date' => 'required|date|after_or_equal:today',
      'hospital_id' => 'required|exists:hospitals_profile,hospital_id',
    ]);

    // Create a new appointment entry
    $appointment = HospitalAppointment::create([
      'patient_name' => $validated['patient_name'],
      'phone_number' => $validated['phone_number'],
      'email' => $validated['email'],
      'appointment_date' => $validated['appointment_date'],
      'hospital_id' => $validated['hospital_id'],
    ]);

    // Redirect or respond with a success message
    if ($request->has('hospital_slug')) {
      return redirect()->route('hospital.show', $request->hospital_slug)->with('success', 'Appointment successfully booked!');
    }
    return redirect()->route('hospital.all')->with('success', 'Appointment successfully booked!');
  }

  public function hospitalAppointment()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();

    $setting = Setting::where('option', 'appointment_settings')
      ->where('hospital_id', Auth::id())
      ->first();
    $appointmentSettings = $setting ? json_decode($setting->value, true) : null;
    return view('frontend.content.hospitals.hospital-appointment', compact('user', 'appointmentSettings'));
  }

  public function hospitalAppointmentSetting()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();

    $setting = Setting::where('option', 'appointment_settings')
      ->where('hospital_id', Auth::id())
      ->first();
    $appointmentSettings = $setting ? json_decode($setting->value, true) : null;
    return view('frontend.content.hospitals.hospital-appointment-setting', compact('user', 'appointmentSettings'));
  }
  public function hospitalAppointmentSettingUpdate(Request $request)
  {
    $validated = $request->validate([
      'appointment_type' => 'required|in:whats_app,website_link,default_from',
      'country_code' => 'required_if:appointment_type,whats_app|string|max:5',
      'whats_number' => 'required_if:appointment_type,whats_app|nullable|regex:/^\d{6,14}$/',
      'whats_message' => 'required_if:appointment_type,whats_app|max:255|nullable',
      'website_address' => 'required_if:appointment_type,website_link|nullable|url',
    ]);

    // Create the JSON value
    $appointmentSettings = json_encode($validated);

    // Check if the setting already exists
    $setting = Setting::updateOrCreate(
      [
        'option' => 'appointment_settings',
        'hospital_id' => Auth::id()
      ],
      [
        'value' => $appointmentSettings
      ]
    );

    return redirect()->back()->with('success', 'Appointment settings updated successfully!');
  }
  public function getHospitalAppointment(Request $request, $hospital_id)
  {
    $query = HospitalAppointment::where('hospital_id', $hospital_id);

    // Search functionality
    if ($search = $request->input('search')['value']) {
      $query->where('patient_name', 'LIKE', "%{$search}%")
        ->orWhere('phone_number', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('appointment_date', 'LIKE', "%{$search}%");
    }

    // Pagination and ordering
    $order = $request->input('order.0.column'); // Column index
    $dir = $request->input('order.0.dir'); // asc or desc
    $columns = ['id', 'patient_name', 'phone_number', 'email', 'appointment_date'];
    if ($order == 0)
      $query->orderBy('id', 'desc');
    else
      $query->orderBy($columns[$order], $dir);
    $perPage = $request->input('length');
    $offset = $request->input('start');
    $appointments = $query->skip($offset)->take($perPage)->get();

    // Total records
    $totalData = HospitalAppointment::where('hospital_id', $hospital_id)->count();
    $totalFiltered = $query->count();

    // Format data for DataTable
    $data = [];
    foreach ($appointments as $appointment) {
      $data[] = [
        'checkbox' => "<input type='checkbox' class='appointment-checkbox' data-id='{$appointment->id}' />",
        'patient_name' => $appointment->patient_name,
        'phone_number' => $appointment->phone_number,
        'email' => $appointment->email,
        'appointment_date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y'),
        'actions' => "<button class='btn btn-icon p-0 border-0 mx-2' onclick='deleteAppointment({$appointment->id})'>
                    <img src='" . asset('assets/frontend/images/icons/delete.svg') . "' alt='Delete'/>
                </button>
            ",
      ];
    }
    // Response structure
    $json_data = [
      "draw" => intval($request->input('draw')),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    ];

    return response()->json($json_data);
  }
  public function destroyAppointments($id)
  {
    try {
      $appointment = HospitalAppointment::findOrFail($id);
      $appointment->delete();

      return response()->json(['message' => 'Appointment deleted successfully.'], 200);
    } catch (Exception $e) {
      return response()->json(['message' => 'Failed to delete appointment.'], 500);
    }
  }

  public function exportCsv()
  {
    $fileName = 'appointment_records.csv';

    $headers = [
      "Content-type"        => "text/csv",
      "Content-Disposition" => "attachment; filename=$fileName",
      "Pragma"              => "no-cache",
      "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
      "Expires"             => "0"
    ];

    $columns = ['id', 'patient_name', 'phone_number', 'email', 'appointment_date'];

    $callback = function () use ($columns) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      $appointments = HospitalAppointment::where('hospital_id', Auth::id())->get();

      foreach ($appointments as $record) {
        fputcsv($file, [
          $record->id,
          $record->patient_name,
          $record->phone_number,
          $record->email,
          Carbon::parse($record->appointment_date)->format('M d, Y'),
        ]);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function hospitalReview()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.hospitals.hospital-review', compact('user'));
  }

  public function getHospitalReviews(Request $request, $hospital_id)
  {
    $query = HospitalReview::with([
      'user' => function ($query) {
        $query->select('id', 'name');
      }
    ])->where('hospital_id', $hospital_id);

    // Search functionality
    if ($search = $request->input('search')['value']) {
      $query->where('review', 'LIKE', "%{$search}%")
        ->orWhereHas('user', function ($q) use ($search) {
          $q->where('name', 'LIKE', "%{$search}%");
        });
    }

    // Pagination and ordering
    $order = $request->input('order.0.column'); // Column index
    $dir = $request->input('order.0.dir'); // asc or desc
    $columns = ['id', 'user.name', 'rating', 'review', 'created_at'];
    if ($order == 0)
      $query->orderBy('id', 'desc');
    else
      $query->orderBy($columns[$order], $dir);

    $perPage = $request->input('length');
    $offset = $request->input('start');
    $reviews = $query->skip($offset)->take($perPage)->get();

    // Total records
    $totalData = HospitalReview::where('hospital_id', Auth::id())->count();
    $totalFiltered = $query->count();

    // Format data for DataTable
    $data = [];
    foreach ($reviews as $review) {
      $username_rating = $review->user->name . '<div class="rating">';
      for ($i = 1; $i <= 5; $i++) {
        $username_rating .= '<i class="fa fa-star ' . ($i <= $review->rating ? 'active' : '') . '"></i>';
      }
      $username_rating .= ' ' . number_format($review->rating, 1) . '</div>';

      $data[] = [
        'id' => $review->id,
        'username_rating' => $username_rating,
        'review' => $review->review,
        'reviewed_on' => $review->created_at->format('M d, Y'),
        'status' => $review->status == 1 ? 'Open' : 'Blocked',
        'actions' => "<button class='btn btn-icon p-0 border-0 view-review' data-id='{$review->id}'>
                <img src='" . asset('assets/frontend/images/icons/eye.png') . "' alt='View'/>
            </button>
        ",
      ];
    }

    // Response structure
    $json_data = [
      "draw" => intval($request->input('draw')),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    ];

    return response()->json($json_data);
  }

  public function viewReview($id)
  {
    $review = HospitalReview::with('user')
      ->where('id', $id)
      ->firstOrFail();

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $review->id,
        'user_name' => $review->user->name,
        'rating' => $review->rating,
        'review' => $review->review,
        'reply' => $review->reply,
        'reviewed_on' => $review->created_at->format('M d, Y'),
      ]
    ]);
  }
  public function replyToReview(Request $request, $id)
  {
    $request->validate([
      'reply' => 'required|string|max:500',
    ]);

    $review = HospitalReview::findOrFail($id);

    if ($review->reply) {
      return response()->json([
        'success' => false,
        'message' => 'This review has already been replied to.',
      ], 400);
    }

    $review->reply = $request->input('reply');
    $review->save();

    return response()->json([
      'success' => true,
      'message' => 'Reply submitted successfully.',
      'data' => $review,
    ]);
  }



  public function reviewExportCsv()
  {
    $fileName = 'reviews.csv';

    $headers = [
      "Content-type"        => "text/csv",
      "Content-Disposition" => "attachment; filename=$fileName",
      "Pragma"              => "no-cache",
      "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
      "Expires"             => "0"
    ];

    $columns = ['id', 'User name', 'Rating', 'Comments', 'reviewed_on'];

    $callback = function () use ($columns) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      $reviews = $reviews = HospitalReview::with([
        'user' => function ($query) {
          $query->select('id', 'name');
        }
      ])
        ->where('hospital_id', Auth::id())->get();

      foreach ($reviews as $review) {
        fputcsv($file, [
          $review->id,
          $review->user->name,
          $review->rating,
          $review->review,
          Carbon::parse($review->created_at)->format('M d, Y'),
        ]);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function hospitalDoctor()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user               = Auth::user();
    $hospital           = HospitalsProfile::where('hospital_id' ,$user->id)->first();
    $registeredDoctors  = Doctor::select('doctors.*' , 'hospital_doctors.is_approved')
    ->leftJoin('hospital_doctors' , 'hospital_doctors.doctor_id' , 'doctors.id')
    ->where('hospital_doctors.hospital_id' , $hospital->id)
    ->where('doctors.hospital_id' , $hospital->id)
    ->get();
        
    return view('frontend.content.hospitals.hospital-doctor', compact('user' , 'registeredDoctors'));
  }
  public function addHospitalDoctor()
  {
    return view('frontend.content.hospitals.hospital-doctor');
  }
  public function uploadImageCase8(Request $request)
  {
    // Validate the file with a custom error message for size
    $request->validate([
      'file' => 'required|image|max:5125', // Validate image file
    ], [
      'file.max' => 'The image size must not exceed 5MB.',
    ]);

    // If validation passes, store the image
    if ($request->file('file')->isValid()) {
      $hospital_id = Auth::id();
      dd($hospital_id);
      $filePath = $request->file('file')->store('uploads/case8/' . $hospital_id, 'public');

      return response()->json(['success' => true, 'filePath' => asset('storage/' . $filePath)]);
    }

    // If validation fails, return failure response
    return response()->json(['success' => false]);
  }

  public function deleteImageCase8(Request $request)
  {
    $request->validate([
      'imagePath' => 'required|string',
    ]);

    $imagePath = $request->input('imagePath'); // Get the image path

    // Check if the file exists and delete it
    if (Storage::exists($imagePath)) {
      Storage::delete($imagePath);
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => true]);
    }

    // Check if the path requires 'public/' prefix
    $publicPath = 'public/' . $imagePath;
    if (Storage::exists($publicPath)) {
      Storage::delete($publicPath);
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'File not found']);
  }

  public function searchDoctors(Request $request)
  {
    if ($request->ajax()) {
        $term     = $request->input('search_text');

        $query = Doctor::query();

        if (!empty($term)) {
          $query->where('email', 'LIKE', '%' . $term . '%');
        }

        $results = $query->limit(10)->get();

        return response()->json([
            'result' => $results->map(function ($doctor) {
              return [
                  'id'    => $doctor->id,
                  'name'  => $doctor->name,
                  'email' => $doctor->email,
              ];
            }),
        ]);
    }

    // Optional fallback in case request is not AJAX
    return response()->json(['error' => 'Invalid request'], 400);
  }

  public function linkRegisteredDoctor(Request $request)
  {

    $data = [
      'doctor_id'   => $request->doctor_id,
      'hospital_id' => $request->hospital_id,
      'is_approved' => 0
    ];

    HospitalDoctor::create($data);

    $doctor   = Doctor::find($request->doctor_id);
    $hospital = HospitalsProfile::where('hospital_id',$request->hospital_id)->first();

    // Send password to the user's email
    Mail::to($doctor->email)->send(new LinkRegisteredDoctorConfirmationMail($doctor,$hospital));

    return redirect()->back()->with('success', 'Email successfully send to Doctor.');

  }

  public function doctorProfile(Request $request, $id)
  {
    $doctor = Doctor::select(
      'doctors.*',
      'doctor_award_achievements.award_name',
      'doctor_award_achievements.awarded_year',
      'doctor_educational_qualifications.college_name',
      'doctor_educational_qualifications.year_studied',
      'doctor_educational_qualifications.degree',
      'doctor_educational_qualifications.qualification_certificate',
      'doctor_educational_qualifications.show_certificate_in_public',
      )
    ->leftJoin('doctor_award_achievements' , 'doctor_award_achievements.doctor_id' , 'doctors.id')
    ->leftJoin('doctor_educational_qualifications','doctor_educational_qualifications.doctor_id' ,'doctors.id')
    ->where('doctors.id' , $id)
    ->first();

    $user                            = Auth::user();
    $doctorAwardsAndAchievements     = DoctorAwardAchievement::where('doctor_id' , $id)->get();
    $doctorEducationalQualifications = DoctorEducationalQualification::where('doctor_id' , $id)->get();
      
        
    $doctorAppointments = DoctorAppointment::select(
      'doctor_appointments.*' ,
      'doctors.user_id' ,
      'user_profile.address',
      'hospitals_profile.hospital_name',
    )
    ->leftJoin('hospitals_profile','hospitals_profile.id' , 'doctor_appointments.hospital_id')
    ->leftJoin('users' , 'users.id' , 'hospitals_profile.hospital_id')
    ->leftJoin('user_profile' , 'users.id' , 'user_profile.user_id')
    ->leftJoin('doctors' , 'doctors.id' , 'doctor_appointments.doctor_id')
    ->where('doctors.id' , $id)
    ->orderBy('doctor_appointments.id', 'asc')
    ->get();  
        
    return view('frontend.content.hospitals.doctor-profile', compact(
      'doctor',
      'user',
      'doctorAwardsAndAchievements',
      'doctorEducationalQualifications',
      'doctorAppointments',
    ));
  }

  public function hospitalProfileUpdate(Request $request)
  {
    $rules = [];

    // Validate required fields if 'required_casees' is provided
    if ($request->has('required_casees') && is_array($request->required_casees)) {
      foreach ($request->required_casees as $field) {
        if (!empty($field)) {
          $rules[$field] = 'required';
        }
      }
    }

    // Perform validation
    $validatedData = $request->validate($rules);

    $hospital_id = auth()->id();

    // Handle image removal
    if ($request->has('removed_images') && is_array($request->removed_images)) {
      foreach ($request->removed_images as $imagePath) {
        $filePath = public_path('storage/' . $imagePath);
        if (file_exists($filePath)) {
          unlink($filePath); // Delete the file from storage
        }
      }
    }

    // Collect other data and remove unnecessary fields
    $other_data = $request->except([
      '_token',
      'removed_images',
      'mandatory_hospital_name',
      'mandatory_hospital_image',
      'mandatory_description',
      'mandatory_specialization',
      'mandatory_medical_system',
      'mandatory_phone',
      'mandatory_emergency_contact',
      'mandatory_email',
      'mandatory_website',
      'mandatory_location',
      'mandatory_facilities',
      'required_casees',
      'mandatory_hospital_imagesReq',
      'mandatory_hospital_images'
    ]);

    // Convert checkbox fields to JSON
    $specialization = $request->has('mandatory_specialization') && is_array($request->mandatory_specialization)
      ? json_encode($request->mandatory_specialization)
      : null;

    $medical_system = $request->has('mandatory_medical_system') && is_array($request->mandatory_medical_system)
      ? json_encode($request->mandatory_medical_system)
      : null;

    $facilities = $request->has('mandatory_facilities') && is_array($request->mandatory_facilities)
      ? json_encode($request->mandatory_facilities)
      : null;

    $hospital_images = $request->has('mandatory_hospital_images') && is_array($request->mandatory_hospital_images)
      ? json_encode($request->mandatory_hospital_images)
      : null;
    // Generate the hospital slug from the hospital name
    $hospital_slug = Str::slug($request->mandatory_hospital_name);
    $slugExists = HospitalsProfile::where('hospital_slug', $hospital_slug)->exists();
    if ($slugExists) {
      $hospital_slug .= '-' . Str::random(5);
    }

    // Prepare data for update or create
    $data = [
      'hospital_id' => $hospital_id,
      'hospital_name' => $request->mandatory_hospital_name,
      'description' => $request->mandatory_description,
      'specialization' => $specialization,
      'medical_system' => $medical_system,
      'phone' => $request->mandatory_phone,
      'emergency_contact' => $request->mandatory_emergency_contact,
      'email' => $request->mandatory_email,
      'website' => $request->mandatory_website,
      'location' => $request->mandatory_location,
      'facilities' => $facilities,
      'other_data' => json_encode($other_data),
      'hospital_slug' => $hospital_slug,
      'hospital_images' => $hospital_images,
    ];

    // Update or create the hospital profile
    HospitalsProfile::updateOrCreate(
      ['hospital_id' => $hospital_id],
      $data
    );

    return redirect()->back()->with('success', 'Hospital profile successfully updated.');
  }

  public function setFavorite(Request $request)
  {
    // Redirect to login if the user is not authenticated
    if (!auth()->check()) {
      return response()->json(['status' => 'notlogin', 'message' => 'please login']);
    }
    if (auth()->user()->type != 3) {
      return response()->json(['status' => 'notlogin', 'message' => 'please login']);
    }

    // Validate the incoming request to ensure hospital_id is provided
    $hospitalId = $request->input('hospitalId');

    // Check if the hospital exists in the hospitals_profile table
    $hospitalExists = HospitalsProfile::where('hospital_id', $hospitalId)->exists();
    if (!$hospitalExists) {
      return response()->json(['status' => 'error', 'message' => 'Hospital does not exist'], 400);
    }

    $userId = auth()->id(); // Get the current user's ID

    // Check if the hospital is already favorited by the user
    $favorite = FavoriteHospital::where('user_id', $userId)->where('hospital_id', $hospitalId)->first();

    if ($favorite) {
      // If already favorited, remove it
      $favorite->delete();
      return response()->json(['status' => 'removed', 'message' => 'Hospital removed from favorites']);
    } else {
      // If not favorited, add to the favorites
      try {
        FavoriteHospital::create([
          'hospital_id' => $hospitalId,
          'user_id' => $userId
        ]);
        return response()->json(['status' => 'added', 'message' => 'Hospital added to favorites']);
      } catch (\Illuminate\Database\QueryException $e) {
        // Check if the error is a foreign key violation
        if ($e->getCode() == '23000') {
          return response()->json(['status' => 'error', 'message' => 'Foreign key constraint violation, hospital does not exist.'], 400);
        }
        // Handle other errors
        return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred'], 500);
      }
    }
  }

  public function hospitalSetting()
  {
    $user = Auth::user();
    $hospitalId = $user->id;
    $hospital = HospitalsProfile::where('hospital_id', $hospitalId)->first();
    return view('frontend.content.hospitals.hospital-setting', compact('user', 'hospital'));
  }

  public function linkRegisteredDoctorConfirmation($is_approved , $doctor_id, $hospital_id)
  {
    $hospitalDoctor  = HospitalDoctor::where('doctor_id' , $doctor_id)->first();
    $hospitalDoctor->update(['is_approved' => $is_approved , 'hospital_id' => $hospital_id]);


    $doctor = Doctor::where('id' , $doctor_id)->first();
    $doctor->update(['hospital_id' => $hospital_id]);

    return redirect()->route('home')->with('success', 'Confirmation successfully updated.');;

  }

  /*update locality */
  public function updateLocality(Request $request)
  {
    // Validate the incoming data
    $request->validate([
      'country'  => 'required|string',
      'state'    => 'required|string',
      'city'     => 'required|string',
      'locality' => 'required|string',
    ]);

    // Get the current user's ID
    $hospitalId = Auth::id();

    // Find the hospital profile by the current user's ID
    $hospitalProfile = HospitalsProfile::where('hospital_id', $hospitalId)->first();

    if ($hospitalProfile) {
      // Update the fields
      $hospitalProfile->country = $request->country;
      $hospitalProfile->state = $request->state;
      $hospitalProfile->city = $request->city;
      $hospitalProfile->locality = $request->locality;
      $hospitalProfile->save();

      return back()->with('success', 'Locality updated successfully!');
    }

    return back()->with('error', 'Hospital profile not found.');
  }
}
