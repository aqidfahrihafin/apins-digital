<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portofolios;
use App\Models\PortofoliosCategory;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->string('kategori')->toString();

        $portfolios = Portofolios::active()
            ->with(['category', 'images'])
            ->when($category, fn ($query) => $query->whereHas(
                'category',
                fn ($categoryQuery) => $categoryQuery->where('slug', $category),
            ))
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = PortofoliosCategory::query()
            ->whereHas('portofolios', fn ($query) => $query->active())
            ->orderBy('name')
            ->get();

        return view('portfolio.index', compact('portfolios', 'categories', 'category'));
    }

    public function show(string $slug)
    {
        $portfolio = Portofolios::active()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $portfolio->increment('view_count');

        $relatedPortfolios = Portofolios::active()
            ->with(['category', 'images'])
            ->whereKeyNot($portfolio->getKey())
            ->when($portfolio->category_id, fn ($query) => $query->where('category_id', $portfolio->category_id))
            ->latest()
            ->limit(3)
            ->get();

        return view('portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }
}
