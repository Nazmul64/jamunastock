<?php

namespace App\Http\Controllers;

use App\Models\CardBenefit;
use App\Models\OurPackage;
use App\Models\Stock;
use App\Models\StockGallery;
use App\Models\StockPrice;
use Illuminate\Http\Request;

class PublicSearchController extends Controller
{
    /**
     * Public full search results page
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('search', ''));

        $stocks = collect();
        $packages = collect();
        $cards = collect();

        if ($keyword !== '') {
            $stocks = Stock::where('status', 'active')
                ->where(function ($q) use ($keyword) {
                    $q->where('stock_name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->latest()
                ->get();

            $packages = OurPackage::where('status', 1)
                ->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->latest()
                ->get();

            $cards = CardBenefit::where('status', 1)
                ->where(function ($q) use ($keyword) {
                    $q->where('card_name', 'like', "%{$keyword}%")
                      ->orWhere('card_type', 'like', "%{$keyword}%")
                      ->orWhere('short_description', 'like', "%{$keyword}%");
                })
                ->latest()
                ->get();
        }

        return view('frontend.search', compact('keyword', 'stocks', 'packages', 'cards'));
    }

    /**
     * AJAX Live Search Endpoint for Dropdown suggestions
     */
    public function liveSearch(Request $request)
    {
        $keyword = trim($request->input('q') ?: $request->input('search', ''));

        if (mb_strlen($keyword) < 1) {
            return response()->json([]);
        }

        $results = [];

        // 1. Search Stocks
        $stocks = Stock::where('status', 'active')
            ->where(function ($q) use ($keyword) {
                $q->where('stock_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->take(5)
            ->get();

        foreach ($stocks as $stock) {
            $gallery = StockGallery::where('stock_id', $stock->id)->first();
            $img = $gallery && $gallery->image ? asset('upload/stock_images/' . $gallery->image) : asset('upload/images/backend/logo/' . setting('logo'));
            $price = StockPrice::where('stock_id', $stock->id)->latest()->first();
            $priceText = $price ? 'BDT ' . number_format($price->selling_price) : '';

            $results[] = [
                'type' => 'Stock',
                'badge_class' => 'bg-success',
                'title' => $stock->stock_name,
                'subtitle' => $stock->stock_quantity ? 'Available: ' . $stock->stock_quantity : 'Live Market',
                'price' => $priceText,
                'image' => $img,
                'url' => route('stock.detials', $stock->id),
            ];
        }

        // 2. Search Packages
        $packages = OurPackage::where('status', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->take(3)
            ->get();

        foreach ($packages as $pkg) {
            $img = $pkg->image ? asset($pkg->image) : asset('upload/images/backend/logo/' . setting('logo'));
            $results[] = [
                'type' => 'Package',
                'badge_class' => 'bg-warning text-dark',
                'title' => $pkg->title ?: 'Membership Package',
                'subtitle' => 'Our Packages',
                'price' => '',
                'image' => $img,
                'url' => route('/') . '#our-packages',
            ];
        }

        // 3. Search Cards
        $cards = CardBenefit::where('status', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('card_name', 'like', "%{$keyword}%")
                  ->orWhere('card_type', 'like', "%{$keyword}%")
                  ->orWhere('short_description', 'like', "%{$keyword}%");
            })
            ->take(3)
            ->get();

        foreach ($cards as $card) {
            $img = $card->image ? asset($card->image) : asset('upload/images/backend/logo/' . setting('logo'));
            $results[] = [
                'type' => 'Card',
                'badge_class' => 'bg-primary',
                'title' => $card->card_name,
                'subtitle' => $card->card_type ?: 'Membership Card',
                'price' => $card->card_fee ? 'Fee: BDT ' . (is_numeric($card->card_fee) ? number_format((float)$card->card_fee) : $card->card_fee) : '',
                'image' => $img,
                'url' => route('/') . '#card-benefits',
            ];
        }

        return response()->json($results);
    }

    /**
     * Public Stock Market page
     */
    public function stocks(Request $request)
    {
        $query = Stock::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('stock_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $all_stocks = $query->latest()->paginate(12);

        return view('frontend.stocks', compact('all_stocks'));
    }
}
