# -*- coding: utf-8 -*-
"""
project_nlp.py — TapakBot NLP Engine
=====================================
Chatbot rekomendasi wisata Lampung berbasis NLP.

Dataset  : Database MySQL tapak_lampung (destinations, culinaries, trips)
Pipeline : Preprocessing → Spell Correction → Synonym Expansion →
           TF-IDF Vectorization → Cosine Similarity → Intent Detection

Cara menjalankan:
    python project_nlp.py

Pastikan MySQL/XAMPP sudah berjalan dan package berikut terinstall:
    pip install Sastrawi pyspellchecker nltk scikit-learn mysql-connector-python pandas
"""

# ──────────────────────────────────────────────────────────────
# 1. IMPORT LIBRARY
# Catatan: package diinstall di venv-clean (bukan sistem).
#          Tanda merah di editor adalah peringatan linter, BUKAN error.
#          Kode dapat dijalankan normal dengan: ./venv-clean/bin/python project_nlp.py
# ──────────────────────────────────────────────────────────────
import re
import string
import pandas as pd                                              
import mysql.connector                                           
import nltk                                                      

from math import radians, sin, cos, sqrt, atan2
from nltk.corpus import stopwords                                
from nltk.tokenize import RegexpTokenizer                        
from sklearn.feature_extraction.text import TfidfVectorizer      
from sklearn.metrics.pairwise import cosine_similarity           
from spellchecker import SpellChecker                            
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory      

# ──────────────────────────────────────────────────────────────
# 2. UNDUH RESOURCE NLTK (hanya perlu sekali)
# ──────────────────────────────────────────────────────────────
nltk.download('stopwords', quiet=True)

# ──────────────────────────────────────────────────────────────
# 3. INISIALISASI NLP TOOLS
# ──────────────────────────────────────────────────────────────
factory  = StemmerFactory()
stemmer  = factory.create_stemmer()

list_stopwords = set(stopwords.words('indonesian'))
tokenizer      = RegexpTokenizer(r'\w+')

# ──────────────────────────────────────────────────────────────
# 4. KONEKSI DATABASE (sesuaikan port jika perlu)
# ──────────────────────────────────────────────────────────────
DB_CONFIG = {
    'host':     '127.0.0.1',
    'port':     8000,          # Port MySQL Anda (cek dengan: lsof -i :3306 atau :8000)
    'user':     'root',
    'password': '',
    'database': 'tapak_lampung'
}


def get_db_connection():
    return mysql.connector.connect(**DB_CONFIG)


def load_data_from_db():
    """Ambil data dari database dan kembalikan sebagai DataFrame."""
    conn   = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    # Destinations
    cursor.execute("""
        SELECT id, name, location, description, category,
               entrance_fee, rating, distance_km, travel_time
        FROM destinations
    """)
    destinations = cursor.fetchall()

    # Culinaries
    cursor.execute("SELECT id, name, description, category FROM culinaries")
    culinaries = cursor.fetchall()

    # Trips
    cursor.execute("SELECT id, name, description, duration, price FROM trips")
    trips = cursor.fetchall()

    cursor.close()
    conn.close()

    df_dest = pd.DataFrame(destinations)
    df_cul  = pd.DataFrame(culinaries)
    df_trip = pd.DataFrame(trips)

    return df_dest, df_cul, df_trip


