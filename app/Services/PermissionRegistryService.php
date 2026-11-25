<?php

namespace App\Services;

use App\Models\PermissionRegistry;
use App\Models\RbacPermission;
use App\Models\Role;
use App\Models\User;
use App\Models\PermissionAuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PermissionRegistryService
{
    /**
     * Master Schema of All Modules, Menus, Submenus and Actions across the Enterprise Platform
     */
    public static function getMasterCatalog(): array
    {
        return [
            // 1. Dashboard Module
            [
                'module_key' => 'dashboard',
                'module_name' => 'ড্যাশবোর্ড (Dashboard)',
                'menu_key' => 'main_dashboard',
                'menu_name' => 'প্রধান ড্যাশবোর্ড',
                'submenu_key' => 'analytics',
                'submenu_name' => 'পরিসংখ্যান ও সারাংশ',
                'actions' => [
                    ['action' => 'view', 'name' => 'ড্যাশবোর্ড পর্যবেক্ষণ', 'key' => 'dashboard.view', 'route' => 'home'],
                    ['action' => 'view_details', 'name' => 'অ্যানালিটিক্স চার্ট দেখা', 'key' => 'dashboard.analytics', 'route' => 'home'],
                    ['action' => 'export', 'name' => 'ড্যাশবোর্ড রিপোর্ট এক্সপোর্ট', 'key' => 'dashboard.export', 'route' => 'home'],
                ]
            ],

            // 2. Corporate & Board of Directors
            [
                'module_key' => 'corporate',
                'module_name' => 'পরিচালনা পর্ষদ ও কর্পোরেট (Corporate & Board)',
                'menu_key' => 'board_members',
                'menu_name' => 'বোর্ড অব ডিরেক্টরস',
                'submenu_key' => 'members_list',
                'submenu_name' => 'পরিচালক ও টিম মেম্বার',
                'actions' => [
                    ['action' => 'view', 'name' => 'পরিচালক তালিকা দেখা', 'key' => 'corporate.board.view', 'route' => 'admin.corporate.index'],
                    ['action' => 'create', 'name' => 'নতুন পরিচালক যুক্ত করা', 'key' => 'corporate.board.create', 'route' => 'admin.corporate.create'],
                    ['action' => 'edit', 'name' => 'পরিচালকের তথ্য সংশোধন', 'key' => 'corporate.board.edit', 'route' => 'admin.corporate.edit'],
                    ['action' => 'delete', 'name' => 'পরিচালক প্রোফাইল অপসারণ', 'key' => 'corporate.board.delete', 'route' => 'admin.corporate.delete'],
                ]
            ],
            [
                'module_key' => 'corporate',
                'module_name' => 'পরিচালনা পর্ষদ ও কর্পোরেট (Corporate & Board)',
                'menu_key' => 'corporate_profile',
                'menu_name' => 'কর্পোরেট প্রোফাইল',
                'submenu_key' => 'company_info',
                'submenu_name' => 'কোম্পানি নথি ও পলিসি',
                'actions' => [
                    ['action' => 'settings', 'name' => 'কর্পোরেট নথি পরিচালনা', 'key' => 'corporate.profile.manage', 'route' => 'admin.corporate.index'],
                ]
            ],

            // 3. Investments & Projects (9 Sectors Enterprise System)
            [
                'module_key' => 'investments',
                'module_name' => 'বিনিয়োগ ও প্রকল্প খাত (Investments & Projects)',
                'menu_key' => 'enterprise_investments',
                'menu_name' => '৯টি এন্টারপ্রাইজ ইনভেস্টমেন্ট সেক্টর',
                'submenu_key' => 'central_dashboard',
                'submenu_name' => 'কেন্দ্রীয় ড্যাশবোর্ড ও সেক্টর হিসাব',
                'actions' => [
                    ['action' => 'view', 'name' => 'কেন্দ্রীয় ড্যাশবোর্ড দেখা', 'key' => 'investments.dashboard.view', 'route' => 'admin.investments.central.dashboard'],
                    ['action' => 'view_sector', 'name' => 'সেক্টর ড্যাশবোর্ড ও কাস্টমার হিসাব', 'key' => 'investments.sector.view', 'route' => 'admin.investments.sector.dashboard'],
                    ['action' => 'contracts_view', 'name' => 'বিনিয়োগ চুক্তিপত্র দেখা', 'key' => 'investments.contracts.view', 'route' => 'admin.investments.index'],
                    ['action' => 'contracts_create', 'name' => 'নতুন বিনিয়োগ চুক্তি তৈরি', 'key' => 'investments.contracts.create', 'route' => 'admin.investments.create'],
                    ['action' => 'contracts_settle', 'name' => 'মেয়াদপূর্ব উত্তোলন ও নিষ্পত্তি', 'key' => 'investments.contracts.settle', 'route' => 'admin.investments.settlement.process'],
                    ['action' => 'profit_pay', 'name' => 'লভ্যাংশ কিস্তি পরিশোধ', 'key' => 'investments.profit.pay', 'route' => 'admin.investments.profit.pay'],
                    ['action' => 'receipts_view', 'name' => 'মানি রসিদ দেখা ও প্রিন্ট', 'key' => 'investments.receipts.print', 'route' => 'admin.investments.receipts.view'],
                    ['action' => 'receipts_void', 'name' => 'মানি রসিদ বাতিল (Void)', 'key' => 'investments.receipts.void', 'route' => 'admin.investments.receipts.void'],
                    ['action' => 'ledger_view', 'name' => 'ফাইন্যান্সিয়াল লেজার খাতা দেখা', 'key' => 'investments.ledger.view', 'route' => 'admin.investments.ledger.index'],
                    ['action' => 'inquiries_crm', 'name' => 'অনলাইন ইনকোয়ারি পাইপলাইন পরিচালনা', 'key' => 'investments.inquiries.manage', 'route' => 'admin.investments.inquiries.index'],
                    ['action' => 'inquiries_convert', 'name' => 'ইনকোয়ারি থেকে বিনিয়োগে রূপান্তর', 'key' => 'investments.inquiries.convert', 'route' => 'admin.investments.inquiries.convert'],
                ]
            ],
            [
                'module_key' => 'investments',
                'module_name' => 'বিনিয়োগ ও প্রকল্প খাত (Investments & Projects)',
                'menu_key' => 'investment_projects',
                'menu_name' => 'প্রকল্প পরিচালনা',
                'submenu_key' => 'project_list',
                'submenu_name' => 'লাইভ ও আসন্ন প্রকল্প',
                'actions' => [
                    ['action' => 'view', 'name' => 'প্রকল্প তালিকা দেখা', 'key' => 'investments.projects.view', 'route' => 'admin.investments.index'],
                    ['action' => 'create', 'name' => 'নতুন প্রকল্প যোগ', 'key' => 'investments.projects.create', 'route' => 'admin.investments.create'],
                    ['action' => 'edit', 'name' => 'প্রকল্প তথ্য সংশোধন', 'key' => 'investments.projects.edit', 'route' => 'admin.investments.edit'],
                    ['action' => 'approve', 'name' => 'প্রকল্প ফান্ডিং অনুমোদন', 'key' => 'investments.projects.approve', 'route' => 'admin.investments.index'],
                    ['action' => 'delete', 'name' => 'প্রকল্প বাতিল/অপসারণ', 'key' => 'investments.projects.delete', 'route' => 'admin.investments.destroy'],
                ]
            ],

            // 4. Shareholders & Partnership
            [
                'module_key' => 'shareholders',
                'module_name' => 'শেয়ারহোল্ডার ও পার্টনারশিপ (Shareholders & Partners)',
                'menu_key' => 'shareholder_registry',
                'menu_name' => 'শেয়ারহোল্ডার রেজিস্ট্রি',
                'submenu_key' => 'shareholders_list',
                'submenu_name' => 'শেয়ারহোল্ডার তালিকা',
                'actions' => [
                    ['action' => 'view', 'name' => 'শেয়ারহোল্ডার তালিকা দেখা', 'key' => 'shareholders.view', 'route' => 'admin.shareholders.index'],
                    ['action' => 'create', 'name' => 'নতুন শেয়ারহোল্ডার এন্ট্রি', 'key' => 'shareholders.create', 'route' => 'admin.shareholders.create'],
                    ['action' => 'edit', 'name' => 'শেয়ারহোল্ডার তথ্য আপডেট', 'key' => 'shareholders.edit', 'route' => 'admin.shareholders.edit'],
                    ['action' => 'delete', 'name' => 'শেয়ারহোল্ডার রেকর্ড অপসারণ', 'key' => 'shareholders.delete', 'route' => 'admin.shareholders.destroy'],
                ]
            ],
            [
                'module_key' => 'shareholders',
                'module_name' => 'শেয়ারহোল্ডার ও পার্টনারশিপ (Shareholders & Partners)',
                'menu_key' => 'partnerships',
                'menu_name' => 'পার্টনারশিপ ও লভ্যাংশ',
                'submenu_key' => 'dividend_settings',
                'submenu_name' => 'লভ্যাংশ ও পলিসি সেটিংস',
                'actions' => [
                    ['action' => 'settings', 'name' => 'পার্টনারশিপ পলিসি পরিচালনা', 'key' => 'shareholders.partnerships.manage', 'route' => 'admin.shareholders.index'],
                    ['action' => 'export', 'name' => 'শেয়ারহোল্ডার অডিট রিপোর্ট এক্সপোর্ট', 'key' => 'shareholders.export', 'route' => 'admin.shareholders.index'],
                ]
            ],

            // 5. Central Finance & Accounts
            [
                'module_key' => 'finance',
                'module_name' => 'সেন্ট্রাল ফিন্যান্স ও অ্যাকাউন্ট (Central Finance)',
                'menu_key' => 'accounts_head',
                'menu_name' => 'অ্যাকাউন্টস হেড ও লেজার',
                'submenu_key' => 'chart_of_accounts',
                'submenu_name' => 'চার্ট অব অ্যাকাউন্টস',
                'actions' => [
                    ['action' => 'view', 'name' => 'অ্যাকাউন্টস লেজার দেখা', 'key' => 'finance.accounts.view', 'route' => 'admin.finance.index'],
                    ['action' => 'create', 'name' => 'নতুন অ্যাকাউন্ট হেড তৈরি', 'key' => 'finance.accounts.create', 'route' => 'admin.finance.create'],
                    ['action' => 'edit', 'name' => 'অ্যাকাউন্ট হেড সম্পাদনা', 'key' => 'finance.accounts.edit', 'route' => 'admin.finance.edit'],
                ]
            ],
            [
                'module_key' => 'finance',
                'module_name' => 'সেন্ট্রাল ফিন্যান্স ও অ্যাকাউন্ট (Central Finance)',
                'menu_key' => 'transactions',
                'menu_name' => 'লেনদেন ও পেমেন্ট হিস্ট্রি',
                'submenu_key' => 'payment_records',
                'submenu_name' => 'আয়-ব্যয় লেনদেন',
                'actions' => [
                    ['action' => 'view', 'name' => 'লেনদেন তালিকা দেখা', 'key' => 'finance.transactions.view', 'route' => 'admin.finance.index'],
                    ['action' => 'create', 'name' => 'নতুন আর্থিক এন্ট্রি যুক্ত', 'key' => 'finance.transactions.create', 'route' => 'admin.finance.create'],
                    ['action' => 'approve', 'name' => 'লেনদেন অনুমোদন', 'key' => 'finance.transactions.approve', 'route' => 'admin.finance.index'],
                    ['action' => 'export', 'name' => 'আর্থিক স্টেটমেন্ট ডাউনলোড', 'key' => 'finance.reports.export', 'route' => 'admin.finance.index'],
                ]
            ],

            // 6. Bills & Vouchers Management
            [
                'module_key' => 'bills_vouchers',
                'module_name' => 'বিল ও ভাউচার ইকোসিস্টেম (Bills & Vouchers ERP)',
                'menu_key' => 'vouchers_main',
                'menu_name' => 'ভাউচার ম্যানেজমেন্ট',
                'submenu_key' => 'all_vouchers',
                'submenu_name' => 'ডেবিট, ক্রেডিট ও জার্নাল ভাউচার',
                'actions' => [
                    ['action' => 'view', 'name' => 'ভাউচার ড্যাশবোর্ড ও তালিকা দেখা', 'key' => 'bills_vouchers.vouchers.view', 'route' => 'admin.bills_vouchers.vouchers.index'],
                    ['action' => 'create', 'name' => 'নতুন ভাউচার তৈরি', 'key' => 'bills_vouchers.vouchers.create', 'route' => 'admin.bills_vouchers.vouchers.create'],
                    ['action' => 'edit', 'name' => 'ভাউচার সংশোধন', 'key' => 'bills_vouchers.vouchers.edit', 'route' => 'admin.bills_vouchers.vouchers.index'],
                    ['action' => 'delete', 'name' => 'ভাউচার মুছে ফেলা/বাতিল', 'key' => 'bills_vouchers.vouchers.delete', 'route' => 'admin.bills_vouchers.vouchers.index'],
                    ['action' => 'approve', 'name' => 'ভাউচার চূড়ান্ত অনুমোদন', 'key' => 'bills_vouchers.vouchers.approve', 'route' => 'admin.bills_vouchers.vouchers.approve'],
                    ['action' => 'print', 'name' => 'ভাউচার প্রিন্ট ও রশিদ তৈরি', 'key' => 'bills_vouchers.vouchers.print', 'route' => 'admin.bills_vouchers.vouchers.print'],
                    ['action' => 'export', 'name' => 'ভাউচার লেজার এক্সপোর্ট', 'key' => 'bills_vouchers.reports.export', 'route' => 'admin.bills_vouchers.vouchers.index'],
                ]
            ],

            // 7. Store & Assets ERP
            [
                'module_key' => 'store',
                'module_name' => 'স্টোর ও সম্পদ ERP (Store & Asset Management)',
                'menu_key' => 'assets_inventory',
                'menu_name' => 'সম্পদ ও ইনভেন্টরি',
                'submenu_key' => 'hardware_assets',
                'submenu_name' => 'হার্ডওয়্যার ও স্থায়ী সম্পদ',
                'actions' => [
                    ['action' => 'view', 'name' => 'সম্পদ তালিকা ও ড্যাশবোর্ড দেখা', 'key' => 'store.assets.view', 'route' => 'admin.store.assets.index'],
                    ['action' => 'create', 'name' => 'নতুন সম্পদ এন্ট্রি করা', 'key' => 'store.assets.create', 'route' => 'admin.store.assets.create'],
                    ['action' => 'edit', 'name' => 'সম্পদের বিবরণ পরিবর্তন', 'key' => 'store.assets.edit', 'route' => 'admin.store.assets.edit'],
                    ['action' => 'delete', 'name' => 'সম্পদ ডিসপোজ/অপসারণ', 'key' => 'store.assets.delete', 'route' => 'admin.store.assets.delete'],
                    ['action' => 'assign', 'name' => 'কর্মীকে সম্পদ বরাদ্দ ও হস্তান্তর', 'key' => 'store.assets.assign', 'route' => 'admin.store.assets.index'],
                    ['action' => 'export', 'name' => 'সম্পদ অডিট রিপোর্ট এক্সপোর্ট', 'key' => 'store.assets.export', 'route' => 'admin.store.assets.index'],
                ]
            ],
            [
                'module_key' => 'store',
                'module_name' => 'স্টোর ও সম্পদ ERP (Store & Asset Management)',
                'menu_key' => 'asset_maintenance',
                'menu_name' => 'রক্ষণাবেক্ষণ ও সার্ভিসিং',
                'submenu_key' => 'repair_logs',
                'submenu_name' => 'সার্ভিসিং ও বিল রেজিস্টার',
                'actions' => [
                    ['action' => 'view', 'name' => 'রক্ষণাবেক্ষণ লগ দেখা', 'key' => 'store.maintenances.view', 'route' => 'admin.store.maintenances.index'],
                    ['action' => 'create', 'name' => 'সার্ভিসিং রিকুইজিশন তৈরি', 'key' => 'store.maintenances.create', 'route' => 'admin.store.maintenances.create'],
                    ['action' => 'approve', 'name' => 'সার্ভিস বিল অনুমোদন', 'key' => 'store.maintenances.approve', 'route' => 'admin.store.maintenances.approve'],
                ]
            ],

            // 8. Live Chat & Support Tickets
            [
                'module_key' => 'support',
                'module_name' => 'লাইভ চ্যাট ও সাপোর্ট টিকেট (Support Ecosystem)',
                'menu_key' => 'tickets_management',
                'menu_name' => 'সাপোর্ট টিকেট ডেস্ক',
                'submenu_key' => 'ticket_queue',
                'submenu_name' => 'টিকেট কিউ ও সমাধান',
                'actions' => [
                    ['action' => 'view', 'name' => 'সাপোর্ট ড্যাশবোর্ড ও টিকেট দেখা', 'key' => 'support.tickets.view', 'route' => 'admin.chat.index'],
                    ['action' => 'create', 'name' => 'নতুন কাস্টমার টিকেট খোলা', 'key' => 'support.tickets.create', 'route' => 'admin.chat.index'],
                    ['action' => 'edit', 'name' => 'টিকেটে রিপ্লাই ও মেসেজ পাঠানো', 'key' => 'support.tickets.reply', 'route' => 'admin.chat.index'],
                    ['action' => 'assign', 'name' => 'টিকেট সাপোর্ট এজেন্টের কাছে অ্যাসাইন', 'key' => 'support.tickets.assign', 'route' => 'admin.chat.index'],
                    ['action' => 'approve', 'name' => 'টিকেট ক্লোজ/সমাধান চিহ্নিত করা', 'key' => 'support.tickets.close', 'route' => 'admin.chat.index'],
                ]
            ],
            [
                'module_key' => 'support',
                'module_name' => 'লাইভ চ্যাট ও সাপোর্ট টিকেট (Support Ecosystem)',
                'menu_key' => 'live_chat_agent',
                'menu_name' => 'রিয়েল-টাইম চ্যাট',
                'submenu_key' => 'chat_conversations',
                'submenu_name' => 'সরাসরি কথোপকথন',
                'actions' => [
                    ['action' => 'view', 'name' => 'লাইভ চ্যাট রুম দেখা', 'key' => 'support.livechat.view', 'route' => 'admin.chat.index'],
                    ['action' => 'edit', 'name' => 'গ্রাহকের সাথে সরাসরি চ্যাট করা', 'key' => 'support.livechat.reply', 'route' => 'admin.chat.index'],
                    ['action' => 'export', 'name' => 'সাপোর্ট পারফরম্যান্স রিপোর্ট দেখা', 'key' => 'support.reports.view', 'route' => 'admin.chat.index'],
                ]
            ],

            // 9. Stock Management
            [
                'module_key' => 'stocks',
                'module_name' => 'স্টক ম্যানেজমেন্ট (Stock Management)',
                'menu_key' => 'stock_overview',
                'menu_name' => 'স্টক মার্কেট ও মূল্য',
                'submenu_key' => 'stock_pricing',
                'submenu_name' => 'স্টক রেট ও ট্রেডিং',
                'actions' => [
                    ['action' => 'view', 'name' => 'স্টক তালিকা ও দর দেখা', 'key' => 'stocks.view', 'route' => 'admin.stock.index'],
                    ['action' => 'edit', 'name' => 'দৈনিক স্টক মূল্য নির্ধারণ (Set Price)', 'key' => 'stocks.set_price', 'route' => 'admin.stock.index'],
                    ['action' => 'create', 'name' => 'স্টক বাই/সেল অনুমোদন ও তদারকি', 'key' => 'stocks.buy_sell', 'route' => 'admin.stock.allStock'],
                    ['action' => 'settings', 'name' => 'স্টক প্রিসেট ও লিমিট সেটিংস', 'key' => 'stocks.presets.manage', 'route' => 'admin.stock_preset.index'],
                ]
            ],

            // 10. Monthly Bazaar & Distribution Ecosystem
            [
                'module_key' => 'bazaar',
                'module_name' => 'মাসিক বাজার ও সেন্ট্রাল ডিস্ট্রিবিউশন (Monthly Bazaar & Agents)',
                'menu_key' => 'bazaar_catalog',
                'menu_name' => 'বাজার আইটেম ক্যাটালগ',
                'submenu_key' => 'products_list',
                'submenu_name' => 'খাদ্যপণ্য ও নিত্যপ্রয়োজনীয় আইটেম',
                'actions' => [
                    ['action' => 'view', 'name' => 'বাজার পণ্য তালিকা দেখা', 'key' => 'bazaar.items.view', 'route' => 'admin.monthly_bazaar.index'],
                    ['action' => 'create', 'name' => 'নতুন পণ্য যোগ করা', 'key' => 'bazaar.items.create', 'route' => 'admin.monthly_bazaar.create'],
                    ['action' => 'edit', 'name' => 'পণ্যের মূল্য ও স্টক সংশোধন', 'key' => 'bazaar.items.edit', 'route' => 'admin.monthly_bazaar.edit'],
                    ['action' => 'delete', 'name' => 'পণ্য অপসারণ', 'key' => 'bazaar.items.delete', 'route' => 'admin.monthly_bazaar.destroy'],
                ]
            ],
            [
                'module_key' => 'bazaar',
                'module_name' => 'মাসিক বাজার ও সেন্ট্রাল ডিস্ট্রিবিউশন (Monthly Bazaar & Agents)',
                'menu_key' => 'bazaar_orders',
                'menu_name' => 'অর্ডার ও সেন্ট্রাল ডিসপ্যাচ',
                'submenu_key' => 'orders_workflow',
                'submenu_name' => 'সদস্য অর্ডার প্রসেসিং',
                'actions' => [
                    ['action' => 'view', 'name' => 'অর্ডার রিকোয়েস্ট দেখা', 'key' => 'bazaar.orders.view', 'route' => 'admin.monthly_bazaar.all_orders'],
                    ['action' => 'approve', 'name' => 'অর্ডার অনুমোদন ও রিলিজ', 'key' => 'bazaar.orders.approve', 'route' => 'admin.monthly_bazaar.all_orders'],
                    ['action' => 'transfer', 'name' => 'পণ্য ওয়্যারহাউস থেকে ডিসপ্যাচ', 'key' => 'bazaar.orders.dispatch', 'route' => 'admin.monthly_bazaar.all_orders'],
                ]
            ],
            [
                'module_key' => 'bazaar',
                'module_name' => 'মাসিক বাজার ও সেন্ট্রাল ডিস্ট্রিবিউশন (Monthly Bazaar & Agents)',
                'menu_key' => 'agent_points_eco',
                'menu_name' => 'এজেন্ট পয়েন্ট ও ডেলিভারি',
                'submenu_key' => 'agent_points_list',
                'submenu_name' => 'সংগ্রহ পয়েন্ট ও এজেন্ট স্টক',
                'actions' => [
                    ['action' => 'view', 'name' => 'এজেন্ট পয়েন্ট ও স্টক মনিটরিং', 'key' => 'bazaar.agent_points.manage', 'route' => 'admin.agent_points.index'],
                    ['action' => 'create', 'name' => 'এজেন্ট পয়েন্টে স্টক বরাদ্দ (Allocate)', 'key' => 'bazaar.agent_points.allocate_stock', 'route' => 'admin.agent_ledger.allocate'],
                    ['action' => 'view_details', 'name' => 'এজেন্ট ডেলিভারি হিস্ট্রি দেখা', 'key' => 'bazaar.deliveries.view', 'route' => 'admin.agent_deliveries.index'],
                    ['action' => 'export', 'name' => 'ডিস্ট্রিবিউশন অডিট রিপোর্ট এক্সপোর্ট', 'key' => 'bazaar.reports.export', 'route' => 'admin.agent_monitoring.index'],
                ]
            ],

            // 11. User & Financial Management
            [
                'module_key' => 'users',
                'module_name' => 'ব্যবহারকারী ও ক্লায়েন্ট ফাইন্যান্সিয়াল (User Management)',
                'menu_key' => 'clients_directory',
                'menu_name' => 'ক্লায়েন্ট ডিরেক্টরি',
                'submenu_key' => 'all_clients',
                'submenu_name' => 'রেজিস্টার্ড মেম্বার তালিকা',
                'actions' => [
                    ['action' => 'view', 'name' => 'ব্যবহারকারী তালিকা দেখা', 'key' => 'users.view', 'route' => 'user.index'],
                    ['action' => 'create', 'name' => 'নতুন মেম্বার যুক্ত করা', 'key' => 'users.create', 'route' => 'user.create'],
                    ['action' => 'edit', 'name' => 'মেম্বার তথ্য ও ব্যালেন্স সমন্বয়', 'key' => 'users.edit', 'route' => 'user.edit'],
                    ['action' => 'delete', 'name' => 'মেম্বার নিষ্ক্রিয় বা ব্যান করা', 'key' => 'users.deactivate', 'route' => 'user.destroy'],
                    ['action' => 'restore', 'name' => 'মেম্বার অ্যাকাউন্ট সক্রিয় করা', 'key' => 'users.restore', 'route' => 'user.index'],
                ]
            ],
            [
                'module_key' => 'users',
                'module_name' => 'ব্যবহারকারী ও ক্লায়েন্ট ফাইন্যান্সিয়াল (User Management)',
                'menu_key' => 'withdraw_requests',
                'menu_name' => 'উত্তোলন ব্যবস্থাপনা',
                'submenu_key' => 'withdraw_queue',
                'submenu_name' => 'টাকা উত্তোলনের আবেদন',
                'actions' => [
                    ['action' => 'view', 'name' => 'উত্তোলন রিকোয়েস্ট দেখা', 'key' => 'users.withdraw.view', 'route' => 'admin.withdraw.index'],
                    ['action' => 'approve', 'name' => 'উত্তোলন অনুমোদন ও পেমেন্ট রিলিজ', 'key' => 'users.withdraw.approve', 'route' => 'admin.withdraw.approve'],
                    ['action' => 'reject', 'name' => 'উত্তোলন আবেদন প্রত্যাখ্যান', 'key' => 'users.withdraw.reject', 'route' => 'admin.withdraw.reject'],
                    ['action' => 'export', 'name' => 'উত্তোলন স্টেটমেন্ট এক্সপোর্ট', 'key' => 'users.withdraw.export', 'route' => 'admin.withdraw.index'],
                ]
            ],

            // 12. HRM Management System
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'hrm_dashboard',
                'menu_name' => 'HRM এক্সিকিউটিভ ড্যাশবোর্ড',
                'submenu_key' => 'workforce_kpi',
                'submenu_name' => 'উপস্থিতি ও টার্নওভার KPI',
                'actions' => [
                    ['action' => 'view', 'name' => 'HRM ড্যাশবোর্ড পর্যবেক্ষণ', 'key' => 'hrm.dashboard.view', 'route' => 'admin.hrm.dashboard'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'employee_directory',
                'menu_name' => 'কর্মকর্তা-কর্মচারী ফাইল',
                'submenu_key' => 'employee_profiles',
                'submenu_name' => '৩৬০° এমপ্লয়ী প্রোফাইল',
                'actions' => [
                    ['action' => 'view', 'name' => 'এমপ্লয়ী তালিকা ও প্রোফাইল দেখা', 'key' => 'hrm.employees.view', 'route' => 'admin.hrm.employees.index'],
                    ['action' => 'create', 'name' => 'নতুন কর্মচারী যোগদান ও অনবোর্ডিং', 'key' => 'hrm.employees.create', 'route' => 'admin.hrm.employees.create'],
                    ['action' => 'edit', 'name' => 'এমপ্লয়ী তথ্য ও নথি সংশোধন', 'key' => 'hrm.employees.edit', 'route' => 'admin.hrm.employees.edit'],
                    ['action' => 'delete', 'name' => 'এমপ্লয়ী চাকরি অবসান/অপসারণ', 'key' => 'hrm.employees.delete', 'route' => 'admin.hrm.employees.destroy'],
                    ['action' => 'export', 'name' => 'এমপ্লয়ী ডিরেক্টরি এক্সপোর্ট', 'key' => 'hrm.employees.export', 'route' => 'admin.hrm.employees.index'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'attendance_shifts',
                'menu_name' => 'হাজিরা ও শিফট ব্যবস্থাপনা',
                'submenu_key' => 'daily_timesheet',
                'submenu_name' => 'দৈনিক হাজিরা ও মাসিক রেজিস্টার',
                'actions' => [
                    ['action' => 'view', 'name' => 'হাজিরা রেজিস্টার ও লগ দেখা', 'key' => 'hrm.attendances.view', 'route' => 'admin.hrm.attendance.index'],
                    ['action' => 'create', 'name' => 'ম্যানুয়াল হাজিরা পাঞ্চ ও শিফট এসাইন', 'key' => 'hrm.attendances.create', 'route' => 'admin.hrm.attendance.create'],
                    ['action' => 'edit', 'name' => 'হাজিরা ভুল সংশোধন আবেদন (Correction)', 'key' => 'hrm.attendances.edit', 'route' => 'admin.hrm.attendance.corrections'],
                    ['action' => 'approve', 'name' => 'হাজিরা সংশোধন অনুমোদন', 'key' => 'hrm.attendances.approve', 'route' => 'admin.hrm.attendance.corrections.approve'],
                    ['action' => 'settings', 'name' => 'হাজিরা পলিসি ও গ্রেস পিরিয়ড নির্ধারণ', 'key' => 'hrm.attendance_policy.manage', 'route' => 'admin.hrm.attendance.policy'],
                    ['action' => 'export', 'name' => 'মাসিক হাজিরা শীট এক্সপোর্ট', 'key' => 'hrm.attendances.export', 'route' => 'admin.hrm.attendance.monthly'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'biometric_terminals',
                'menu_name' => 'বায়োমেট্রিক ডিভাইস ইন্টিগ্রেশন',
                'submenu_key' => 'fingerprint_face',
                'submenu_name' => 'ফিঙ্গারপ্রিন্ট ও ফেস টার্মিনাল',
                'actions' => [
                    ['action' => 'view', 'name' => 'ডিভাইস তালিকা ও কানেকশন স্ট্যাটাস', 'key' => 'hrm.biometric.view', 'route' => 'admin.hrm.biometric.devices'],
                    ['action' => 'create', 'name' => 'নতুন ডিভাইস যোগ ও কনফিগারেশন', 'key' => 'hrm.biometric.create', 'route' => 'admin.hrm.biometric.devices.store'],
                    ['action' => 'assign', 'name' => 'কর্মীর ফিঙ্গার/ফেস ইউজার আইডি ম্যাপিং', 'key' => 'hrm.biometric.enroll', 'route' => 'admin.hrm.biometric.enrollment'],
                    ['action' => 'transfer', 'name' => 'টার্মিনাল থেকে সরাসরি লগ সিঙ্ক/আমদানি', 'key' => 'hrm.biometric.sync', 'route' => 'admin.hrm.biometric.devices.sync'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'leave_holidays',
                'menu_name' => 'ছুটি ও হলিডে ক্যালেন্ডার',
                'submenu_key' => 'leave_applications',
                'submenu_name' => 'ছুটির আবেদন ও কোটা',
                'actions' => [
                    ['action' => 'view', 'name' => 'ছুটি তালিকা ও ব্যালেন্স দেখা', 'key' => 'hrm.leaves.view', 'route' => 'admin.hrm.leaves.index'],
                    ['action' => 'create', 'name' => 'ছুটির আবেদন দাখিল', 'key' => 'hrm.leaves.create', 'route' => 'admin.hrm.leaves.create'],
                    ['action' => 'approve', 'name' => 'ছুটির আবেদন অনুমোদন বা বাতিল', 'key' => 'hrm.leaves.approve', 'route' => 'admin.hrm.leaves.approve'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'payroll_salary',
                'menu_name' => 'বেতন ও পে-রোল প্রসেসিং',
                'submenu_key' => 'monthly_payroll',
                'submenu_name' => 'মাসিক স্যালারি শীট ও পে-স্লিপ',
                'actions' => [
                    ['action' => 'view', 'name' => 'পে-রোল ও স্যালারি স্লিপ দেখা', 'key' => 'hrm.payroll.view', 'route' => 'admin.hrm.payroll.index'],
                    ['action' => 'create', 'name' => 'স্বয়ংক্রিয় মাসিক স্যালারি জেনারেট', 'key' => 'hrm.payroll.generate', 'route' => 'admin.hrm.payroll.generate'],
                    ['action' => 'approve', 'name' => 'বেতন অনুমোদন ও ফান্ড ডিসবার্স', 'key' => 'hrm.payroll.pay', 'route' => 'admin.hrm.payroll.index'],
                    ['action' => 'export', 'name' => 'ব্যাংক স্টেটমেন্ট ও পে-স্লিপ এক্সপোর্ট', 'key' => 'hrm.payroll.export', 'route' => 'admin.hrm.payroll.index'],
                ]
            ],
            [
                'module_key' => 'hrm',
                'module_name' => 'HRM ও মানবসম্পদ ব্যবস্থাপনা (Enterprise HRM)',
                'menu_key' => 'departments_designations',
                'menu_name' => 'বিভাগ ও পদবী ব্যবস্থাপনা',
                'submenu_key' => 'dept_structure',
                'submenu_name' => 'অর্গানোগ্রাম কাঠামো',
                'actions' => [
                    ['action' => 'settings', 'name' => 'বিভাগ ও পদবী পরিচালনা', 'key' => 'hrm.departments.manage', 'route' => 'admin.hrm.departments.index'],
                    ['action' => 'export', 'name' => 'HRM কমপ্লায়েন্স রিপোর্ট এক্সপোর্ট', 'key' => 'hrm.reports.export', 'route' => 'admin.hrm.reports.index'],
                ]
            ],

            // 13. Suppliers Management
            [
                'module_key' => 'suppliers',
                'module_name' => 'সাপ্লায়ার ব্যবস্থাপনা (Supplier Management)',
                'menu_key' => 'suppliers_list_menu',
                'menu_name' => 'সাপ্লায়ার ডিরেক্টরি',
                'submenu_key' => 'vendor_list',
                'submenu_name' => 'ভেন্ডর ও চালান রেকর্ড',
                'actions' => [
                    ['action' => 'view', 'name' => 'সাপ্লায়ার তালিকা দেখা', 'key' => 'suppliers.view', 'route' => 'admin.suppliers.index'],
                    ['action' => 'create', 'name' => 'নতুন সাপ্লায়ার যুক্ত করা', 'key' => 'suppliers.create', 'route' => 'admin.suppliers.create'],
                    ['action' => 'edit', 'name' => 'সাপ্লায়ার তথ্য সংশোধন', 'key' => 'suppliers.edit', 'route' => 'admin.suppliers.show'],
                    ['action' => 'delete', 'name' => 'সাপ্লায়ার অপসারণ', 'key' => 'suppliers.delete', 'route' => 'admin.suppliers.index'],
                    ['action' => 'approve', 'name' => 'পেন্ডিং সাপ্লাই অনুমোদন', 'key' => 'suppliers.supplies.approve', 'route' => 'admin.suppliers.pending_supplies'],
                    ['action' => 'export', 'name' => 'সাপ্লায়ার রিপোর্ট এক্সপোর্ট', 'key' => 'suppliers.reports.export', 'route' => 'admin.suppliers.reports'],
                ]
            ],

            // 14. Roles, Security & Simulator
            [
                'module_key' => 'roles',
                'module_name' => 'রোল ও এক্সেস সিকিউরিটি (Enterprise RBAC & ABAC)',
                'menu_key' => 'roles_directory',
                'menu_name' => 'রোল ম্যানেজমেন্ট',
                'submenu_key' => 'roles_list',
                'submenu_name' => 'রোল ক্যাটালগ ও স্কোপ',
                'actions' => [
                    ['action' => 'view', 'name' => 'রোল তালিকা দেখা', 'key' => 'roles.view', 'route' => 'admin.roles.index'],
                    ['action' => 'create', 'name' => 'নতুন রোল তৈরি', 'key' => 'roles.create', 'route' => 'admin.roles.store'],
                    ['action' => 'edit', 'name' => 'রোল সম্পাদনা ও আপডেট', 'key' => 'roles.edit', 'route' => 'admin.roles.update'],
                    ['action' => 'clone', 'name' => 'রোল ডুপ্লিকেট/ক্লোন করা', 'key' => 'roles.duplicate', 'route' => 'admin.roles.clone'],
                    ['action' => 'delete', 'name' => 'রোল মুছে ফেলা (Delete Role)', 'key' => 'roles.delete', 'route' => 'admin.roles.delete'],
                ]
            ],
            [
                'module_key' => 'roles',
                'module_name' => 'রোল ও এক্সেস সিকিউরিটি (Enterprise RBAC & ABAC)',
                'menu_key' => 'permission_matrix_tree',
                'menu_name' => 'ডাইনামিক পারমিশন ট্রি ও ম্যাট্রিক্স',
                'submenu_key' => 'tree_matrix',
                'submenu_name' => 'গ্র্যানুলার পারমিশন কনফিগারেশন',
                'actions' => [
                    ['action' => 'settings', 'name' => 'পারমিশন ট্রি সংরক্ষণ ও সিঙ্ক', 'key' => 'roles.matrix.manage', 'route' => 'admin.roles.matrix'],
                ]
            ],
            [
                'module_key' => 'roles',
                'module_name' => 'রোল ও এক্সেস সিকিউরিটি (Enterprise RBAC & ABAC)',
                'menu_key' => 'user_assignment_menu',
                'menu_name' => 'ইউজার রোল ও এক্সেস অ্যাসাইনমেন্ট',
                'submenu_key' => 'user_roles_assign',
                'submenu_name' => 'কর্মী ক্রেডেনশিয়াল ও স্কোপ',
                'actions' => [
                    ['action' => 'assign', 'name' => 'ইউজার তৈরি ও রোল অ্যাসাইন', 'key' => 'roles.users.assign', 'route' => 'admin.roles.user_assignment'],
                    ['action' => 'edit', 'name' => 'কর্মীর পাসওয়ার্ড পরিবর্তন (Reset)', 'key' => 'roles.users.reset_password', 'route' => 'admin.roles.user.reset_password'],
                ]
            ],
            [
                'module_key' => 'roles',
                'module_name' => 'রোল ও এক্সেস সিকিউরিটি (Enterprise RBAC & ABAC)',
                'menu_key' => 'access_simulator_menu',
                'menu_name' => 'এক্সেস সিমুলেটর ও অডিট ট্রেইল',
                'submenu_key' => 'simulator_audit',
                'submenu_name' => 'লাইভ প্রিভিউ ও নিরাপত্তা অডিট',
                'actions' => [
                    ['action' => 'view', 'name' => 'ব্যবহারকারী এক্সেস সিমুলেট ও যাচাই', 'key' => 'roles.simulator.view', 'route' => 'admin.roles.simulator'],
                    ['action' => 'export', 'name' => 'সিকিউরিটি অডিট লগ পর্যবেক্ষণ', 'key' => 'roles.audit.view', 'route' => 'admin.roles.simulator'],
                ]
            ],

            // 15. System & General Settings
            [
                'module_key' => 'settings',
                'module_name' => 'সিস্টেম ও জেনারেল সেটিংস (System Settings)',
                'menu_key' => 'general_settings',
                'menu_name' => 'কোম্পানি সেটিংস',
                'submenu_key' => 'site_config',
                'submenu_name' => 'লোগো, নাম ও কারেন্সি',
                'actions' => [
                    ['action' => 'view', 'name' => 'সিস্টেম সেটিংস দেখা', 'key' => 'settings.general.view', 'route' => 'setting.index'],
                    ['action' => 'settings', 'name' => 'সিস্টেম সেটিংস পরিবর্তন', 'key' => 'settings.general.edit', 'route' => 'setting.index'],
                    ['action' => 'create', 'name' => 'প্যাকেজ ও মেম্বারশিপ প্ল্যান তৈরি', 'key' => 'settings.packages.manage', 'route' => 'admin.our_packages.index'],
                    ['action' => 'edit', 'name' => 'হোম স্লাইডার ও ব্যানার ম্যানেজ', 'key' => 'settings.sliders.manage', 'route' => 'setting.slider'],
                    ['action' => 'settings', 'name' => 'পেমেন্ট গেটওয়ে কনফিগারেশন', 'key' => 'settings.gateways.manage', 'route' => 'admin.payment.index'],
                ]
            ],

            // 16. Photo Gallery Management
            [
                'module_key' => 'gallery',
                'module_name' => 'ফটো গ্যালারি ম্যানেজমেন্ট (Photo Gallery)',
                'menu_key' => 'gallery_management',
                'menu_name' => 'গ্যালারি অ্যালবাম',
                'submenu_key' => 'all_photos',
                'submenu_name' => 'ছবি আপলোড ও প্রদর্শন',
                'actions' => [
                    ['action' => 'view', 'name' => 'গ্যালারি ছবি দেখা', 'key' => 'gallery.view', 'route' => 'admin.gallery.index'],
                    ['action' => 'create', 'name' => 'একাধিক ছবি আপলোড করা', 'key' => 'gallery.create', 'route' => 'admin.gallery.store'],
                    ['action' => 'edit', 'name' => 'ছবির বিবরণ ও ক্যাটাগরি সম্পাদন', 'key' => 'gallery.edit', 'route' => 'admin.gallery.update'],
                    ['action' => 'delete', 'name' => 'ছবি মুছে ফেলা', 'key' => 'gallery.delete', 'route' => 'admin.gallery.destroy'],
                ]
            ],

            // 17. Theme & Color Customizer
            [
                'module_key' => 'settings',
                'module_name' => 'সিস্টেম ও জেনারেল সেটিংস (System Settings)',
                'menu_key' => 'theme_customizer_menu',
                'menu_name' => 'কালার ও থিম সেটিংস',
                'submenu_key' => 'theme_colors',
                'submenu_name' => 'ন্যাভবার, বাটন ও উইজেট কালার',
                'actions' => [
                    ['action' => 'view', 'name' => 'থিম সেটিংস দেখা', 'key' => 'settings.theme.view', 'route' => 'admin.settings.theme.index'],
                    ['action' => 'settings', 'name' => 'থিম ও কালার পরিবর্তন সংরক্ষণ', 'key' => 'settings.theme.edit', 'route' => 'admin.settings.theme.update'],
                    ['action' => 'delete', 'name' => 'ডিফল্ট থিমে রিসেট', 'key' => 'settings.theme.reset', 'route' => 'admin.settings.theme.reset'],
                ]
            ],

            // 18. Blog & Publication Management
            [
                'module_key' => 'blogs',
                'module_name' => 'ব্লগ ও পাবলিকেশন (Blog Management)',
                'menu_key' => 'blog_posts_menu',
                'menu_name' => 'ব্লগ পোস্ট ও ক্যাটাগরি',
                'submenu_key' => 'all_blogs',
                'submenu_name' => 'ব্লগ প্রকাশনা ও ব্যবস্থাপনা',
                'actions' => [
                    ['action' => 'view', 'name' => 'সকল ব্লগ পোস্ট দেখা', 'key' => 'blogs.view', 'route' => 'admin.blogs.index'],
                    ['action' => 'create', 'name' => 'নতুন ব্লগ পোস্ট লিখুন', 'key' => 'blogs.create', 'route' => 'admin.blogs.create'],
                    ['action' => 'edit', 'name' => 'ব্লগ পোস্ট সম্পাদনা', 'key' => 'blogs.edit', 'route' => 'admin.blogs.edit'],
                    ['action' => 'delete', 'name' => 'ব্লগ পোস্ট মুছে ফেলা', 'key' => 'blogs.delete', 'route' => 'admin.blogs.destroy'],
                    ['action' => 'manage_categories', 'name' => 'ব্লগ ক্যাটাগরি পরিচালনা', 'key' => 'blogs.categories.manage', 'route' => 'admin.blogs.categories.index'],
                ]
            ],
        ];
    }

    /**
     * Synchronize Master Permission Catalog with `permission_registries` and `rbac_permissions`
     */
    public static function syncRegistry(): array
    {
        $catalog = self::getMasterCatalog();
        $syncedCount = 0;
        $newCreatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($catalog as $item) {
                foreach ($item['actions'] as $act) {
                    $permKey = $act['key'];

                    // 1. Sync or Create in permission_registries
                    $registry = PermissionRegistry::firstOrNew(['permission_key' => $permKey]);
                    $isNew = !$registry->exists;

                    $registry->module_key = $item['module_key'];
                    $registry->module_name = $item['module_name'];
                    $registry->menu_key = $item['menu_key'];
                    $registry->menu_name = $item['menu_name'];
                    $registry->submenu_key = $item['submenu_key'];
                    $registry->submenu_name = $item['submenu_name'];
                    $registry->action_key = $act['action'];
                    $registry->action_name = $act['name'];
                    $registry->route_name = $act['route'] ?? null;
                    $registry->description = "{$item['module_name']} > {$item['menu_name']} > {$act['name']}";
                    $registry->is_active = true;
                    $registry->is_retired = false;
                    $registry->save();

                    // 2. Sync or Create in rbac_permissions for backward-compatible Role relations
                    $rbac = RbacPermission::firstOrNew(['slug' => $permKey]);
                    if (!$rbac->exists) {
                        $rbac->code = $permKey;
                        $newCreatedCount++;
                    }
                    $rbac->name = $act['name'];
                    $rbac->module = $item['module_name'];
                    $rbac->menu = $item['menu_name'];
                    $rbac->submenu = $item['submenu_name'];
                    $rbac->action = $act['action'];
                    $rbac->guard_name = 'web';
                    $rbac->description = $registry->description;
                    $rbac->is_active = true;
                    $rbac->is_retired = false;
                    $rbac->save();

                    $syncedCount++;
                }
            }

            // Invalidate cache
            self::clearPermissionCache();

            // Record audit log
            self::logAudit('registry', 0, 'Registry System', 'registry_synced', [
                'total_synced' => $syncedCount,
                'newly_created' => $newCreatedCount,
            ], 'Automatic Enterprise Registry Sync');

            DB::commit();

            return [
                'success' => true,
                'total_synced' => $syncedCount,
                'new_created' => $newCreatedCount,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build Hierarchical Permission Tree for a given Role (Module -> Menu -> Submenu -> Actions)
     */
    public static function getHierarchicalTree(?Role $role = null): array
    {
        $registries = PermissionRegistry::active()->orderBy('id')->get();
        $assignedKeys = [];

        if ($role) {
            if ($role->slug === 'super_admin') {
                $assignedKeys = $registries->pluck('permission_key')->toArray();
            } else {
                $assignedKeys = $role->permissions->pluck('slug')->toArray();
            }
        }

        $tree = [];

        foreach ($registries as $item) {
            $mKey = $item->module_key;
            $menuKey = $item->menu_key;
            $subKey = $item->submenu_key ?: 'general';

            if (!isset($tree[$mKey])) {
                $tree[$mKey] = [
                    'module_key' => $mKey,
                    'module_name' => $item->module_name,
                    'total_count' => 0,
                    'granted_count' => 0,
                    'menus' => [],
                ];
            }

            if (!isset($tree[$mKey]['menus'][$menuKey])) {
                $tree[$mKey]['menus'][$menuKey] = [
                    'menu_key' => $menuKey,
                    'menu_name' => $item->menu_name,
                    'total_count' => 0,
                    'granted_count' => 0,
                    'submenus' => [],
                ];
            }

            if (!isset($tree[$mKey]['menus'][$menuKey]['submenus'][$subKey])) {
                $tree[$mKey]['menus'][$menuKey]['submenus'][$subKey] = [
                    'submenu_key' => $subKey,
                    'submenu_name' => $item->submenu_name ?: $item->menu_name,
                    'total_count' => 0,
                    'granted_count' => 0,
                    'actions' => [],
                ];
            }

            $isGranted = in_array($item->permission_key, $assignedKeys);

            $actionData = [
                'id' => $item->id,
                'permission_key' => $item->permission_key,
                'action_key' => $item->action_key,
                'action_name' => $item->action_name,
                'route_name' => $item->route_name,
                'description' => $item->description,
                'is_granted' => $isGranted,
            ];

            $tree[$mKey]['total_count']++;
            $tree[$mKey]['menus'][$menuKey]['total_count']++;
            $tree[$mKey]['menus'][$menuKey]['submenus'][$subKey]['total_count']++;

            if ($isGranted) {
                $tree[$mKey]['granted_count']++;
                $tree[$mKey]['menus'][$menuKey]['granted_count']++;
                $tree[$mKey]['menus'][$menuKey]['submenus'][$subKey]['granted_count']++;
            }

            $tree[$mKey]['menus'][$menuKey]['submenus'][$subKey]['actions'][] = $actionData;
        }

        return $tree;
    }

    /**
     * Clear Cache for instantaneous permission updates
     */
    public static function clearPermissionCache(?int $userId = null): void
    {
        try {
            if ($userId) {
                Cache::forget("user_permissions_{$userId}");
                Cache::forget("user_roles_{$userId}");
            } else {
                Cache::forget('all_rbac_permissions');
                Cache::forget('rbac_permission_tree');
            }
        } catch (\Throwable $e) {
            // Ignore cache tag exceptions on file/database cache drivers
        }
    }

    /**
     * Record an immutable audit log entry
     */
    public static function logAudit(
        string $targetType,
        $targetId,
        string $targetName,
        string $eventType,
        array $details,
        ?string $reason = null
    ): void {
        try {
            PermissionAuditLog::create([
                'performed_by' => Auth::id(),
                'performer_name' => Auth::check() ? Auth::user()->name : 'System Scheduler',
                'target_type' => $targetType,
                'target_id' => $targetId,
                'target_name' => $targetName,
                'event_type' => $eventType,
                'details' => $details,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Request::userAgent() ?? 'CLI / Localhost',
                'reason' => $reason,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Fail safe, do not crash execution
        }
    }
}
