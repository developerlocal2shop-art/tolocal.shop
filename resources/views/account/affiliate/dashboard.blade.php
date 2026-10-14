@extends('layouts.client.vendor')
@section('title', 'Affiliate Dashboard')

@section('content')
<style>
    .affiliate-dashboard-shell {
        background: linear-gradient(180deg, #f7faff 0%, #edf3ff 100%);
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 18px 45px rgba(32, 56, 100, 0.08);
    }

    .affiliate-dashboard-card {
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .stat-card {
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.03);
        height: 100%;
    }

    .stat-card .card-body {
        padding: 22px 20px;
    }

    .stat-label {
        color: #6b7a90;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: #1c2d4d;
        margin: 0;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.1rem;
    }

    .bg-soft-blue { background: rgba(59, 130, 246, 0.1); color: #1d4ed8; }
    .bg-soft-green { background: rgba(34, 197, 94, 0.1); color: #15803d; }
    .bg-soft-purple { background: rgba(168, 85, 247, 0.12); color: #7c3aed; }
    .bg-soft-gold { background: rgba(245, 158, 11, 0.12); color: #b45309; }

    .activity-table th,
    .activity-table td {
        vertical-align: middle;
        padding: 0.85rem 0.9rem;
        border-color: #edf2f7;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .status-approved {
        background: rgba(34, 197, 94, 0.12);
        color: #15803d;
    }

    .status-pending {
        background: rgba(251, 146, 60, 0.14);
        color: #ff9d7f;
        border: 1px solid rgba(251, 146, 60, 0.45);
    }

    .affiliate-code-box {
        background: linear-gradient(135deg, #203864 0%, #2d5ea8 100%);
        color: #fff;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 15px 35px rgba(32, 56, 100, 0.22);
    }

    .affiliate-code-box small {
        color: rgba(255,255,255,0.75);
        display: block;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .affiliate-code-box strong {
        font-size: 1.65rem;
        letter-spacing: 0.06em;
    }

    .dashboard-actions .btn {
        border-radius: 12px;
        padding: 12px 18px;
        font-weight: 600;
    }

    .affiliate-link-field {
        min-width: 0;
    }

    .affiliate-product-select {
        min-height: 44px;
        border-radius: 8px;
    }

    .affiliate-link-url {
        min-width: 260px;
        color: #203864;
        font-size: 13px;
    }

    .product-search-wrapper {
        position: relative;
    }

    .product-search-suggestions {
        position: absolute;
        z-index: 20;
        top: 100%;
        right: 0;
        left: 0;
        max-height: 280px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #d9e0eb;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
    }

    .product-search-suggestions button {
        display: block;
        width: 100%;
        padding: 10px 12px;
        text-align: left;
        color: #26364f;
        background: #fff;
        border: 0;
    }

    .product-search-suggestions button:hover,
    .product-search-suggestions button:focus {
        background: #f1f5f9;
        outline: none;
    }

    .product-search-suggestions [role="status"] {
        padding: 10px 12px;
        color: #64748b;
    }
</style>

<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9">
                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header">
                        <div class="container">
                            <h1>Affiliate Dashboard</h1>
                        </div>
                    </div>
                </div>

                @if (session('info'))
                    <div class="alert alert-info mt-3">{{ session('info') }}</div>
                @endif

                <div class="affiliate-dashboard-shell mt-3">
                    <div class="row g-4 align-items-stretch mb-4">
                        <div class="col-lg-4">
                            <div class="affiliate-code-box h-100">
                                <small>Affiliate Code</small>
                                <strong>{{ $affiliate->affiliate_code }}</strong>
                                <div class="mt-3 d-flex align-items-center justify-content-between">
                                    <span class="status-pill {{ $affiliate->status === 'approved' ? 'status-approved' : 'status-pending' }}">
                                        {{ ucfirst($affiliate->status) }}
                                    </span>
                                    <span class="small opacity-75">{{ ucfirst($affiliate->commission_type) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="affiliate-dashboard-card h-100 p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                    <div>
                                        <div class="stat-label mb-1">Program Overview</div>
                                        <h4 class="mb-0">Affiliate performance</h4>
                                    </div>
                                    <div class="dashboard-actions">
                                        @if($affiliate)

                                        @else
                                            <a href="{{ route('affiliate.register') }}" class="btn btn-primary">Join Affiliate Program</a>
                                        @endif
                                    </div>
                                </div>

                                <p class="mb-0 text-muted">
                                    @if($affiliate->status === 'approved')
                                        Your affiliate profile is active and ready to track conversions. Use the dashboard below to monitor clicks, qualified sales, and payouts.
                                    @elseif($affiliate->status === 'pending')
                                        Your affiliate request is under review. You’ll be able to access the full dashboard after approval.
                                    @else
                                        Join the affiliate program to start earning commissions and track your performance.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($affiliate->status === 'approved')
                        <section class="affiliate-dashboard-card p-4 mb-4" aria-labelledby="affiliate-links-heading">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div>
                                    <div class="stat-label mb-1">Product Promotion</div>
                                    <h4 id="affiliate-links-heading" class="mb-0">Create an affiliate link</h4>
                                </div>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success" role="status">{{ session('success') }}</div>
                            @endif

                            @if(session('generated_link'))
                                <div class="mb-4">
                                    <label class="form-label fw-bold" for="generated-affiliate-link">Your new link</label>
                                    <div class="input-group">
                                        <input id="generated-affiliate-link" class="form-control affiliate-link-url" type="text" value="{{ session('generated_link') }}" readonly>
                                        <button class="btn btn-outline-primary" type="button" data-copy-target="generated-affiliate-link">Copy link</button>
                                    </div>
                                </div>
                            @endif

                            <form method="GET" action="{{ route('affiliate.dashboard') }}" class="row g-2 mb-3">
                                <div class="col-md-9">
                                    <label class="sr-only" for="product-search">Search products</label>
                                    <div class="product-search-wrapper">
                                        <input id="product-search" class="form-control" type="search" name="product_search" value="{{ request('product_search') }}" placeholder="Search vendor products" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="product-search-suggestions" data-suggestions-url="{{ route('affiliate.products.suggestions') }}">
                                        <div id="product-search-suggestions" class="product-search-suggestions" role="listbox" hidden></div>
                                    </div>
                                </div>
                                <div class="col-md-3 d-grid">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </form>

                            @if($products->isNotEmpty())
                                <form method="POST" action="{{ route('affiliate.links.store') }}" class="row g-2 align-items-end">
                                    @csrf
                                    <input type="hidden" name="product_search" value="{{ request('product_search') }}">
                                    <div class="col-md-9">
                                        <label class="form-label fw-bold" for="affiliate-product">Vendor product</label>
                                        <select id="affiliate-product" class="form-control affiliate-product-select" name="product_id" required>
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-grid">
                                        <button class="btn btn-primary" type="submit">Generate link</button>
                                    </div>
                                </form>
                                @if($products->hasPages())
                                    <div class="mt-3">{{ $products->links() }}</div>
                                @endif
                            @else
                                <p class="text-muted mb-0">No eligible products found.</p>
                            @endif
                        </section>

                        <section class="affiliate-dashboard-card mb-4" aria-labelledby="generated-links-heading">
                            <div class="card-header bg-white border-0 py-3 px-4">
                                <h5 id="generated-links-heading" class="mb-0 fw-bold">Your product links</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table activity-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Affiliate link</th>
                                            <th>Clicks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($affiliateLinks as $affiliateLink)
                                            <tr>
                                                <td>{{ optional($affiliateLink->product)->name ?? 'Product unavailable' }}</td>
                                                <td>
                                                    <div class="input-group affiliate-link-field">
                                                        <input id="affiliate-link-{{ $affiliateLink->id }}" class="form-control affiliate-link-url" type="text" value="{{ $affiliateLink->url }}" readonly>
                                                        <button class="btn btn-outline-primary" type="button" data-copy-target="affiliate-link-{{ $affiliateLink->id }}">Copy</button>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($affiliateLink->clicks) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">No affiliate links generated yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if (window.jQuery && jQuery.fn.select2) {
                                    jQuery('#affiliate-product').select2({
                                        width: '100%',
                                        placeholder: 'Select a product',
                                        allowClear: true
                                    });
                                }

                                var productSearch = document.getElementById('product-search');
                                var suggestions = document.getElementById('product-search-suggestions');
                                if (!productSearch || !suggestions) return;

                                var searchTimer;
                                var activeRequest;

                                function closeSuggestions() {
                                    suggestions.hidden = true;
                                    productSearch.setAttribute('aria-expanded', 'false');
                                }

                                function showMessage(message) {
                                    var item = document.createElement('div');
                                    item.setAttribute('role', 'status');
                                    item.textContent = message;
                                    suggestions.replaceChildren(item);
                                    suggestions.hidden = false;
                                    productSearch.setAttribute('aria-expanded', 'true');
                                }

                                productSearch.addEventListener('input', function () {
                                    window.clearTimeout(searchTimer);
                                    if (activeRequest) activeRequest.abort();

                                    var query = productSearch.value.trim();
                                    if (query.length < 2) {
                                        closeSuggestions();
                                        return;
                                    }

                                    searchTimer = window.setTimeout(function () {
                                        activeRequest = new AbortController();
                                        var url = productSearch.dataset.suggestionsUrl + '?q=' + encodeURIComponent(query);

                                        fetch(url, {
                                            headers: { 'Accept': 'application/json' },
                                            signal: activeRequest.signal
                                        })
                                            .then(function (response) {
                                                if (!response.ok) throw new Error('Product suggestions unavailable');
                                                return response.json();
                                            })
                                            .then(function (products) {
                                                suggestions.replaceChildren();
                                                if (!products.length) {
                                                    showMessage('No matching products');
                                                    return;
                                                }

                                                products.forEach(function (product) {
                                                    var option = document.createElement('button');
                                                    option.type = 'button';
                                                    option.setAttribute('role', 'option');
                                                    option.textContent = product.name;
                                                    option.addEventListener('click', function () {
                                                        productSearch.value = product.name;
                                                        closeSuggestions();
                                                        productSearch.focus();
                                                    });
                                                    suggestions.appendChild(option);
                                                });

                                                suggestions.hidden = false;
                                                productSearch.setAttribute('aria-expanded', 'true');
                                            })
                                            .catch(function (error) {
                                                if (error.name !== 'AbortError') closeSuggestions();
                                            });
                                    }, 250);
                                });

                                productSearch.addEventListener('keydown', function (event) {
                                    if (event.key === 'ArrowDown' && !suggestions.hidden) {
                                        var firstOption = suggestions.querySelector('[role="option"]');
                                        if (firstOption) {
                                            event.preventDefault();
                                            firstOption.focus();
                                        }
                                    } else if (event.key === 'Escape') {
                                        closeSuggestions();
                                    }
                                });

                                document.addEventListener('click', function (event) {
                                    if (!event.target.closest('.product-search-wrapper')) closeSuggestions();
                                });
                            });

                            document.addEventListener('click', function (event) {
                                var button = event.target.closest('[data-copy-target]');
                                if (!button) return;

                                var input = document.getElementById(button.dataset.copyTarget);
                                if (!input) return;

                                input.select();
                                input.setSelectionRange(0, input.value.length);
                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(input.value);
                                } else {
                                    document.execCommand('copy');
                                }

                                button.textContent = 'Copied';
                                window.setTimeout(function () {
                                    button.textContent = button.dataset.copyTarget === 'generated-affiliate-link' ? 'Copy link' : 'Copy';
                                }, 1500);
                            });
                        </script>
                    @endif

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="stat-label">Clicks</div>
                                            <p class="stat-value">{{ number_format($stats['clicks']) }}</p>
                                        </div>
                                        <div class="stat-icon bg-soft-blue"><i class="fa fa-mouse-pointer"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="stat-label">Orders</div>
                                            <p class="stat-value">{{ number_format($stats['qualified_orders']) }}</p>
                                        </div>
                                        <div class="stat-icon bg-soft-green"><i class="fa fa-shopping-cart"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="stat-label">Rate</div>
                                            <p class="stat-value">{{ number_format($stats['commission_rate'], 2) }}%</p>
                                        </div>
                                        <div class="stat-icon bg-soft-purple"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="stat-label">Wallet</div>
                                            <p class="stat-value">kr{{ number_format($stats['wallet_balance'], 2) }}</p>
                                        </div>
                                        <div class="stat-icon bg-soft-gold"><i class="fa fa-wallet"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="affiliate-dashboard-card h-100">
                                <div class="card-header bg-white border-0 py-3 px-4">
                                    <h5 class="mb-0 fw-bold">Recent activity</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table activity-table mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($recentConversions->isNotEmpty())
                                                    @foreach($recentConversions as $conversion)
                                                        <tr>
                                                            <td>Conversion</td>
                                                            <td>#{{ $conversion->id }}</td>
                                                            <td>
                                                                <span class="status-pill {{ $conversion->status === 'approved' ? 'status-approved' : 'status-pending' }}">
                                                                    {{ ucfirst($conversion->status) }}
                                                                </span>
                                                            </td>
                                                            <td>£{{ number_format((float) $conversion->paid_amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-4">No conversions yet. Start sharing your unique affiliate code to generate sales.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="affiliate-dashboard-card h-100 p-4">
                                <div class="stat-label">Summary</div>
                                <div class="mb-4">
                                    <div class="small text-muted">Pending payouts</div>
                                    <h3 class="mb-0">{{ number_format($stats['pending_payouts']) }}</h3>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted">Qualified orders</div>
                                    <h3 class="mb-0">{{ number_format($stats['qualified_orders']) }}</h3>
                                </div>
                                <div>
                                    <div class="small text-muted">Total earnings</div>
                                    <h3 class="mb-0">£{{ number_format($stats['total_earnings'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