# ──────────────────────────────────────────────────────────────
# 5. KAMUS SINONIM (disesuaikan konteks Lampung)
# ──────────────────────────────────────────────────────────────
SYNONYM_MAP = {
    'healing':     ['santai', 'relaksasi', 'tenang', 'alam', 'refreshing'],
    'refreshing':  ['santai', 'relaksasi', 'alam', 'healing'],
    'jalan':       ['wisata', 'libur', 'berlibur'],
    'view':        ['pemandang', 'pemandangan', 'panorama'],
    'foto':        ['instagramable', 'spot'],
    'murah':       ['hemat', 'terjangkau'],
    'nongkrong':   ['cafe', 'santai', 'kopi'],
    'kuliner':     ['makan', 'gastronomi', 'santap', 'masakan'],
    'hidden':      ['unik', 'alam', 'tersembunyi'],
    'pantai':      ['pesisir', 'laut', 'tepi', 'pasir'],
    'pulau':       ['island', 'kepulauan', 'karang'],
    'snorkeling':  ['selam', 'menyelam', 'terumbu', 'karang', 'bawah'],
    'surfing':     ['selancar', 'ombak', 'gelombang', 'surf'],
    'danau':       ['telaga', 'waduk', 'vulkanik'],
    'terjun':      ['curug', 'waterfall', 'air'],
    'sejuk':       ['dingin', 'adem', 'segar'],
    'petualangan': ['adventure', 'tantangan', 'hiking', 'trekking'],
    'lumba':       ['dolphin', 'mamalia', 'teluk'],
    'trip':        ['paket', 'tour', 'wisata', 'perjalanan'],
    'camping':     ['berkemah', 'tenda', 'malam'],
    'viral':       ['populer', 'trend', 'terkenal'],
    'keluarga':    ['anak', 'family', 'bersama'],
    'kopi':        ['robusta', 'arabika', 'minuman'],
    'seruit':      ['ikan', 'bakar', 'tempoyak', 'khas'],
    'pindang':     ['sup', 'asam', 'pedas', 'ikan'],
    'gulai':       ['santan', 'kuning', 'rempah', 'tradisional'],
}

# ──────────────────────────────────────────────────────────────
# 6. KOORDINAT REFERENSI LOKASI DI LAMPUNG
# ──────────────────────────────────────────────────────────────
LOCATION_COORDS = {
    'bandar lampung':  {'lat': -5.3971, 'lon': 105.2668},
    'pesawaran':       {'lat': -5.5010, 'lon': 105.1991},
    'tanggamus':       {'lat': -5.5067, 'lon': 104.6229},
    'pesisir barat':   {'lat': -5.0560, 'lon': 103.9370},
    'lampung barat':   {'lat': -5.2014, 'lon': 104.2021},
    'lampung selatan': {'lat': -5.5786, 'lon': 105.5430},
    'teluk kiluan':    {'lat': -5.7493, 'lon': 105.1927},
    'pahawang':        {'lat': -5.6728, 'lon': 105.2183},
    'krui':            {'lat': -5.1916, 'lon': 103.9304},
}

# Koordinat setiap destinasi berdasarkan id
DESTINATION_COORDS = {
    1: (-5.6728, 105.2183),  # Pulau Pahawang Kecil
    2: (-5.7493, 105.1927),  # Teluk Kiluan
    3: (-5.2500, 103.9200),  # Pantai Mandiri Krui
    4: (-5.4852, 104.6903),  # Air Terjun Way Lalaan
    5: (-4.8625, 103.9306),  # Danau Ranau
    6: (-5.7500, 105.1900),  # Pulau Kelapa
}


# ──────────────────────────────────────────────────────────────
# 7. FUNGSI HAVERSINE (jarak antar dua koordinat)
# ──────────────────────────────────────────────────────────────
def haversine_distance(lat1, lon1, lat2, lon2):
    """Hitung jarak (km) antara dua titik koordinat GPS."""
    R = 6371
    lat1, lon1, lat2, lon2 = map(radians, [lat1, lon1, lat2, lon2])
    dlat = lat2 - lat1
    dlon = lon2 - lon1
    a = sin(dlat / 2)**2 + cos(lat1) * cos(lat2) * sin(dlon / 2)**2
    return R * 2 * atan2(sqrt(a), sqrt(1 - a))


# ──────────────────────────────────────────────────────────────
# 8. PIPELINE PREPROCESSING NLP
# ──────────────────────────────────────────────────────────────
def preprocess_text(text):
    """
    Preprocessing teks:
    1. Case Folding   — ubah ke huruf kecil
    2. Cleaning       — hapus angka, tanda baca, karakter non-huruf
    3. Tokenisasi     — pecah menjadi token
    4. Stopword Removal
    5. Stemming       — Sastrawi (Bahasa Indonesia)
    """
    if not text:
        return ''
    text = str(text).lower()
    text = re.sub(r'\d+', '', text)
    text = text.translate(str.maketrans('', '', string.punctuation))
    text = re.sub(r'[^a-zA-Z\s]', '', text)
    text = re.sub(r'\s+', ' ', text).strip()

    tokens = tokenizer.tokenize(text)
    tokens = [w for w in tokens if w not in list_stopwords]
    tokens = [stemmer.stem(w) for w in tokens]
    return ' '.join(tokens)


