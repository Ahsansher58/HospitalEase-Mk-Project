<!DOCTYPE html>
<html>

<head>
    <title>Hospital Collaboration Invitation</title>
</head>

<body>
    <p>Dear Dr. {{ $name }},</p>

    <p>We are pleased to invite you to collaborate with our hospital. Your expertise and dedication to healthcare are highly respected, and we believe you would be a valuable addition to our medical team.</p>

    {{-- <p>As part of this invitation, we have created a temporary account for you on our system.</p>

    <p><strong>Login Credentials:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You may log in using the credentials above and update your password at your convenience. Please review the hospital profile, facilities, and collaboration terms once logged in.</p> --}}

    <p>To accept or decline this invitation, please click on one of the following:</p>

    <p>
        <a href="{{ route('hospital.link-registered-doctor-confirmation', ['is_approved' => 1 , 'doctor_id' =>$doctor_id , 'hospital_id' =>  $hospital_id]) }}" style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Approve Invitation</a>
        &nbsp;&nbsp;
        <a href="{{ route('hospital.link-registered-doctor-confirmation', ['is_approved' => 0, 'doctor_id' =>$doctor_id , 'hospital_id' =>  $hospital_id]) }}" style="background-color: #dc3545; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Decline Invitation</a>
    </p>


    <p>If you have any questions or require further details, feel free to contact us.</p>

    <p>We look forward to the possibility of working together.</p>

    <p>Best Regards,<br>
    {{ $hospital_name }}<br>
</body>

</html>
