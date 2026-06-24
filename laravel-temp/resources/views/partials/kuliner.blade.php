    <section class="section" id="kuliner">
        <div class="section-head reveal">
            <div class="section-tag">Kuliner Khas</div>
            <h2 class="section-title">Cicipi rasa <b>otentik</b> Lampung</h2>
            <p class="section-sub">Dari Seruit legendaris hingga Kopi Lampung yang mendunia. Temukan warung terbaik di
                dekat destinasi.</p>
        </div>

        <div class="foods">
            @forelse($culinaries as $food)
            <a class="food reveal" href="{{ route('culinary.show', $food->id) }}" style="text-decoration:none;color:inherit;display:block;cursor:pointer;">
                <div class="food-img" style="background-image: url('{{ str_starts_with($food->image_path ?? '', 'http') || str_starts_with($food->image_path ?? '', 'images/') ? asset($food->image_path) : asset('storage/' . $food->image_path) }}'); background-size: cover; background-position: center;">
                    <span class="food-cat">{{ $food->category }}</span>
                </div>
                <div class="food-body">
                    <div class="food-name">{{ $food->name }}</div>
                    <div class="food-desc">{{ $food->description }}</div>
                    <div class="food-meta">
                        <div class="spice" title="Tingkat Pedas: {{ $food->spice_level }}/5">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="spice-bar {{ $i <= $food->spice_level ? 'on' : '' }}"></div>
                            @endfor
                        </div>
                        <span class="food-near"><strong>{{ $food->outlet_count }}</strong> {{ $food->outlet_type }} terdekat</span>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-light);">
                <p>Belum ada kuliner khas yang terdaftar.</p>
            </div>
            @endforelse
        </div>
    </section>