def expand_synonyms(tokens, synonym_map=SYNONYM_MAP):
    """Perluas token dengan sinonim yang relevan."""
    expanded = []
    for token in tokens:
        expanded.append(token)
        if token in synonym_map:
            expanded.extend(synonym_map[token])
    return list(set(expanded))


def correct_spelling(tokens, spell):
    """Koreksi ejaan menggunakan SpellChecker."""
    corrected = []
    for word in tokens:
        correction = spell.correction(word)
        corrected.append(correction if correction else word)
    return corrected


def process_user_query(query, spell):
    """Pipeline lengkap: preprocess → spell correction → sinonim."""
    preprocessed = preprocess_text(query)
    tokens       = tokenizer.tokenize(preprocessed)
    corrected    = correct_spelling(tokens, spell)
    expanded     = expand_synonyms(corrected)
    return ' '.join(expanded)


# ──────────────────────────────────────────────────────────────
# 9. BUILD MODEL TF-IDF
# ──────────────────────────────────────────────────────────────
def build_tfidf_model(texts):
    """
    Bangun TF-IDF vectorizer.
    min_df disesuaikan otomatis agar tidak error pada dataset kecil.
    """
    n     = len(texts)
    min_df = max(1, min(2, n - 1))

    vectorizer = TfidfVectorizer(
        min_df=min_df,
        max_df=0.95,
        ngram_range=(1, 2)
    )
    matrix = vectorizer.fit_transform(texts)
    vocab  = vectorizer.get_feature_names_out()

    print(f"  → TF-IDF shape: {matrix.shape} | vocab size: {len(vocab)}")
    return vectorizer, matrix, vocab


# ──────────────────────────────────────────────────────────────
# 10. FUNGSI REKOMENDASI BERBASIS TF-IDF
# ──────────────────────────────────────────────────────────────
def recommend_destination(query, vectorizer, matrix, df, top_n=5,
                          threshold=0.05, user_lat=None, user_lon=None,
                          max_distance_km=None, spell=None):
    """
    Rekomendasi destinasi menggunakan cosine similarity TF-IDF.
    Mendukung filter berdasarkan jarak (haversine).
    """
    processed = process_user_query(query, spell) if spell else preprocess_text(query)

    query_vec   = vectorizer.transform([processed])
    cosine_sim  = cosine_similarity(query_vec, matrix).flatten()

    result_df   = df.copy()
    result_df['similarity_score'] = cosine_sim
    result_df   = result_df[result_df['similarity_score'] >= threshold]

    # Filter jarak jika koordinat user tersedia
    if user_lat is not None and user_lon is not None and max_distance_km is not None:
        def get_coord_lat(row):
            coords = DESTINATION_COORDS.get(row['id'])
            return coords[0] if coords else None

        def get_coord_lon(row):
            coords = DESTINATION_COORDS.get(row['id'])
            return coords[1] if coords else None

        result_df['_lat'] = result_df.apply(get_coord_lat, axis=1)
        result_df['_lon'] = result_df.apply(get_coord_lon, axis=1)
        result_df = result_df.dropna(subset=['_lat', '_lon'])

        result_df['distance_km'] = result_df.apply(
            lambda r: haversine_distance(user_lat, user_lon, r['_lat'], r['_lon']),
            axis=1
        )
        result_df = result_df[result_df['distance_km'] <= max_distance_km]
        result_df = result_df.sort_values(
            by=['similarity_score', 'distance_km'], ascending=[False, True]
        )
    else:
        result_df = result_df.sort_values(by='similarity_score', ascending=False)

    if result_df.empty:
        return pd.DataFrame(), "Maaf, tidak ada rekomendasi yang sesuai."

    top = result_df.head(top_n).copy()
    rename_map = {
        'name':             'Nama Tempat',
        'category':         'Kategori',
        'description':      'Deskripsi',
        'location':         'Lokasi',
        'entrance_fee':     'Tiket Masuk',
        'rating':           'Rating',
        'similarity_score': 'Similarity Score',
    }
    # Hanya rename kolom yang ada
    rename_map = {k: v for k, v in rename_map.items() if k in top.columns}
    top = top.rename(columns=rename_map)
    # Pastikan distance_km tetap numerik
    if 'distance_km' in top.columns:
        top['distance_km'] = pd.to_numeric(top['distance_km'], errors='coerce')
    return top, None


