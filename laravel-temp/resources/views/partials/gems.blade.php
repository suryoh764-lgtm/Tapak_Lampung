    <section class="section" id="gems">
        <div class="section-head reveal">
            <div class="section-tag">Hidden Gems</div>
            <h2 class="section-title">Temukan <b>permata tersembunyi</b> Lampung</h2>
            <p class="section-sub">Destinasi indah yang belum banyak diketahui wisatawan. Dari pantai privat hingga air
                terjun di tengah hutan.</p>
        </div>

        <div class="gems">
            @forelse($destinations as $dest)
            <a href="{{ route('destinations.show', $dest->id) }}" class="gem reveal" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                <div class="gem-img"
                    style="background-image: url('{{ str_starts_with($dest->image_path, 'http') || str_starts_with($dest->image_path, 'images/') ? asset($dest->image_path) : asset('storage/' . $dest->image_path) }}'); background-size: cover; background-position: center;">
                    <span class="gem-label {{ $dest->label === 'Surfing' ? 'red' : ($dest->label === 'Populer' ? 'accent' : 'green') }}">
                        {{ $dest->label ?? 'Hidden Gem' }}
                    </span>
                </div>
                <div class="gem-body">
                    <div class="gem-loc">{{ $dest->location }}</div>
                    <div class="gem-name">{{ $dest->name }}</div>
                    <div class="gem-desc">{{ $dest->description }}</div>
                    <div class="gem-footer">
                        <span class="gem-stat star"><svg>
                                <use href="#s-star" />
                            </svg> {{ number_format($dest->rating, 1) }}</span>
                        <span class="gem-stat"><svg>
                                <use href="#s-heart" />
                            </svg> {{ $dest->likes_count }}</span>
                        <span class="gem-stat"><svg>
                                <use href="#s-pin" />
                            </svg> {{ $dest->category }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-3);">
                Belum ada destinasi terdaftar.
            </div>
            @endforelse
        </div>
    </section>
