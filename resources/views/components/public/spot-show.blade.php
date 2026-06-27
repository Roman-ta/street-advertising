<div class="spot-show">

    <a href="{{ route('home') }}" class="spot-show__back">{{ __('messages.spot_show.back') }}</a>

    <div class="spot-show__layout">

        {{-- Левая часть --}}
        <div>
            <div class="spot-show__gallery">
                @if($spot->photos->isNotEmpty())
                    <img
                        src="{{ Storage::url($spot->photos->first()->path) }}"
                        alt="{{ $spot->title }}"
                        class="spot-show__main-photo"
                    >
                    @if($spot->photos->count() > 1)
                        <div class="spot-show__thumbnails">
                            @foreach($spot->photos->skip(1) as $photo)
                                <img src="{{ Storage::url($photo->path) }}" class="spot-show__thumbnail">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="spot-show__no-photo">{{ __('messages.spot_show.no_photo') }}</div>
                @endif
            </div>

            <h1 class="spot-show__title">{{ $spot->title }}</h1>
            <p class="spot-show__address">📍 {{ $spot->address }}, {{ __('messages.cities.' . $spot->city) }}</p>

            @if($spot->description)
                <div class="spot-show__description">
                    <h3>{{ __('messages.spot_show.description') }}</h3>
                    <p>{{ $spot->description }}</p>
                </div>
            @endif

            <div class="spot-show__specs">
                <h3>{{ __('messages.spot_show.specs') }}</h3>
                <div class="spot-show__specs-grid">
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_type') }}</span>
                        <p>{{ __('messages.types.' . $spot->type) }}</p>
                    </div>
                    @if($spot->size_w && $spot->size_h)
                        <div class="spot-show__spec-item">
                            <span>{{ __('messages.spot_show.spec_size') }}</span>
                            <p>{{ $spot->size_w }}×{{ $spot->size_h }} м</p>
                        </div>
                    @endif
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_traffic') }}</span>
                        <p>{{ __('messages.traffic.' . $spot->traffic) }}</p>
                    </div>
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_lighting') }}</span>
                        <p>{{ $spot->lighting ? __('messages.spot_show.lighting_yes') : __('messages.spot_show.lighting_no') }}</p>
                    </div>
                    @if($spot->file_types_allowed)
                        <div class="spot-show__spec-item spot-show__spec-item--full">
                            <span>{{ __('messages.spot_show.spec_formats') }}</span>
                            <p>{{ implode(', ', array_map('strtoupper', $spot->file_types_allowed)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Правая часть --}}
        <div>
            <div class="spot-show__booking">

                <div class="spot-show__price">
                    {{ money($spot->price_month, 0) }}
                </div>
                <div class="spot-show__price-hint">
                    {{ __('messages.spot_show.price_per_day', ['price' => money($spot->price_month / 30, 1)]) }}
                </div>

                <div class="spot-show__dates">
                    <div>
                        <label>{{ __('messages.spot_show.date_from_label') }}</label>
                        <input
                            type="date"
                            wire:model="date_from"
                            wire:change="$set('date_from', $event.target.value)"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                        >
                    </div>
                    <div>
                        <label>{{ __('messages.spot_show.date_to_label') }}</label>
                        <input
                            type="date"
                            wire:model="date_to"
                            wire:change="$set('date_to', $event.target.value)"
                            min="{{ now()->addDays(2)->format('Y-m-d') }}"
                        >
                    </div>
                </div>

                @if($error)
                    <div class="alert alert--error">{{ $error }}</div>
                @endif

                @if(!empty($occupiedDates))
                    <div class="alert alert--warning">
                        {{ __('messages.spot_show.dates_occupied') }}
                    </div>
                @endif

                @if($days > 0 && !$error)
                    <div class="spot-show__calc">
                        <div class="spot-show__calc-row">
                            <span>{{ __('messages.spot_show.days_x', ['price' => money($spot->price_month / 30, 1), 'days' => $days]) }}</span>
                            <span>{{ money($base_price, 2) }}</span>
                        </div>
                        <div class="spot-show__calc-total">
                            <span>{{ __('messages.spot_show.total') }}</span>
                            <span>{{ money($total, 2) }}</span>
                        </div>
                    </div>
                @endif

                @if($days > 0 && !$error)
                    @auth
                        <button wire:click="addToCart" class="btn btn--primary btn--full btn--lg">
                            <span wire:loading.remove>{{ __('messages.spot_show.add_to_cart') }}</span>
                            <span wire:loading>{{ __('messages.spot_show.adding') }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn--primary btn--full btn--lg">
                            {{ __('messages.spot_show.login_to_book') }}
                        </a>
                    @endauth
                @else
                    <button disabled class="btn btn--full btn--lg" style="background:#e5e7eb; color:#9ca3af; cursor:not-allowed">
                        {{ __('messages.spot_show.select_dates') }}
                    </button>
                @endif

                <p class="spot-show__hint">
                    {{ __('messages.spot_show.frozen_hint') }}
                </p>

                <div class="spot-show__owner">
                    <p>{{ __('messages.spot_show.owner') }}</p>
                    <p>{{ $spot->partner->name }}</p>
                </div>

            </div>
        </div>

    </div>
</div>