def recommend_culinary(query, vectorizer, matrix, df, top_n=3,
                       threshold=0.05, spell=None):
    """Rekomendasi kuliner menggunakan cosine similarity TF-IDF."""
    processed   = process_user_query(query, spell) if spell else preprocess_text(query)
    query_vec   = vectorizer.transform([processed])
    cosine_sim  = cosine_similarity(query_vec, matrix).flatten()

    result_df   = df.copy()
    result_df['similarity_score'] = cosine_sim
    result_df   = result_df[result_df['similarity_score'] >= threshold]
    result_df   = result_df.sort_values(by='similarity_score', ascending=False)

    if result_df.empty:
        return pd.DataFrame(), "Maaf, tidak ada kuliner yang sesuai."

    top = result_df.head(top_n).copy()
    top = top.rename(columns={
        'name':             'Nama Kuliner',
        'category':         'Kategori',
        'description':      'Deskripsi',
        'similarity_score': 'Similarity Score',
    })
    return top, None


# ──────────────────────────────────────────────────────────────
# 11. DETEKSI INTENT
# ──────────────────────────────────────────────────────────────
def identify_intent(user_message):
    """Deteksi intent berdasarkan kata kunci dari pesan asli."""
    msg = user_message.lower()

    if any(k in msg for k in ['tiket', 'harga tiket', 'tiket masuk', 'biaya masuk', 'htm']):
        return 'Tiket'
    if any(k in msg for k in ['halo', 'hai', 'hi', 'pagi', 'siang', 'malam', 'selamat', 'apa kabar']):
        return 'Sapaan'
    if any(k in msg for k in ['kuliner', 'makan', 'makanan', 'minuman', 'kopi', 'seruit',
                               'pindang', 'gulai', 'restoran', 'warung', 'lapar', 'haus']):
        return 'Kuliner'
    if any(k in msg for k in ['trip', 'open trip', 'paket wisata', 'tour', 'paket',
                               'ikut trip', 'booking', 'rombongan']):
        return 'Trip'
    if any(k in msg for k in ['jarak', 'berapa km', 'berapa jauh', 'tempuh']):
        return 'Jarak'
    if any(k in msg for k in ['wisata', 'pantai', 'pulau', 'danau', 'air terjun', 'teluk',
                               'healing', 'liburan', 'rekreasi', 'tempat', 'destinasi',
                               'explore', 'snorkeling', 'surfing', 'camping']):
        return 'Wisata'
    return 'Umum'


# ──────────────────────────────────────────────────────────────
# 12. FORMAT OUTPUT
# ──────────────────────────────────────────────────────────────
def print_destinations(df_result, user_lat=None, user_lon=None, ref_name=None):
    """Tampilkan hasil rekomendasi destinasi dengan format rapi."""
    if df_result.empty:
        print("Bot: Tidak ada hasil yang ditemukan.\n")
        return

    print("Bot: Berikut rekomendasi destinasi wisata di Lampung:\n")
    print(f"{'No':<4} {'Nama Tempat':<28} {'Lokasi':<25} {'Tiket':>22} {'Rating':>8} {'Score':>7}")
    print("─" * 100)

    for i, (_, row) in enumerate(df_result.iterrows(), 1):
        nama   = str(row.get('Nama Tempat', '-'))[:26]
        lokasi = str(row.get('Lokasi', '-'))[:23]
        tiket  = str(row.get('Tiket Masuk', 'Gratis'))[:20]
        rating = str(row.get('Rating', '-'))
        score  = f"{row.get('Similarity Score', 0):.3f}"
        print(f"{i:<4} {nama:<28} {lokasi:<25} {tiket:>22} {rating:>8} {score:>7}")

        # Deskripsi singkat
        deskripsi = str(row.get('Deskripsi', ''))
        if deskripsi:
            print(f"     💬 {deskripsi[:90]}...")

        # Jarak jika ada
        if 'distance_km' in row.index and pd.notna(row.get('distance_km')) and ref_name:
            dist_val = float(row['distance_km'])
            print(f"     📍 Jarak dari {ref_name.title()}: {dist_val:.1f} km")
        print()

    print("─" * 100)


