@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <style>
        /* Career Page Custom Styling */
        .career-hero-badge {
            background: rgba(245, 158, 11, 0.2);
            color: #fbb03b;
            border-radius: 30px;
            font-size: 13.5px;
            font-weight: 700;
            border: 1px solid #f59e0b;
            padding: 6px 18px;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.5px;
        }
        .career-card-container {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(6, 56, 26, 0.06);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        .career-card-header {
            background: linear-gradient(135deg, #0c5c2d 0%, #06381a 100%);
            color: #ffffff;
            padding: 24px 32px;
            border-bottom: 3px solid #f59e0b;
        }
        .career-form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .career-label-badge {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #ecfdf5;
            color: #0c5c2d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 1px solid #a7f3d0;
        }
        .career-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .career-input-icon {
            position: absolute;
            left: 16px;
            color: #0c5c2d;
            font-size: 16px;
            pointer-events: none;
            z-index: 2;
            transition: color 0.2s ease;
        }
        .career-form-control {
            width: 100%;
            height: 52px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 18px 12px 46px;
            font-size: 14.5px;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }
        .career-form-control:focus {
            background: #ffffff;
            border-color: #0c5c2d;
            box-shadow: 0 0 0 4px rgba(12, 92, 45, 0.12), 0 2px 8px rgba(0,0,0,0.04);
            outline: none;
        }
        .career-form-control:focus + .career-input-icon,
        .career-input-wrapper:focus-within .career-input-icon {
            color: #f59e0b;
        }
        .career-form-select {
            width: 100%;
            height: 52px;
            background: #f8fafc url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230c5c2d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") no-repeat right 16px center/14px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 42px 12px 46px;
            font-size: 14.5px;
            font-weight: 600;
            color: #0f172a;
            appearance: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .career-form-select:focus {
            background-color: #ffffff;
            border-color: #0c5c2d;
            box-shadow: 0 0 0 4px rgba(12, 92, 45, 0.12);
            outline: none;
        }
        .career-form-textarea {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14.5px;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            resize: vertical;
        }
        .career-form-textarea:focus {
            background: #ffffff;
            border-color: #0c5c2d;
            box-shadow: 0 0 0 4px rgba(12, 92, 45, 0.12);
            outline: none;
        }

        /* Upload Dropzone */
        .career-dropzone {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px dashed #16a34a;
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .career-dropzone:hover {
            border-color: #f59e0b;
            background: #fefce8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
        }
        .career-dropzone-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 12px;
            border: 2px solid #86efac;
            transition: all 0.3s ease;
        }
        .career-dropzone:hover .career-dropzone-icon {
            background: #fef08a;
            color: #b45309;
            border-color: #fde047;
            transform: scale(1.08);
        }
        .career-submit-btn {
            background: linear-gradient(135deg, #0c5c2d 0%, #06381a 100%);
            color: #ffffff !important;
            border: 2px solid #f59e0b;
            border-radius: 50px;
            padding: 14px 40px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 20px rgba(6, 56, 26, 0.3);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }
        .career-submit-btn:hover {
            background: linear-gradient(135deg, #0e7238 0%, #084721 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(245, 158, 11, 0.35);
        }
    </style>

    <!-- Page Hero Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #06381a 0%, #0c5c2d 50%, #053316 100%); padding: 65px 0; border-bottom: 3px solid #f59e0b; position: relative;">
        <div class="container text-center text-white">
            <span class="career-hero-badge mb-3 shadow-sm">
                <i class="fa fa-briefcase me-2 text-warning"></i> Career Opportunities
            </span>
            <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px; letter-spacing: 0.5px;">
                Career & Circulars
            </h1>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0; max-width: 680px; margin: 0 auto; line-height: 1.6;">
                Join the Jamuna Stock Market PLC family and become a proud partner in building a prosperous and sustainable future.
            </p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 14px; border-left: 6px solid #16a34a; background: #f0fdf4; color: #166534; font-weight: 600; padding: 18px 24px;">
                <i class="fa fa-check-circle fa-lg me-2 text-success"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 14px; border-left: 6px solid #dc2626; background: #fef2f2; color: #991b1b; font-weight: 500; padding: 18px 24px;">
                <h6 class="fw-bold mb-2"><i class="fa fa-exclamation-triangle me-2"></i> Please correct the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row g-4">

                <!-- Left Column: Career Application Form -->
                <div class="col-lg-8 col-md-12">
                    <div class="career-card-container">
                        
                        <!-- Header Banner -->
                        <div class="career-card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h4 class="mb-1 fw-bold" style="font-size: 22px; color: #ffffff;">
                                        <i class="fa fa-file-text-o text-warning me-2"></i> Online Job Application Form
                                    </h4>
                                    <p class="mb-0" style="color: #cbd5e1; font-size: 13.5px;">
                                        Please provide accurate personal information and upload your resume (CV).
                                    </p>
                                </div>
                                <span class="badge px-3 py-2" style="background: rgba(245, 158, 11, 0.25); border: 1px solid #f59e0b; color: #fde68a; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                    <i class="fa fa-lock me-1"></i> 100% Secure Application
                                </span>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="p-4 p-md-5">
                            <form action="{{ route('career.apply') }}" method="POST" enctype="multipart/form-data" id="careerApplicationForm">
                                @csrf

                                <!-- 1. Name Row (First Name & Last Name) -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="first_name" class="career-form-label">
                                            <span class="career-label-badge"><i class="fa fa-user"></i></span>
                                            <span>First Name</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="career-input-wrapper">
                                            <i class="fa fa-user career-input-icon"></i>
                                            <input type="text" name="first_name" id="first_name" class="career-form-control" placeholder="e.g. John" value="{{ old('first_name') }}" required autocomplete="given-name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="career-form-label">
                                            <span class="career-label-badge"><i class="fa fa-user-o"></i></span>
                                            <span>Last Name</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="career-input-wrapper">
                                            <i class="fa fa-user-o career-input-icon"></i>
                                            <input type="text" name="last_name" id="last_name" class="career-form-control" placeholder="e.g. Doe" value="{{ old('last_name') }}" required autocomplete="family-name">
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Contact Row (Phone & Email) -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="phone" class="career-form-label">
                                            <span class="career-label-badge"><i class="fa fa-phone"></i></span>
                                            <span>Phone Number</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="career-input-wrapper">
                                            <i class="fa fa-phone career-input-icon"></i>
                                            <input type="tel" name="phone" id="phone" class="career-form-control" placeholder="e.g. 01712345678" value="{{ old('phone') }}" required autocomplete="tel">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="career-form-label">
                                            <span class="career-label-badge"><i class="fa fa-envelope-o"></i></span>
                                            <span>Email Address</span>
                                            <small class="text-muted fw-normal">(Optional)</small>
                                        </label>
                                        <div class="career-input-wrapper">
                                            <i class="fa fa-envelope-o career-input-icon"></i>
                                            <input type="email" name="email" id="email" class="career-form-control" placeholder="e.g. sakib@example.com" value="{{ old('email') }}" autocomplete="email">
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Applying Position Row -->
                                <div class="mb-4">
                                    <label for="position" class="career-form-label">
                                        <span class="career-label-badge"><i class="fa fa-briefcase"></i></span>
                                        <span>Applying Position</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="career-input-wrapper">
                                        <i class="fa fa-briefcase career-input-icon"></i>
                                        <select name="position" id="position" class="career-form-select">
                                            <option value="General Application" {{ old('position') == 'General Application' ? 'selected' : '' }}>General Application (Any Suitable Role)</option>
                                            <option value="Investment Advisor / Officer" {{ old('position') == 'Investment Advisor / Officer' ? 'selected' : '' }}>Investment Advisor / Officer</option>
                                            <option value="Field & Marketing Officer" {{ old('position') == 'Field & Marketing Officer' ? 'selected' : '' }}>Field & Marketing Officer</option>
                                            <option value="Customer Support Executive" {{ old('position') == 'Customer Support Executive' ? 'selected' : '' }}>Customer Support Executive</option>
                                            <option value="Accounts & Finance Officer" {{ old('position') == 'Accounts & Finance Officer' ? 'selected' : '' }}>Accounts & Finance Officer</option>
                                            <option value="IT & Software Developer" {{ old('position') == 'IT & Software Developer' ? 'selected' : '' }}>IT & Software Developer</option>
                                            <option value="Branch Manager" {{ old('position') == 'Branch Manager' ? 'selected' : '' }}>Branch Manager</option>
                                            <option value="Other" {{ old('position') == 'Other' ? 'selected' : '' }}>Other Position</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 4. Location / Address Row -->
                                <div class="mb-4">
                                    <label for="address" class="career-form-label">
                                        <span class="career-label-badge"><i class="fa fa-map-marker"></i></span>
                                        <span>Current Address / Location</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="address" id="address" rows="3" class="career-form-textarea" placeholder="Enter your current division, district, thana, area or full address here..." required>{{ old('address') }}</textarea>
                                </div>

                                <!-- 5. File Upload Row (Resume / CV) -->
                                <div class="mb-4">
                                    <label class="career-form-label">
                                        <span class="career-label-badge"><i class="fa fa-cloud-upload"></i></span>
                                        <span>Upload Resume / CV</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    
                                    <div class="career-dropzone" onclick="document.getElementById('resume').click();" id="careerDropzoneBox">
                                        <div class="career-dropzone-icon">
                                            <i class="fa fa-cloud-upload"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1" style="color: #065f46; font-size: 17px;">
                                            Click here to upload your Resume / CV
                                        </h5>
                                        <p class="text-muted mb-3" style="font-size: 13px;">
                                            Accepted formats: <strong>PDF, DOC, DOCX, JPG, PNG</strong> (Max 10 MB)
                                        </p>
                                        <button type="button" class="btn px-4 py-2 text-white fw-bold shadow-sm" style="background: linear-gradient(135deg, #0c5c2d 0%, #06381a 100%); border: 1.5px solid #f59e0b; border-radius: 25px; font-size: 13.5px;">
                                            <i class="fa fa-folder-open me-2 text-warning"></i> Browse Files
                                        </button>

                                        <!-- Selected File Preview Badge -->
                                        <div id="resumeFileName" class="mt-3 p-2 px-3 rounded-pill bg-white border border-success d-inline-flex align-items-center gap-2 shadow-sm" style="display: none !important;">
                                            <i class="fa fa-check-circle text-success fs-5"></i>
                                            <span id="resumeFileText" class="text-dark fw-bold" style="font-size: 13px;"></span>
                                        </div>
                                    </div>
                                    <input type="file" name="resume" id="resume" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required onchange="handleFileSelected(this)">
                                </div>

                                <!-- 6. Optional Notes / Cover Letter -->
                                <div class="mb-4">
                                    <label for="notes" class="career-form-label">
                                        <span class="career-label-badge"><i class="fa fa-commenting-o"></i></span>
                                        <span>Cover Letter / Additional Notes</span>
                                        <small class="text-muted fw-normal">(Optional)</small>
                                    </label>
                                    <textarea name="notes" id="notes" rows="3" class="career-form-textarea" placeholder="Describe your relevant experience, special skills or any notes you'd like to include...">{{ old('notes') }}</textarea>
                                </div>

                                <!-- 7. Submit Action Button -->
                                <div class="pt-2 text-center text-md-start">
                                    <button type="submit" id="careerSubmitBtn" class="career-submit-btn w-100 w-md-auto">
                                        <i class="fa fa-paper-plane text-warning"></i>
                                        <span>Submit Application</span>
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Why Join Jamuna & Support Info -->
                <div class="col-lg-4 col-md-12">
                    
                    <!-- Why Join Us Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: #06381a; border-bottom: 2px solid #f59e0b; display: inline-block; padding-bottom: 6px;">
                                <i class="fa fa-star text-warning me-2"></i> Why Work With Us?
                            </h5>
                            
                            <ul class="list-unstyled mb-0" style="font-size: 14px; line-height: 1.8;">
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="fa fa-check-circle text-success me-2 mt-1" style="font-size: 16px;"></i>
                                    <span><strong>Modern & Inspiring Workplace</strong> that fosters talent and professional growth.</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="fa fa-check-circle text-success me-2 mt-1" style="font-size: 16px;"></i>
                                    <span><strong>Competitive Compensation & Bonuses</strong> with performance-driven incentives.</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="fa fa-check-circle text-success me-2 mt-1" style="font-size: 16px;"></i>
                                    <span><strong>Rapid Promotion & Career Growth</strong> through structured career pathways.</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="fa fa-check-circle text-success me-2 mt-1" style="font-size: 16px;"></i>
                                    <span><strong>Training & Skill Development</strong> through regular sessions by professional trainers.</span>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="fa fa-check-circle text-success me-2 mt-1" style="font-size: 16px;"></i>
                                    <span><strong>Impactful Contribution</strong> to sustainable agriculture and the national economy.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- HR Contact Help Card -->
                    <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #052611 0%, #031a0c 100%); color: #ffffff; border-radius: 20px; border: 1px solid rgba(245, 158, 11, 0.3);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-warning">
                                <i class="fa fa-headphones me-2"></i> HR Department Support
                            </h5>
                            <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.6;">
                                For any inquiries regarding career opportunities and recruitment, please contact our Human Resources department directly.
                            </p>

                            <hr style="border-color: rgba(255,255,255,0.15);">

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-white bg-opacity-10 p-2 text-warning me-3 text-center" style="width: 40px; height: 40px; line-height: 24px;">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div>
                                    <small class="text-light opacity-75 d-block" style="font-size: 11px;">Hotline</small>
                                    <a href="tel:{{ setting('phone1') }}" class="text-white fw-bold text-decoration-none" style="font-size: 14px;">{{ setting('phone1') }}</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-white bg-opacity-10 p-2 text-warning me-3 text-center" style="width: 40px; height: 40px; line-height: 24px;">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div>
                                    <small class="text-light opacity-75 d-block" style="font-size: 11px;">Email</small>
                                    <a href="mailto:{{ setting('email1') }}" class="text-white fw-bold text-decoration-none" style="font-size: 14px;">{{ setting('email1') }}</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="rounded-circle bg-white bg-opacity-10 p-2 text-warning me-3 text-center" style="width: 40px; height: 40px; line-height: 24px;">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div>
                                    <small class="text-light opacity-75 d-block" style="font-size: 11px;">Address</small>
                                    <span class="text-white" style="font-size: 13px;">{{ setting('address1') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

</div>

<script>
function handleFileSelected(input) {
    const badge = document.getElementById('resumeFileName');
    const text = document.getElementById('resumeFileText');
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        text.innerText = file.name + ' (' + sizeMb + ' MB)';
        badge.style.setProperty('display', 'inline-flex', 'important');
    } else {
        badge.style.setProperty('display', 'none', 'important');
        text.innerText = '';
    }
}
</script>
@endsection
