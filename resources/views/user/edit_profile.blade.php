@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
<div class="container-fluid">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">  <a href="{{route('users.myProfile')}}">My Profile</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Edit Profile</li>
                </ul>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('users.basic_info') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-box">
            <h3 class="card-title">Basic Informations</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-img-wrap">
                        <img id="preview-image" class="inline-block" src="{{ !empty($basic->image) ? asset($basic->image) : asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt="Profile Image">
                        <div class="fileupload btn">
                            <span class="btn-text">edit</span>
                            <input id="profile-image-input" name="profile_image" class="upload" type="file" onchange="previewImage()">
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="first_name" class="focus-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control floating" value="{{$basic->user->first_name ?? $user->first_name}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="last_name" class="focus-label">Last Name</label>
                                    <input name="last_name" id="last_name" type="text" class="form-control floating" value="{{$basic->user->last_name ?? $user->last_name}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms ">
                                    <label for="birthday" class="focus-label">Birth Date</label>
                                    <div>
                                        <input type="date" id="birthday" name="birthday" class="form-control floating datetimepicker"  value="{{ !empty($basic) && !is_null($basic->birthday) ? $basic->birthday : '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="gender" class="focus-label">Gender</label>
                                    <select id="gender" name="gender" class="form-control select2" tabindex="-1" aria-hidden="true">
                                        <option>Select Gender</option>
                                        <option value="male" {{ !empty($basic->gender) && $basic->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ !empty($basic->gender) && $basic->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ !empty($basic->gender) && $basic->gender == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-box">
            <h3 class="card-title">Contact Informations</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-block local-forms">
                        <label for="address" class="focus-label">Address</label>
                        <input  id="address" name="address" type="text" class="form-control floating"  value="{{ !empty($basic) && !is_null($basic->address) ? $basic->address : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="county" class="focus-label">County</label>
                        <input id="county" name="county" type="text" class="form-control floating" value="{{ !empty($basic) && !is_null($basic->county) ? $basic->county : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="town" class="focus-label">Town</label>
                        <input id="town" name="town" type="text" class="form-control floating" value="{{ !empty($basic) && !is_null($basic->town) ? $basic->town : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="national_id" class="focus-label">National ID Number</label>
                        <input id="national_id" name="national_id" type="text" class="form-control floating" value="{{ !empty($basic) && !is_null($basic->national_id) ? $basic->national_id : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="phone" class="focus-label">Phone Number</label>
                        <input id="phone" name="phone" type="text" class="form-control floating" value="{{ !empty($basic) && !is_null($basic->phone) ? $basic->phone : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="employee_id_number" class="focus-label">Employee ID Number</label>
                        <input id="employee_id_number" name="employee_id_number" type="text" class="form-control floating" value="{{ !empty($basic) && !is_null($basic->employee_id) ? $basic->employee_id : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-block local-forms">
                        <label for="date_joined" class="focus-label">Date Joined</label>
                        <input id="date_joined" name="date_joined" type="date" class="form-control floating datetimepicker" value="{{ !empty($basic) && !is_null($basic->date_joined) ? $basic->date_joined : '' }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center ">
            <button type="submit" class="btn btn-primary submit-btn mb-4">Save</button>
        </div>
    </form>
    <form method="post" action="{{ route('users.education_info') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-box">
            <div class="education-entries">
                @if($educations->isEmpty())
                    <div class="education-entry">
                        <h3 class="card-title">Education Informations</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="institution" class="focus-label">Institution</label>
                                    <input name="institution[]" id="institution" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->institution) ? $education->institution : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="subject" class="focus-label">Subject</label>
                                    <input name="subject[]" id="subject" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->subject) ? $education->subject : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="starting_date" class="focus-label">Starting Date</label>
                                    <input name="starting_date[]" id="starting_date" type="date" class="form-control floating datetimepicker" value="{{ !empty($education) && !is_null($education->starting_date) ? $education->starting_date : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="complete_date" class="focus-label">Complete Date</label>
                                    <input name="complete_date[]" id="complete_date" type="date" class="form-control floating datetimepicker" value="{{ !empty($education) && !is_null($education->completion_date) ? $education->completion_date : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="degree" class="focus-label">Degree</label>
                                    <input name="degree[]" id="degree" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->degree) ? $education->degree : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="grade" class="focus-label">Grade</label>
                                    <input name="grade[]" id="grade" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->grade) ? $education->grade : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($educations as $key => $education)
                    <div class="education-entry">
                        <h3 class="card-title">Education Informations</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="institution" class="focus-label">Institution</label>
                                    <input name="institution[]" id="institution" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->institution) ? $education->institution : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="subject" class="focus-label">Subject</label>
                                    <input name="subject[]" id="subject" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->subject) ? $education->subject : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="starting_date" class="focus-label">Starting Date</label>
                                    <input name="starting_date[]" id="starting_date" type="date" class="form-control floating datetimepicker" value="{{ !empty($education) && !is_null($education->starting_date) ? $education->starting_date : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="complete_date" class="focus-label">Complete Date</label>
                                    <input name="complete_date[]" id="complete_date" type="date" class="form-control floating datetimepicker" value="{{ !empty($education) && !is_null($education->completion_date) ? $education->completion_date : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="degree" class="focus-label">Degree</label>
                                    <input name="degree[]" id="degree" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->degree) ? $education->degree : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="grade" class="focus-label">Grade</label>
                                    <input name="grade[]" id="grade" type="text" class="form-control floating" value="{{ !empty($education) && !is_null($education->grade) ? $education->grade : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
            @endif
            </div>
            <div class="add-more">
                <a href="#" class="btn btn-primary add-education"><i class="fa fa-plus"></i> Add More Education</a>
            </div>
        </div>
        <div class="text-center ">
            <button type="submit" class="btn btn-primary submit-btn mb-4">Save</button>
        </div>
    </form>
    <form method="post" action="{{ route('users.experience_info') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-box">
            <div class="experience-entries">
                @if($educations->isEmpty())
                    <div class="experience-entry">
                        <h3 class="card-title">Experience Informations</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="company_name" class="focus-label">Company Name</label>
                                    <input type="text" id="company_name" name="company_name[]" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->company_name) ? $experience->company_name : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="location" class="focus-label">Location</label>
                                    <input name="location[]" id="location" type="text" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->location) ? $experience->location : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="job_position" class="focus-label">Job Position</label>
                                    <input name="job_position[]" id="job_position" type="text" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->job_position) ? $experience->job_position : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="period_from" class="focus-label">Period From</label>
                                    <input name="period_from[]" id="period_from" type="date" class="form-control floating datetimepicker" value="{{ !empty($experience) && !is_null($experience->period_from) ? $experience->period_from : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block local-forms">
                                    <label for="period_to" class="focus-label">Period To</label>
                                    <input name="period_to[]" id="period_to" type="date" class="form-control floating datetimepicker" value="{{ !empty($experience) && !is_null($experience->period_to) ? $experience->period_to : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($experiences as $key => $experience)
                        <div class="experience-entry">
                            <h3 class="card-title">Experience Informations</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-block local-forms">
                                        <label for="company_name" class="focus-label">Company Name</label>
                                        <input type="text" id="company_name" name="company_name[]" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->company_name) ? $experience->company_name : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block local-forms">
                                        <label for="location" class="focus-label">Location</label>
                                        <input name="location[]" id="location" type="text" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->location) ? $experience->location : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block local-forms">
                                        <label for="job_position" class="focus-label">Job Position</label>
                                        <input name="job_position[]" id="job_position" type="text" class="form-control floating" value="{{ !empty($experience) && !is_null($experience->job_position) ? $experience->job_position : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block local-forms">
                                        <label for="period_from" class="focus-label">Period From</label>
                                        <input name="period_from[]" id="period_from" type="date" class="form-control floating datetimepicker" value="{{ !empty($experience) && !is_null($experience->period_from) ? $experience->period_from : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block local-forms">
                                        <label for="period_to" class="focus-label">Period To</label>
                                        <input name="period_to[]" id="period_to" type="date" class="form-control floating datetimepicker" value="{{ !empty($experience) && !is_null($experience->period_to) ? $experience->period_to : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="add-more">
                <a  class="btn btn-primary add-experience"><i class="fa fa-plus"></i> Add More Experience</a>
            </div>
        </div>
        <div class="text-center ">
            <button type="submit" class="btn btn-primary submit-btn mb-4">Save</button>
        </div>
    </form>
