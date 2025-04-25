<?php

namespace App\Http\Controllers\hospital_reviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HospitalReview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class HospitalReviews extends Controller
{
  public function index()
  {
    return view('content.hospital-reviews.index');
  }

  public function review_table_json(Request $request)
  {
    $query = HospitalReview::query();
    if ($request->has('date_range') && $request->date_range) {

      $dates = explode(' to ', $request->date_range);

      if (count($dates) === 2) {
        $start_date = $dates[0];
        $end_date = $dates[1];

        $query->whereBetween('created_at', [$start_date, $end_date]);
      }
    }
    if ($request->has('search') && $request->search != '') {
      $query->where(function ($query) use ($request) {
        $query->orWhereHas('hospital', function ($q) use ($request) {
          $q->where('hospital_name', 'like', '%' . $request->search . '%');
        });
      });
    }
    if ($request->has('status') && $request->status != '') {
      $query->where('status', $request->status);
    }

    $hospitalReviews = $query->orderBy('id', 'desc')->get();

    if ($hospitalReviews->isEmpty()) {
      return response()->json([
        "draw" => $request->draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
      ]);
    }
    foreach ($hospitalReviews as $item) {
      $status = ($item->status == 1)
        ? '<span class="status approved">Approved</span>'
        : '<span class="status pending">Pending</span>';
      $class = match ($item->rating) {
        5 => 'five-star',
        4 => 'four-star',
        3 => 'three-star',
        2 => 'two-star',
        default => 'one-star',
      };
      $stars = str_repeat("<li><i class='menu-icon tf-icons ti ti-star'></i></li>", $item->rating);
      $rating_html = "<div class='rating'><ul class='{$class}'>{$stars}</ul></div>";

      $review = mb_strlen($item->review) > 80 ? mb_substr($item->review, 0, 80) . '...' : $item->review;
      $blockText = $item->status == 1 ? 'Block' : 'Unblock';
      $blockIcon = $item->status == 1 ? 'ti-lock' : 'ti-lock-open';
      $action_html = "
      <div class='d-inline-block'>
          <a href='javascript:;'
            class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow'
            data-bs-toggle='dropdown'
            aria-expanded='false'>
              <i class='ti ti-dots ti-md'></i>
          </a>
          <div class='dropdown-menu dropdown-menu-end m-0'>
              <a href='javascript:;'
                class='dropdown-item'
                onclick='delete_review({$item->id})'>
                  <i class='menu-icon tf-icons ti ti-trash'></i> Delete
              </a>
              <a href='javascript:;'
                class='dropdown-item'
                onclick='block_unblock_review({$item->id})'>
                  <i class='menu-icon tf-icons ti {$blockIcon}'></i> {$blockText}
              </a>
          </div>
      </div>
      ";
      $data[] = [
        $item->id,
        Carbon::parse($item->created_at)->format('M d, Y'),
        $rating_html,
        "<div class='message'>{$review}</div>",
        "<div>{$item->user->email}</div><div>{$item->user->mobile}</div>",
        $item->hospital->hospital_name,
        $status,
        $action_html
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data),
      "recordsFiltered" => count($data),
      "data" => $data,
    ]);
  }

  public function review_destroy($id)
  {
    $hospital_review = HospitalReview::find($id);
    if (!$hospital_review) {
      return response()->json(['success' => false, 'message' => 'Review not found.']);
    }
    try {
      $hospital_review->delete();
      return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to delete Review.']);
    }
  }
  public function block_unblock_review($id)
  {
    $hospital_review = HospitalReview::find($id);

    if (!$hospital_review) {
      return response()->json(['success' => false, 'message' => 'Review not found.']);
    }

    try {
      $hospital_review->status = $hospital_review->status == 1 ? 0 : 1;
      $hospital_review->save();

      $newStatus = $hospital_review->status == 1 ? 'unblocked' : 'blocked';

      return response()->json([
        'success' => true,
        'message' => "Review successfully {$newStatus}.",
        'status' => $hospital_review->status
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update review status.'
      ]);
    }
  }

  public function reviewsExportCsv(Request $request)
  {
    $query = HospitalReview::with('hospital', 'user');

    if ($request->has('date_range') && $request->date_range) {

      $dates = explode(' to ', $request->date_range);

      if (count($dates) === 2) {
        $start_date = $dates[0];
        $end_date = $dates[1];

        $query->whereBetween('created_at', [$start_date, $end_date]);
      }
    }

    $reviews = $query->orderBy('id', 'desc')->get();

    $headers = [
      'Content-type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=reviews.csv',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
    ];

    $columns = ['id', 'Date Reviewed', 'Rating', 'Message', 'User Email/Mobile', 'Hopital Reviewed'];

    $callback = function () use ($reviews, $columns) {
      $file = fopen('php://output', 'w');

      fputcsv($file, $columns);

      foreach ($reviews as $record) {
        fputcsv($file, [
          $record->id,
          Carbon::parse($record->created_at)->format('M d, Y'),
          $record->rating,
          $record->review,
          $record->user->email . "/" . $record->user->mobile,
          $record->hospital->hospital_name,

        ]);
      }
      fclose($file);
    };

    // Return the streamed response with the headers
    return Response::stream($callback, 200, $headers);
  }

  public function sendReview(Request $request)
  {
    // Validate the incoming request
    $request->validate([
      'rating' => 'required|integer|min:1|max:5',
      'review' => 'required|string|max:1000',
      'hospital_id' => 'required|exists:hospitals_profile,hospital_id', // Ensure hospital_id is valid
    ]);

    // Check if the user has already submitted a review for this hospital
    $existingReview = HospitalReview::where('user_id', auth()->id())
      ->where('hospital_id', $request->input('hospital_id'))
      ->first();

    if ($existingReview) {
      return redirect()->back()->with('error', 'You have already submitted a review for this hospital.');
    }

    // Save the review
    $review = new HospitalReview();
    $review->hospital_id = $request->input('hospital_id');
    $review->user_id = auth()->id();
    $review->review = $request->input('review');
    $review->rating = $request->input('rating');
    $review->save();

    // Return a success response
    return redirect()->back()->with('success', 'Your review has been submitted successfully!');
  }
}
