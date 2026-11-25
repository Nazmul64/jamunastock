<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>৪০৩ অননুমোদিত প্রবেশাধিকার | যমুনা স্টক মার্কেট</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: #ffffff;
            color: #1e293b;
            max-width: 620px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            padding: 40px;
            text-align: center;
            border-top: 6px solid #ef4444;
        }
        .shield-icon {
            width: 90px;
            height: 90px;
            line-height: 90px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            font-size: 42px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 10px rgba(239, 68, 68, 0.1);
        }
        .badge-perm {
            background: #f1f5f9;
            color: #475569;
            border: 1px dashed #cbd5e1;
            padding: 6px 14px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="shield-icon">
            <i class="fa fa-lock"></i>
        </div>
        <h2 class="fw-bold text-danger mb-2">অননুমোদিত প্রবেশাধিকার (403 Forbidden)</h2>
        <h5 class="text-secondary mb-3">দুঃখিত! আপনার অ্যাকাউন্টে এই মডিউল বা অ্যাকশন ব্যবহারের অনুমতি নেই।</h5>
        
        <div class="alert alert-light border p-3 rounded-3 text-start mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="fa fa-user-circle text-primary me-2 fs-5"></i>
                <span>বর্তমান ব্যবহারকারী: <strong>{{ $user->name ?? 'User' }}</strong> (ভূমিকা: <span class="badge bg-secondary">{{ $user->role ?? 'N/A' }}</span>)</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="fa fa-key text-warning me-2 fs-5"></i>
                <span>প্রয়োজনীয় পারমিশন কী: <span class="badge-perm">{{ $requiredPermission ?? 'N/A' }}</span></span>
            </div>
        </div>

        <p class="text-muted small mb-4">
            আপনার যদি এই অপশনটিতে অ্যাক্সেস প্রয়োজন হয়, তবে আপনার প্রতিষ্ঠানের সিস্টেম অ্যাডমিনিস্ট্রেটর বা HR ম্যানেজারের সাথে যোগাযোগ করে আপনার রোল-এ কাঙ্ক্ষিত পারমিশন যুক্ত করে নিন।
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                <i class="fa fa-arrow-left me-1"></i> পূর্বের পেজে ফিরে যান
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                <i class="fa fa-home me-1"></i> ড্যাশবোর্ডে যান
            </a>
        </div>
    </div>
</body>
</html>