</div>
<div class="notification-box">
    <div class="msg-sidebar notifications msg-noti">
        <div class="topnav-dropdown-header">
            <span>Messages</span>
        </div>
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 1242px;"><div class="drop-scroll msg-list-scroll" id="msg_list" style="overflow: hidden; width: auto; height: 1242px;">
                <ul class="list-box">
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Richard Miles </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item new-message">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">John Doe</span>
                                    <span class="message-time">1 Aug</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Tarah Shropshire </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Mike Litorus</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Catherine Manseau </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">D</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Domenic Houston </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">B</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Buster Wigton </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Rolland Webber </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Claire Mapes </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Melita Faucher</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Jeffery Lalor</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">L</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Loren Gatlin</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Tarah Shropshire</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div><div class="slimScrollBar" style="background: rgb(135, 135, 135); width: 4px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 0px; z-index: 99; right: 1px; height: 776px;"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
        <div class="topnav-dropdown-footer">
            <a href="chat.html">See all messages</a>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-experience').click(function(e) {
            e.preventDefault();
            var experienceEntry = $('.experience-entry:first').clone();
            experienceEntry.find('input').val(''); // Clear input values
            $('.experience-entries').append(experienceEntry);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.add-education').click(function(e) {
            e.preventDefault();
            var educationEntry = $('.education-entry:first').clone();
            educationEntry.find('input').val(''); // Clear input values
            $('.education-entries').append(educationEntry);
        });
    });
</script>
<script>
    function previewImage() {
        var fileInput = document.getElementById('profile-image-input');
        var previewImg = document.getElementById('preview-image');

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                previewImg.src = e.target.result;
            }

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>

@endsection

