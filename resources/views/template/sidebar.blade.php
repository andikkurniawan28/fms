<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="/fms/public/sneat/assets/img/fathania.png" alt="Logo"width="50">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">FMS</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    @php
        $role = auth()->user()->role->name ?? null;
    @endphp

    <ul class="menu-inner py-1">

        <!-- Home -->
        <li class="menu-item @yield('home_active')">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Home</div>
            </a>
        </li>

        <!-- Transaksi -->
        <li class="menu-item @yield('transaksi_active')">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Transaksi</div>
            </a>
            <ul class="menu-sub">

                {{-- ORDER (Owner & Admin) --}}
                @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('order_active')">
                        <a href="{{ route('order.index') }}" class="menu-link">
                            <div>Order</div>
                        </a>
                    </li>
                @endif

                {{-- PAYMENT (Owner & Admin) --}}
                @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('payment_active')">
                        <a href="{{ route('payment.index') }}" class="menu-link">
                            <div>Pelunasan</div>
                        </a>
                    </li>
                @endif

                {{-- PRODUCTION (Owner & Admin) --}}
                @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('production_active')">
                        <a href="{{ route('production.index') }}" class="menu-link">
                            <div>SPK</div>
                        </a>
                    </li>
                @endif

                {{-- EXPENSE JOURNAL (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('expense_journal_active')">
                        <a href="{{ route('income_journal.index') }}" class="menu-link">
                            <div>Jurnal Pemasukan</div>
                        </a>
                    </li>
                @endif

                {{-- INCOME JOURNAL (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('income_journal_active')">
                        <a href="{{ route('expense_journal.index') }}" class="menu-link">
                            <div>Jurnal Pengeluaran</div>
                        </a>
                    </li>
                @endif

            </ul>
        </li>

        <li class="menu-item @yield('laporan_active')">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Laporan</div>
            </a>
            <ul class="menu-sub">

                {{-- BUKU BESAR (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('ledger_active')">
                        <a href="{{ route('ledger.index') }}" class="menu-link">
                            <div>Buku Besar</div>
                        </a>
                    </li>
                @endif

                {{-- ARUS KAS (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('cash_flow_active')">
                        <a href="{{ route('report_cash_flow.index') }}" class="menu-link">
                            <div>Arus Kas</div>
                        </a>
                    </li>
                @endif

                {{-- LABA RUGI (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('profit_loss_active')">
                        <a href="{{ route('report_profit_loss.index') }}" class="menu-link">
                            <div>Laba Rugi</div>
                        </a>
                    </li>
                @endif

                {{-- NERACA (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('balance_sheet_active')">
                        <a href="{{ route('report_balance_sheet.index') }}" class="menu-link">
                            <div>Neraca</div>
                        </a>
                    </li>
                @endif

            </ul>
        </li>

        <!-- Master -->
        <li class="menu-item @yield('master_active')">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-folder"></i>
                <div>Master</div>
            </a>
            <ul class="menu-sub">

                {{-- USER (Owner only) --}}
                @if ($role === 'Owner')
                    <li class="menu-item @yield('user_active')">
                        <a href="{{ route('user.index') }}" class="menu-link">
                            <div>Pengguna</div>
                        </a>
                    </li>
                @endif

                {{-- CUSTOMER (Owner & Admin) --}}
                @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('customer_active')">
                        <a href="{{ route('customer.index') }}" class="menu-link">
                            <div>Customer</div>
                        </a>
                    </li>
                @endif

                {{-- CUSTOMER (Owner) --}}
                @if (in_array($role, ['Owner']))
                    <li class="menu-item @yield('account_active')">
                        <a href="{{ route('account.index') }}" class="menu-link">
                            <div>Akun</div>
                        </a>
                    </li>
                @endif

                {{-- PRODUCT CATEGORY (Owner & Admin) --}}
                {{-- @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('product_category_active')">
                        <a href="{{ route('product_category.index') }}" class="menu-link">
                            <div>Kategori Produk</div>
                        </a>
                    </li>
                @endif --}}

                {{-- PACKAGING (Owner & Admin) --}}
                {{-- @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('packaging_active')">
                        <a href="{{ route('packaging.index') }}" class="menu-link">
                            <div>Packaging</div>
                        </a>
                    </li>
                @endif --}}

                {{-- PRODUCT (Owner & Admin) --}}
                {{-- @if (in_array($role, ['Owner', 'Admin']))
                    <li class="menu-item @yield('product_active')">
                        <a href="{{ route('product.index') }}" class="menu-link">
                            <div>Produk</div>
                        </a>
                    </li>
                @endif --}}

                {{-- TERMIN (Owner only) --}}
                @if ($role === 'Owner')
                    <li class="menu-item @yield('termin_active')">
                        <a href="{{ route('termin.index') }}" class="menu-link">
                            <div>Termin</div>
                        </a>
                    </li>
                @endif

            </ul>
        </li>

    </ul>
</aside>
