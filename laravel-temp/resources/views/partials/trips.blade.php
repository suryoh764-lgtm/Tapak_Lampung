    <section class="section trips-bg" id="trips">
        <div class="section-head reveal">
            <div class="section-tag">Open Trip</div>
            <h2 class="section-title">Pilih paket <b>trip</b> favoritmu</h2>
            <p class="section-sub">Bergabung dengan wisatawan lain dalam paket open trip dari organizer terverifikasi.
            </p>
        </div>

        <div class="trips">
            @forelse($trips as $trip)
            @php
                $imgUrl = str_starts_with($trip->image_path ?? '', 'http') || str_starts_with($trip->image_path ?? '', 'images/')
                    ? asset($trip->image_path)
                    : asset('storage/' . $trip->image_path);
                $slotsLeft = $trip->max_quota - $trip->current_quota;
                $avatarColors = ['var(--primary)', 'var(--coral)', 'var(--gold)', '#8b5cf6', '#06b6d4'];
                $colorIdx = $loop->index % count($avatarColors);
            @endphp
            <div class="trip reveal">
                <div class="trip-img"
                    style="background-image: url('{{ $imgUrl }}'); background-size: cover; background-position: center;">
                    <div class="trip-org">
                        <div class="trip-org-avatar" style="background: {{ $avatarColors[$colorIdx] }};">{{ $trip->organizer_avatar }}</div>
                        <span class="trip-org-name">{{ $trip->organizer_name }}</span>
                        <svg>
                            <use href="#s-check" />
                        </svg>
                    </div>
                </div>
                <div class="trip-body">
                    <div class="trip-tags">
                        @foreach($trip->tags as $tag)
                        <span class="trip-tag">{{ $tag->tag }}</span>
                        @endforeach
                    </div>
                    <div class="trip-name">{{ $trip->name }}</div>
                    <div class="trip-desc">{{ $trip->description }}</div>
                    <div class="trip-details">
                        <div class="trip-detail">
                            <div class="trip-detail-icon"><svg><use href="#s-cal" /></svg></div>
                            <div class="trip-detail-text">
                                <span class="d-label">Jadwal</span>
                                <span class="d-value">{{ \Carbon\Carbon::parse($trip->schedule_date)->locale('id')->isoFormat('D MMM YYYY') }}</span>
                            </div>
                        </div>
                        <div class="trip-detail">
                            <div class="trip-detail-icon"><svg><use href="#s-users" /></svg></div>
                            <div class="trip-detail-text">
                                <span class="d-label">Kuota</span>
                                <span class="d-value">{{ $trip->current_quota }}/{{ $trip->max_quota }} peserta</span>
                            </div>
                        </div>
                        <div class="trip-detail">
                            <div class="trip-detail-icon"><svg><use href="#s-clock" /></svg></div>
                            <div class="trip-detail-text">
                                <span class="d-label">Durasi</span>
                                <span class="d-value">{{ $trip->duration }}</span>
                            </div>
                        </div>
                        <div class="trip-detail">
                            <div class="trip-detail-icon"><svg><use href="#s-star" /></svg></div>
                            <div class="trip-detail-text">
                                <span class="d-label">Rating</span>
                                <span class="d-value">{{ $trip->rating }} ({{ $trip->reviews_count }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="trip-bottom">
                        <div>
                            <div class="trip-price-label">Mulai dari</div>
                            <span class="trip-price-amount">Rp {{ number_format($trip->price / 1000, 0) }}K</span>
                            <span class="trip-price-unit">/orang</span>
                        </div>
                        @if($slotsLeft > 0)
                        <a href="{{ route('trips.book', $trip->id) }}" class="trip-book">Pesan</a>
                        @else
                        <button class="trip-book" disabled style="opacity:.5;cursor:not-allowed;">Penuh</button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada trip tersedia.</p>
            @endforelse
        </div>
    </section>
