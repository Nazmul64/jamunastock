@extends('layouts.backend.app')

@section('content')
<style>
/* Modern Dynamic Permission Tree Styling */
.tree-container {
    background: #f8fafc;
    border-radius: 14px;
    padding: 10px;
}

.tree-module-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
    overflow: hidden;
}
.tree-module-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
}

.tree-module-header {
    background: #f1f5f9;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    user-select: none;
}
.tree-module-header:hover {
    background: #e2e8f0;
}

.tree-menu-block {
    border-bottom: 1px dashed #e2e8f0;
    padding: 16px 20px;
}
.tree-menu-block:last-child {
    border-bottom: none;
}

.tree-submenu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid #f1f5f9;
}

/* Action Badges / Pill Toggles */
.action-pills-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.action-pill-item {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    cursor: pointer;
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b !important;
    transition: all 0.15s ease-in-out;
    user-select: none;
}
.action-pill-item:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1d4ed8 !important;
    transform: translateY(-1px);
}
.action-pill-item.is-selected {
    background: #eff6ff !important;
    border-color: #2563eb !important;
    color: #1e40af !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.18) !important;
}

/* Color Coded Action Badges */
.badge-act-view { color: #0284c7; }
.badge-act-create { color: #16a34a; }
.badge-act-edit { color: #d97706; }
.badge-act-delete { color: #dc2626; }
.badge-act-approve { color: #9333ea; }
.badge-act-export { color: #0d9488; }
.badge-act-settings { color: #475569; }

/* Custom Checkbox Design */
.tree-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2563eb;
    margin-right: 8px;
}

/* Sticky Action Bar */
.sticky-action-bar {
    position: sticky;
    top: 70px;
    z-index: 1020;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    padding: 12px 20px;
    margin-bottom: 24px;
}

/* Floating Save Button */
.floating-save-btn {
    position: fixed;
    bottom: 25px;
    right: 35px;
    z-index: 1060;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
    border-radius: 50px;
}
@media (max-width: 767px) {
    .floating-save-btn {
        bottom: 15px;
        right: 15px;
        left: 15px;
        text-align: center;
    }
}
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-sitemap text-primary me-2"></i> এন্টারপ্রাইজ ডাইনামিক পারমিশন ট্রি ও ম্যাট্রিক্স
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">রোল ব্যবস্থাপনা</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ডাইনামিক পারমিশন ট্রি</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="{{ route('admin.roles.sync_registry') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success rounded-pill px-3 shadow-sm">
                            <i class="fa fa-sync-alt me-1"></i> রেজিস্ট্রি সিঙ্ক করুন (Sync All)
                        </button>
                    </form>
                    <a href="{{ route('admin.roles.user_assignment') }}" class="btn btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-users me-1"></i> ইউজার অ্যাসাইনমেন্ট
                    </a>
                    <a href="{{ route('admin.roles.simulator') }}" class="btn btn-outline-info rounded-pill px-3">
                        <i class="fa fa-eye me-1"></i> এক্সেস সিমুলেটর
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fs-3 me-3 text-success"></i>
                        <div>
                            <strong class="d-block">সফল হয়েছে!</strong>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fs-3 me-3 text-danger"></i>
                        <div>
                            <strong class="d-block">সতর্কবার্তা!</strong>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Interactive Role Tabs Bar (All Roles Visible & Clickable) -->
            <div class="card border-0 shadow-sm rounded-4 mb-3 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-dark fs-14">
                            <i class="fa fa-users-cog text-primary me-1"></i> সিস্টেমের সকল রোল (Role Tabs - ক্লিক করে সরাসরি পারমিশন কনফিগার করুন):
                        </span>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-plus me-1"></i> নতুন রোল তৈরি
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 overflow-auto pb-1" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
                        @foreach($roles as $r)
                            @php
                                $isActiveRole = ($selectedRole && $selectedRole->id == $r->id);
                            @endphp
                            <a href="{{ route('admin.roles.matrix', ['role_id' => $r->id]) }}" 
                               class="btn btn-sm rounded-pill px-3 py-2 fw-bold text-decoration-none d-inline-flex align-items-center gap-2 transition-all {{ $isActiveRole ? 'shadow-sm' : 'border bg-light text-dark' }}"
                               style="{{ $isActiveRole ? 'background: linear-gradient(135deg, #2563eb, #1d4ed8) !important; color: #ffffff !important; border: none;' : 'color: #334155;' }}">
                                @if($r->slug === 'super_admin')
                                    <i class="fa fa-shield text-warning"></i>
                                @elseif($r->slug === 'staff')
                                    <i class="fa fa-user-tie text-info"></i>
                                @else
                                    <i class="fa fa-id-badge text-muted"></i>
                                @endif
                                <span>{{ $r->name }}</span>
                                <span class="badge {{ $isActiveRole ? 'bg-white text-primary' : 'bg-white text-secondary border' }} rounded-pill" style="font-size: 11px;">
                                    {{ $r->permissions->count() }} সক্রিয়
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Role Selector Top Bar -->
            <div class="card border-0 shadow-sm rounded-4 mb-3" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white;">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-5 col-md-6 col-12">
                            <label class="form-label text-white-50 fw-semibold mb-1">
                                <i class="fa fa-user-tag text-warning me-1"></i> পারমিশন নির্ধারণের জন্য ভূমিকা (Role) নির্বাচন করুন:
                            </label>
                            <form method="GET" action="{{ route('admin.roles.matrix') }}" id="roleSelectorForm">
                                <select name="role_id" class="form-select form-select-lg border-0 shadow-sm rounded-3 fw-bold text-dark" onchange="document.getElementById('roleSelectorForm').submit();" style="font-size: 16px;">
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ ($selectedRole && $selectedRole->id == $r->id) ? 'selected' : '' }}>
                                            {{ $r->name }} ({{ $r->slug }}) — [{{ $r->permissions->count() }} পারমিশন সক্রিয়]
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-7 col-md-6 col-12 text-md-end text-start">
                            @if($selectedRole)
                                <div class="d-inline-flex flex-wrap gap-2 align-items-center">
                                    <div class="bg-dark bg-opacity-50 px-3 py-2 rounded-3 border border-secondary text-start">
                                        <small class="text-white-50 d-block">নির্বাচিত রোল</small>
                                        <strong class="text-warning fs-5">{{ $selectedRole->name }}</strong>
                                        <span class="badge bg-primary ms-1">{{ $selectedRole->category }}</span>
                                    </div>
                                    <div class="bg-dark bg-opacity-50 px-3 py-2 rounded-3 border border-secondary text-start">
                                        <small class="text-white-50 d-block">ডাটা স্কোপ (Scope)</small>
                                        <span class="badge bg-info text-dark fw-bold text-uppercase">{{ $selectedRole->data_scope ?? 'ALL' }}</span>
                                    </div>
                                    <div class="bg-dark bg-opacity-50 px-3 py-2 rounded-3 border border-secondary text-start">
                                        <small class="text-white-50 d-block">অনুমোদিত অ্যাকশন</small>
                                        <strong class="text-success fs-5" id="headerGrantedCounter">{{ $totalGrantedActions }}</strong> / {{ $totalSystemActions }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Tree Form -->
            <form id="permissionTreeForm" method="POST" action="{{ route('admin.roles.matrix.update') }}">
                @csrf
                <input type="hidden" name="role_id" value="{{ $selectedRole ? $selectedRole->id : '' }}">

                <!-- Sticky Filter & Action Controls Bar -->
                <div class="sticky-action-bar">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <!-- Quick Presets for Admin -->
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark small me-1"><i class="fa fa-magic text-warning me-1"></i> কুইক প্রিসেট:</span>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btnSelectAll" title="সকল মডিউলের ১০০% পারমিশন প্রদান করুন">
                                <i class="fa fa-check-double me-1"></i> সবগুলো সিলেক্ট (Full Access)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3" id="btnPresetChat" title="শুধুমাত্র লাইভ চ্যাট ও সাপোর্ট টিকেট এক্সেস">
                                <i class="fa fa-comments me-1 text-warning"></i> শুধুমাত্র লাইভ চ্যাট
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" id="btnPresetHrm" title="এইচআরএম ও এমপ্লয়ী এক্সেস">
                                <i class="fa fa-users me-1"></i> এইচআর ও হাজিরা
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnUnselectAll">
                                <i class="fa fa-times me-1"></i> সবগুলো বাতিল (Clear All)
                            </button>
                        </div>

                        <!-- Bulk Controls & Save -->
                        <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2" id="btnExpandAll" title="সব মডিউল খুলুন">
                                <i class="fa fa-chevron-down"></i> সব খুলুন
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2" id="btnCollapseAll" title="সব মডিউল গুটান">
                                <i class="fa fa-chevron-up"></i> সব গুটান
                            </button>
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 shadow fw-bold" id="btnSaveTop">
                                <i class="fa fa-save me-1"></i> পরিবর্তন সংরক্ষণ করুন
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hierarchical Permission Tree Container -->
                <div class="tree-container">
                    @forelse($permissionTree as $mKey => $module)
                        <div class="tree-module-card module-search-target" data-module="{{ $mKey }}">
                            <!-- Module Header Level -->
                            <div class="tree-module-header" data-toggle="collapse" data-target="#collapse_{{ $mKey }}" aria-expanded="true">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="tree-checkbox module-level-checkbox me-3" data-module="{{ $mKey }}" title="এই মডিউলের সব অ্যাকশন নির্বাচন করুন" onclick="event.stopPropagation();">
                                    <div>
                                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                            <i class="fa fa-folder text-warning me-2"></i>
                                            <span>{{ $module['module_name'] }}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-pill px-3 py-1 font-monospace module-counter-badge" data-module="{{ $mKey }}">
                                        {{ $module['granted_count'] }} / {{ $module['total_count'] }} নির্বাচিত
                                    </span>
                                    <button type="button" class="btn btn-sm btn-light border-0 text-muted" onclick="event.stopPropagation(); toggleCollapse('collapse_{{ $mKey }}');">
                                        <i class="fa fa-chevron-down collapse-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Module Collapsible Body -->
                            <div class="collapse show" id="collapse_{{ $mKey }}">
                                <div class="card-body p-0">
                                    @foreach($module['menus'] as $menuKey => $menu)
                                        <div class="tree-menu-block">
                                            @foreach($menu['submenus'] as $subKey => $submenu)
                                                <div class="mb-3">
                                                    <!-- Submenu Header Level -->
                                                    <div class="tree-submenu-header">
                                                        <div class="d-flex align-items-center">
                                                            <input type="checkbox" class="tree-checkbox submenu-level-checkbox" data-module="{{ $mKey }}" data-menu="{{ $menuKey }}" data-submenu="{{ $subKey }}" title="এই সাবমেনুর সব অ্যাকশন নির্বাচন করুন">
                                                            <span class="fw-bold text-secondary fs-14">
                                                                <i class="fa fa-layer-group text-info me-1"></i> {{ $menu['menu_name'] }} &raquo; {{ $submenu['submenu_name'] }}
                                                            </span>
                                                        </div>
                                                        <span class="badge bg-light text-dark border submenu-counter-badge" data-module="{{ $mKey }}" data-menu="{{ $menuKey }}" data-submenu="{{ $subKey }}">
                                                            {{ $submenu['granted_count'] }} / {{ $submenu['total_count'] }}
                                                        </span>
                                                    </div>

                                                    <!-- Action Badges / Pill Checkboxes Level -->
                                                    <div class="action-pills-grid">
                                                        @foreach($submenu['actions'] as $act)
                                                            @php
                                                                $actIcon = 'fa-eye';
                                                                $actClass = 'badge-act-view';
                                                                if($act['action_key'] == 'create') { $actIcon = 'fa-plus-circle'; $actClass = 'badge-act-create'; }
                                                                elseif($act['action_key'] == 'edit') { $actIcon = 'fa-pen'; $actClass = 'badge-act-edit'; }
                                                                elseif($act['action_key'] == 'delete') { $actIcon = 'fa-trash'; $actClass = 'badge-act-delete'; }
                                                                elseif($act['action_key'] == 'approve') { $actIcon = 'fa-check-circle'; $actClass = 'badge-act-approve'; }
                                                                elseif($act['action_key'] == 'export') { $actIcon = 'fa-file-excel'; $actClass = 'badge-act-export'; }
                                                                elseif($act['action_key'] == 'settings') { $actIcon = 'fa-cog'; $actClass = 'badge-act-settings'; }
                                                            @endphp

                                                            <label class="action-pill-item {{ $act['is_granted'] ? 'is-selected' : '' }} action-search-target" data-name="{{ strtolower($act['action_name']) }}" data-key="{{ strtolower($act['permission_key']) }}">
                                                                <input type="checkbox" name="permissions[]" value="{{ $act['permission_key'] }}" class="action-checkbox me-2" style="width: 16px; height: 16px; cursor: pointer; accent-color: #2563eb;" data-module="{{ $mKey }}" data-menu="{{ $menuKey }}" data-submenu="{{ $subKey }}" {{ $act['is_granted'] ? 'checked' : '' }}>
                                                                <i class="fa {{ $actIcon }} {{ $actClass }} me-2"></i>
                                                                <span>{{ $act['action_name'] }}</span>
                                                                <code class="ms-2 text-muted small" style="font-size: 11px;">({{ $act['action_key'] }})</code>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-5 text-center shadow-sm rounded-4">
                            <i class="fa fa-info-circle text-muted fs-1 mb-3"></i>
                            <h4>কোনো পারমিশন রেজিস্ট্রি পাওয়া যায়নি!</h4>
                            <p class="text-muted">দয়া করে উপরে থাকা "রেজিস্ট্রি সিঙ্ক করুন" বাটনে ক্লিক করে স্বয়ংক্রিয়ভাবে পারমিশন ক্যাটালগ সিঙ্ক করুন।</p>
                        </div>
                    @endforelse
                </div>

                <!-- Floating Save Button on Bottom Right -->
                <div class="floating-save-btn">
                    <button type="submit" class="btn btn-success btn-lg px-4 py-3 rounded-pill shadow-lg d-flex align-items-center gap-2">
                        <i class="fa fa-shield-alt fs-4"></i>
                        <span class="fw-bold">পারমিশন সংরক্ষণ করুন</span>
                        <span class="badge bg-white text-success rounded-pill px-2 py-1" id="floatingCounterBadge">{{ $totalGrantedActions }}</span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const actionCheckboxes = document.querySelectorAll('.action-checkbox');
    const moduleCheckboxes = document.querySelectorAll('.module-level-checkbox');
    const submenuCheckboxes = document.querySelectorAll('.submenu-level-checkbox');
    const searchInput = document.getElementById('treeSearchInput');
    const headerCounter = document.getElementById('headerGrantedCounter');
    const floatingCounter = document.getElementById('floatingCounterBadge');
    let hasUnsavedChanges = false;

    // 1. Action Pill Toggle
    actionCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            hasUnsavedChanges = true;
            const parentLabel = this.closest('.action-pill-item');
            if (this.checked) {
                parentLabel.classList.add('is-selected');
            } else {
                parentLabel.classList.remove('is-selected');
            }
            updateAllCounters();
        });
    });

    // 2. Submenu Level Checkbox (Selects all actions in that submenu)
    submenuCheckboxes.forEach(subCb => {
        subCb.addEventListener('change', function () {
            hasUnsavedChanges = true;
            const mod = this.dataset.module;
            const menu = this.dataset.menu;
            const sub = this.dataset.submenu;
            const targetActions = document.querySelectorAll(`.action-checkbox[data-module="${mod}"][data-menu="${menu}"][data-submenu="${sub}"]`);
            
            targetActions.forEach(ac => {
                ac.checked = subCb.checked;
                const parentLabel = ac.closest('.action-pill-item');
                if (subCb.checked) parentLabel.classList.add('is-selected');
                else parentLabel.classList.remove('is-selected');
            });

            updateAllCounters();
        });
    });

    // 3. Module Level Checkbox (Selects all actions in the entire module)
    moduleCheckboxes.forEach(modCb => {
        modCb.addEventListener('change', function () {
            hasUnsavedChanges = true;
            const mod = this.dataset.module;
            const targetActions = document.querySelectorAll(`.action-checkbox[data-module="${mod}"]`);
            
            targetActions.forEach(ac => {
                ac.checked = modCb.checked;
                const parentLabel = ac.closest('.action-pill-item');
                if (modCb.checked) parentLabel.classList.add('is-selected');
                else parentLabel.classList.remove('is-selected');
            });

            updateAllCounters();
        });
    });

    // 4. Select All Button
    document.getElementById('btnSelectAll').addEventListener('click', function () {
        hasUnsavedChanges = true;
        actionCheckboxes.forEach(ac => {
            ac.checked = true;
            ac.closest('.action-pill-item').classList.add('is-selected');
        });
        updateAllCounters();
    });

    // 5. Unselect All Button
    document.getElementById('btnUnselectAll').addEventListener('click', function () {
        hasUnsavedChanges = true;
        actionCheckboxes.forEach(ac => {
            ac.checked = false;
            ac.closest('.action-pill-item').classList.remove('is-selected');
        });
        updateAllCounters();
    });

    // 5.1 Preset: Live Chat Only
    const btnPresetChat = document.getElementById('btnPresetChat');
    if (btnPresetChat) {
        btnPresetChat.addEventListener('click', function () {
            hasUnsavedChanges = true;
            actionCheckboxes.forEach(ac => {
                const mod = ac.dataset.module;
                const key = (ac.value || '').toLowerCase();
                const isChat = (mod === 'support') || key.includes('chat') || key.includes('ticket');
                ac.checked = isChat;
                const parentLabel = ac.closest('.action-pill-item');
                if (isChat) parentLabel.classList.add('is-selected');
                else parentLabel.classList.remove('is-selected');
            });
            updateAllCounters();
            const el = document.getElementById('collapse_support');
            if (el) el.classList.add('show');
        });
    }

    // 5.2 Preset: HRM Only
    const btnPresetHrm = document.getElementById('btnPresetHrm');
    if (btnPresetHrm) {
        btnPresetHrm.addEventListener('click', function () {
            hasUnsavedChanges = true;
            actionCheckboxes.forEach(ac => {
                const mod = ac.dataset.module;
                const isHrm = (mod === 'hrm' || mod === 'employee');
                ac.checked = isHrm;
                const parentLabel = ac.closest('.action-pill-item');
                if (isHrm) parentLabel.classList.add('is-selected');
                else parentLabel.classList.remove('is-selected');
            });
            updateAllCounters();
            const el = document.getElementById('collapse_hrm');
            if (el) el.classList.add('show');
        });
    }

    // 6. Expand All
    document.getElementById('btnExpandAll').addEventListener('click', function () {
        document.querySelectorAll('.tree-module-card .collapse').forEach(el => {
            el.classList.add('show');
        });
    });

    // 7. Collapse All
    document.getElementById('btnCollapseAll').addEventListener('click', function () {
        document.querySelectorAll('.tree-module-card .collapse').forEach(el => {
            el.classList.remove('show');
        });
    });

    // 8. Instant Search Filter
    searchInput.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
        const moduleCards = document.querySelectorAll('.tree-module-card');

        if (!query) {
            moduleCards.forEach(mc => {
                mc.style.display = '';
                mc.querySelectorAll('.action-pill-item').forEach(pill => pill.style.display = '');
            });
            return;
        }

        moduleCards.forEach(mc => {
            let moduleHasMatch = false;
            const actionPills = mc.querySelectorAll('.action-pill-item');

            actionPills.forEach(pill => {
                const name = pill.dataset.name || '';
                const key = pill.dataset.key || '';
                if (name.includes(query) || key.includes(query)) {
                    pill.style.display = '';
                    moduleHasMatch = true;
                } else {
                    pill.style.display = 'none';
                }
            });

            const moduleName = mc.querySelector('.tree-module-header span').textContent.toLowerCase();
            if (moduleName.includes(query) || moduleHasMatch) {
                mc.style.display = '';
                mc.querySelector('.collapse').classList.add('show');
            } else {
                mc.style.display = 'none';
            }
        });
    });

    // 9. Update Checkbox Indeterminate States & Badges
    function updateAllCounters() {
        let globalGranted = 0;

        moduleCheckboxes.forEach(modCb => {
            const mod = modCb.dataset.module;
            const modActions = document.querySelectorAll(`.action-checkbox[data-module="${mod}"]`);
            let modGranted = 0;

            modActions.forEach(ac => {
                if (ac.checked) modGranted++;
            });

            globalGranted += modGranted;

            // Update Module Checkbox State (Checked / Indeterminate / Unchecked)
            if (modGranted === 0) {
                modCb.checked = false;
                modCb.indeterminate = false;
            } else if (modGranted === modActions.length) {
                modCb.checked = true;
                modCb.indeterminate = false;
            } else {
                modCb.checked = false;
                modCb.indeterminate = true;
            }

            // Update Module Badge
            const badge = document.querySelector(`.module-counter-badge[data-module="${mod}"]`);
            if (badge) {
                badge.textContent = `${modGranted} / ${modActions.length} নির্বাচিত`;
            }
        });

        // Update Submenu Checkboxes
        submenuCheckboxes.forEach(subCb => {
            const mod = subCb.dataset.module;
            const menu = subCb.dataset.menu;
            const sub = subCb.dataset.submenu;
            const subActions = document.querySelectorAll(`.action-checkbox[data-module="${mod}"][data-menu="${menu}"][data-submenu="${sub}"]`);
            let subGranted = 0;

            subActions.forEach(ac => {
                if (ac.checked) subGranted++;
            });

            if (subGranted === 0) {
                subCb.checked = false;
                subCb.indeterminate = false;
            } else if (subGranted === subActions.length) {
                subCb.checked = true;
                subCb.indeterminate = false;
            } else {
                subCb.checked = false;
                subCb.indeterminate = true;
            }

            const subBadge = document.querySelector(`.submenu-counter-badge[data-module="${mod}"][data-menu="${menu}"][data-submenu="${sub}"]`);
            if (subBadge) {
                subBadge.textContent = `${subGranted} / ${subActions.length}`;
            }
        });

        // Update Global Counters
        if (headerCounter) headerCounter.textContent = globalGranted;
        if (floatingCounter) floatingCounter.textContent = globalGranted;
    }

    // Initial counter check for indeterminate states
    updateAllCounters();

    // 10. Unsaved Changes Warning
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'আপনার করা পরিবর্তনগুলো সংরক্ষিত হয়নি। আপনি কি পেজটি ত্যাগ করতে চান?';
        }
    });

    document.getElementById('permissionTreeForm').addEventListener('submit', function () {
        hasUnsavedChanges = false;
    });
});

function toggleCollapse(id) {
    const el = document.getElementById(id);
    if (el) {
        if (el.classList.contains('show')) {
            el.classList.remove('show');
        } else {
            el.classList.add('show');
        }
    }
}
</script>
@endsection
