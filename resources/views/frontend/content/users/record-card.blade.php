<!-- resources/views/components/record-card.blade.php -->
<div class="col-lg-6 col-xl-3 col-sm-6">
    <!--CARD-->
    <div class="card record-card">
        @php
            $fileExtension = pathinfo($record->report_file, PATHINFO_EXTENSION);
        @endphp

        @if (in_array(strtolower($fileExtension), ['pdf']))
            <!-- If the file is a PDF, display PDF icon -->
            <div class="text-center"><img src="{{ asset('assets/frontend/images/icons/pdf-icon.png') }}"
                    style="width: 150px;" class="card-img-top" alt="PDF File" /></div>
        @else
            <!-- If it's an image, display the image -->
            <img src="{{ asset('storage/users/' . Auth::user()->id . '/medical_records/' . $record->report_file) }}"
                class="card-img-top" alt="{{ $record->report_name }}">
        @endif

        <div class="card-body px-0 gray-90 pt-4">
            <h4 class="card-title">{{ $record->report_name }}</h4>
            <p class="card-text font-regular mb-3">
                {{ \Carbon\Carbon::parse($record->report_date)->format('d M Y') }}
            </p>
            <div class="d-flex justify-content-between">
                <a href="javascript:void(0)" class="btn btn-info-text btn-xs rounded-4"
                    onclick="delete_report({{ $record->id }})">
                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                </a>
                <a href="{{ asset('storage/users/' . Auth::user()->id . '/medical_records/' . $record->report_file) }}"
                    class="btn btn-info btn-xs rounded-4" download>
                    <i class="fa fa-download" aria-hidden="true"></i> Download
                </a>
            </div>
        </div>
    </div>
    <!--/CARD-->
</div>
