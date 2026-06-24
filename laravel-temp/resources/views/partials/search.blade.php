    <form action="{{ route('search') }}" method="GET" class="search-wrap" style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 0 auto; text-align: center;">
        <div class="search-box" style="margin: 0 auto; width: 100%; max-width: 600px;">
            <div class="search-input-area">
                <svg>
                    <use href="#s-search" />
                </svg>
                <input type="text" name="q" placeholder="Cari nama destinasi, open trip, lokasi...">
            </div>
            <button type="submit" class="search-go">Cari</button>
        </div>
        <div class="search-filters" style="justify-content: center; flex-wrap: wrap;">
            <button type="submit" name="category" value="Semua" class="filter-chip active">Semua</button>
            <button type="submit" name="category" value="Pantai" class="filter-chip">Pantai & Laut</button>
            <button type="submit" name="category" value="Pulau" class="filter-chip">Pulau</button>
            <button type="submit" name="category" value="Air Terjun" class="filter-chip">Air Terjun</button>
            <button type="submit" name="category" value="Danau" class="filter-chip">Danau</button>
            <button type="submit" name="category" value="Gunung" class="filter-chip">Gunung</button>
        </div>
    </form>
