<div class="container{{ !isset($is_forced_aligned_center) || $is_forced_aligned_center ? '-fluid' : '' }}">
    <div class="row">
        <div class="col-md-12 text-center">
        	<div>{{ $introducing_text or 'Powered By' }}</div>
            <div class="footer_logo wow fadeInUp animated">
                <a href="{{ isset($company_portfolio) ? $company_portfolio : env('APP_POWER_OWNER_PORTFOLIO') }}" target="_blank"> <img src="{{ isset($company_logo) ? $company_logo : env('APP_POWER_OWNER_LOGO')  }}" alt="Alternative System & Development Technology Ltd."> </a>
            </div>
        </div>
    </div>
</div>