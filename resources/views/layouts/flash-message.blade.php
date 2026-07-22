<style>
    .reminder-box {
        width: 140px;
        min-width: 140px;
        max-width: 140px;
        /* height: 120px; */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 10px 8px;
        color: #fff;
        gap: 4px;
    }

    .reminder-box-name {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.2;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reminder-box-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .reminder-box-label {
        font-size: 12px;
        opacity: 0.9;
    }

    @keyframes blinkPulse {

        0%,
        100% {
            opacity: 1;
            box-shadow: 0 0 0 0 rgba(198, 40, 40, 0.6);
            transform: scale(1);
        }

        50% {
            opacity: 0.9;
            box-shadow: 0 0 18px 6px rgba(198, 40, 40, 0.55);
            transform: scale(1.03);
        }
    }

    .blink-today {
        animation: blinkPulse 1.3s ease-in-out infinite;
    }

    .reminder-box[data-filter] {
        cursor: pointer;
        transition: transform 0.15s ease;
    }

    .reminder-box[data-filter]:hover {
        transform: translateY(-2px);
    }

    .reminder-box.active-filter {
        outline: 3px solid #212529;
        outline-offset: 2px;
    }
</style>
@if (isset($dueSoonBills) && $dueSoonBills->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-left:5px solid #ffc107;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                {{-- Left side --}}
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:45px;"></i>
                    </div>
                    <div>
                        <h4 class="mt-3 fw-bold">Upcoming Bill Expiry</h4>
                        <p class="mb-0 text-muted">
                            <strong>{{ $dueSoonBills->count() }}</strong> bill(s) will expire soon.
                        </p>
                    </div>
                </div>

                {{-- Right side: ONE box per category, clickable to filter the table --}}
                <div class="d-flex flex-wrap gap-3">
                    @php
                        $grouped = $dueSoonBills
                            ->map(function ($bill) {
                                $bill->days_left = \Carbon\Carbon::today()->diffInDays(
                                    \Carbon\Carbon::parse($bill->end_date),
                                    false,
                                );
                                return $bill;
                            })
                            ->groupBy(function ($bill) {
                                if ($bill->days_left <= 0) {
                                    return 'today';
                                }
                                if ($bill->days_left == 1) {
                                    return 'oneday';
                                }
                                return 'twoday';
                            });

                        $categories = [
                            'today' => [
                                'label' => 'Today',
                                'bg' => 'linear-gradient(135deg,#ef5350,#c62828)',
                                'blink' => 'blink-today',
                            ],
                            'oneday' => [
                                'label' => '1 Day',
                                'bg' => 'linear-gradient(135deg,#ff9800,#ef6c00)',
                                'blink' => '',
                            ],
                            'twoday' => [
                                'label' => '2 Days',
                                'bg' => 'linear-gradient(135deg,#fdd835,#f9a825)',
                                'blink' => '',
                            ],
                        ];
                    @endphp

                    @foreach ($categories as $key => $cat)
                        @if (isset($grouped[$key]))
                            <div class="reminder-box rounded-3 shadow {{ $cat['blink'] }}"
                                style="background: {{ $cat['bg'] }};" data-filter="{{ $key }}"
                                onclick="filterBillsTable('{{ $key }}', this)">
                                <div class="reminder-box-name">
                                    {{ $cat['label'] }}
                                </div>
                                <div class="reminder-box-value">
                                    {{ $grouped[$key]->count() }}
                                </div>
                                <div class="reminder-box-label">
                                    Bill(s) &bull; click to view
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endif

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <!-- <button type="button" class="close" data-dismiss="alert" id="dismiss">×</button>	 -->
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <!-- <button type="button" class="close" data-dismiss="alert" id="dismiss">×</button>	 -->
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block">
        <!-- <button type="button" class="close" data-dismiss="alert" id="dismiss">×</button>	 -->
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
        <!-- <button type="button" class="close" data-dismiss="alert" id="dismiss">×</button>	 -->
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($errors->any())
    <div class="alert alert-danger">
        <!-- <button type="button" class="close" data-dismiss="alert" id="dismiss">×</button>	 -->
        Please check the form below for errors
    </div>
@endif