def print_culinaries(df_result):
    """Tampilkan hasil rekomendasi kuliner."""
    if df_result.empty:
        print("Bot: Tidak ada kuliner yang sesuai.\n")
        return

    print("Bot: Rekomendasi kuliner khas Lampung:\n")
    for i, (_, row) in enumerate(df_result.iterrows(), 1):
        print(f"  {i}. 🍽️  {row.get('Nama Kuliner', '-')} [{row.get('Kategori', '-')}]")
        print(f"     {row.get('Deskripsi', '')}")
        print(f"     Skor relevansi: {row.get('Similarity Score', 0):.3f}\n")


def print_trips(df_trip):
    """Tampilkan daftar open trip."""
    if df_trip.empty:
        print("Bot: Belum ada paket trip tersedia.\n")
        return

    print("Bot: Paket Open Trip yang tersedia di Tapak Lampung:\n")
    for i, row in df_trip.iterrows():
        price = f"Rp {float(row['price']):,.0f}".replace(',', '.')
        print(f"  🧭 {row['name']}")
        print(f"     ⏱️  Durasi : {row['duration']}")
        print(f"     💰 Harga  : {price}/orang")
        print(f"     📋 {row['description']}\n")


# ──────────────────────────────────────────────────────────────
# 13. FUNGSI UTAMA CHATBOT INTERAKTIF
# ──────────────────────────────────────────────────────────────
def chatbot(df_dest, df_cul, df_trip,
            dest_vectorizer, dest_matrix,
            cul_vectorizer, cul_matrix,
            spell):
    """
    Chatbot interaktif berbasis terminal.
    Mendukung: wisata, kuliner, trip, tiket, jarak, sapaan.
    """
    print("=" * 60)
    print("  🤖 TapakBot — Asisten Wisata Lampung")
    print("  Berbasis NLP: TF-IDF + Cosine Similarity + Sastrawi")
    print("=" * 60)
    print("  Ketik 'exit' atau 'quit' untuk keluar.\n")
    print("  Contoh pertanyaan:")
    print("    - rekomendasi pantai untuk snorkeling")
    print("    - kuliner khas Lampung yang enak")
    print("    - ada open trip apa aja?")
    print("    - harga tiket pahawang")
    print("    - wisata dekat pesawaran")
    print("=" * 60 + "\n")

    while True:
        try:
            user_input = input("Anda  : ").strip()
        except (EOFError, KeyboardInterrupt):
            print("\nBot   : Terima kasih telah menggunakan TapakBot. Sampai jumpa! 👋")
            break

        if not user_input:
            continue
        if user_input.lower() in ('exit', 'quit', 'keluar'):
            print("Bot   : Terima kasih telah menggunakan TapakBot. Selamat berwisata! 🌴")
            break

        # ── Deteksi intent ──────────────────────────────────────
        intent = identify_intent(user_input)

        # ── Deteksi lokasi referensi ────────────────────────────
        user_lat = user_lon = max_dist = None
        ref_name = None
        for loc, coords in LOCATION_COORDS.items():
            if loc in user_input.lower():
                user_lat = coords['lat']
                user_lon = coords['lon']
                ref_name = loc
                # Ekstrak jarak jika disebutkan, mis. "5 km"
                m = re.search(r'(\d+)\s*km', user_input.lower())
                if m:
                    max_dist = float(m.group(1))
                elif 'terdekat' in user_input.lower():
                    max_dist = 15.0
                break

        print(f"\n[Intent terdeteksi: {intent}]")

        # ════════════════════════════════════════════════════════
        # INTENT: SAPAAN
        # ════════════════════════════════════════════════════════
        if intent == 'Sapaan':
            print("Bot   : Halo! 👋 Saya TapakBot, asisten wisata Lampung.")
            print("         Saya bisa membantu Anda menemukan:\n"
                  "         - 🏖️  Destinasi wisata (pantai, pulau, air terjun, danau)\n"
                  "         - 🍽️  Kuliner khas Lampung\n"
                  "         - 🧭  Paket Open Trip\n"
                  "         - 🎫  Informasi harga tiket\n"
                  "         Mau liburan ke mana? 😊\n")
            continue

        # ════════════════════════════════════════════════════════
        # INTENT: TIKET
        # ════════════════════════════════════════════════════════
        if intent == 'Tiket':
            found = False
            msg   = user_input.lower()
            for _, row in df_dest.iterrows():
                nama_dest = row['name'].lower()
                # Cocokkan nama destinasi dalam pesan user
                if nama_dest in msg or any(
                    w in msg for w in nama_dest.split() if len(w) > 4
                ):
                    fee = row['entrance_fee'] if row.get('entrance_fee') else 'Gratis'
                    print(f"Bot   : 🎫 Tiket masuk **{row['name']}**: {fee}")
                    print(f"         📍 Lokasi: {row['location']}\n")
                    found = True
                    break

            if not found:
                print("Bot   : Berikut daftar tiket masuk semua destinasi di Lampung:\n")
                for _, row in df_dest.iterrows():
                    fee = row['entrance_fee'] if row.get('entrance_fee') else 'Gratis'
                    print(f"  🏖️  {row['name']:<30} → {fee}")
                print()
            continue

        # ════════════════════════════════════════════════════════
        # INTENT: TRIP
        # ════════════════════════════════════════════════════════
        if intent == 'Trip':
            print_trips(df_trip)
            continue

        # ════════════════════════════════════════════════════════
        # INTENT: KULINER
        # ════════════════════════════════════════════════════════
        if intent == 'Kuliner':
            result, msg = recommend_culinary(
                user_input, cul_vectorizer, cul_matrix, df_cul,
                top_n=3, threshold=0.05, spell=spell
            )
            if msg:
                # Fallback tampilkan semua kuliner
                result = df_cul.rename(columns={
                    'name': 'Nama Kuliner', 'category': 'Kategori',
                    'description': 'Deskripsi'
                })
                result['Similarity Score'] = 0.0
            print_culinaries(result)
            continue

        # ════════════════════════════════════════════════════════
        # INTENT: JARAK
        # ════════════════════════════════════════════════════════
        if intent == 'Jarak':
            # Format: "jarak dari [lokasi] ke [destinasi]"
            found_dist = False
            for _, row in df_dest.iterrows():
                nama_dest = row['name'].lower()
                if nama_dest in user_input.lower() or any(
                    w in user_input.lower() for w in nama_dest.split() if len(w) > 4
                ):
                    dest_id    = row['id']
                    dest_coord = DESTINATION_COORDS.get(dest_id)
                    if dest_coord and ref_name:
                        dist = haversine_distance(
                            user_lat, user_lon, dest_coord[0], dest_coord[1]
                        )
                        print(f"Bot   : 📍 Jarak dari {ref_name.title()} ke {row['name']}: "
                              f"sekitar {dist:.1f} km\n")
                        found_dist = True
                        break
            if not found_dist:
                print("Bot   : Maaf, tidak bisa menghitung jarak. "
                      "Sebutkan nama destinasi dan lokasi referensi.\n"
                      "         Contoh: 'Jarak dari Bandar Lampung ke Pahawang'\n")
            continue

        # ════════════════════════════════════════════════════════
        # INTENT: WISATA / UMUM — TF-IDF Semantic Search
        # ════════════════════════════════════════════════════════
        if ref_name:
            print(f"Bot   : Mencari wisata di sekitar {ref_name.title()}"
                  + (f" dalam radius {max_dist} km" if max_dist else "") + "...\n")

        result, msg = recommend_destination(
            user_input,
            dest_vectorizer, dest_matrix, df_dest,
            top_n=5, threshold=0.05,
            user_lat=user_lat, user_lon=user_lon,
            max_distance_km=max_dist,
            spell=spell
        )

        if msg or result.empty:
            # Fallback: tampilkan semua destinasi acak
            print("Bot   : Tidak ada yang spesifik ditemukan. "
                  "Ini beberapa destinasi populer di Lampung:\n")
            sample = df_dest.sample(min(3, len(df_dest)))
            for _, row in sample.iterrows():
                fee = row['entrance_fee'] if row.get('entrance_fee') else 'Gratis'
                print(f"  🏖️  {row['name']} — {row['location']}")
                print(f"     {row['description'][:90]}...")
                print(f"     🎫 {fee} | ⭐ {row['rating']}\n")
        else:
            print_destinations(result, user_lat, user_lon, ref_name)

        print()


# ──────────────────────────────────────────────────────────────
# 14. PROGRAM UTAMA
# ──────────────────────────────────────────────────────────────
def main():
    print("\n[1/4] Memuat data dari database MySQL...")
    try:
        df_dest, df_cul, df_trip = load_data_from_db()
    except mysql.connector.Error as e:
        print(f"\n❌ Gagal koneksi ke database: {e}")
        print("   Pastikan MySQL/XAMPP sudah berjalan dan konfigurasi DB_CONFIG benar.")
        return

    print(f"      ✓ {len(df_dest)} destinasi | {len(df_cul)} kuliner | {len(df_trip)} trip")

    print("\n[2/4] Preprocessing teks dengan Sastrawi stemmer...")
    df_dest['preprocessed'] = df_dest.apply(
        lambda r: preprocess_text(
            f"{r['name']} {r['location']} {r['description']} {r['category']}"
        ), axis=1
    )
    df_cul['preprocessed'] = df_cul.apply(
        lambda r: preprocess_text(
            f"{r['name']} {r['description']} {r['category']}"
        ), axis=1
    )
    print("      ✓ Preprocessing selesai")

    print("\n[3/4] Membangun model TF-IDF...")
    print("  Destinasi:")
    dest_vectorizer, dest_matrix, dest_vocab = build_tfidf_model(
        df_dest['preprocessed'].tolist()
    )
    print("  Kuliner:")
    cul_vectorizer, cul_matrix, cul_vocab = build_tfidf_model(
        df_cul['preprocessed'].tolist()
    )

    # SpellChecker dengan vocab gabungan
    all_vocab = list(dest_vocab) + list(cul_vocab)
    for row in df_dest.itertuples():
        all_vocab.extend(str(row.name).lower().split())
    for row in df_cul.itertuples():
        all_vocab.extend(str(row.name).lower().split())

    spell = SpellChecker()
    spell.word_frequency.load_words(list(set(all_vocab)))
    print(f"      ✓ SpellChecker diinisialisasi dengan {len(all_vocab)} kata")

    print("\n[4/4] Menampilkan ringkasan dataset:\n")

    print("── DESTINASI WISATA ──")
    cols_show = ['name', 'location', 'category', 'rating']
    print(df_dest[cols_show].to_string(index=False))

    print("\n── KULINER KHAS ──")
    print(df_cul[['name', 'category', 'description']].to_string(index=False))

    print("\n── OPEN TRIP ──")
    df_trip_show = df_trip.copy()
    df_trip_show['price'] = df_trip_show['price'].apply(
        lambda p: f"Rp {float(p):,.0f}".replace(',', '.')
    )
    print(df_trip_show[['name', 'duration', 'price']].to_string(index=False))

    print("\n" + "=" * 60)
    print("  Semua model berhasil diinisialisasi. Memulai chatbot...")
    print("=" * 60 + "\n")

    # Mulai chatbot
    chatbot(
        df_dest, df_cul, df_trip,
        dest_vectorizer, dest_matrix,
        cul_vectorizer, cul_matrix,
        spell
    )


if __name__ == '__main__':
    main()
