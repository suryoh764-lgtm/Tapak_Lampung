# -*- coding: utf-8 -*-
"""
TapakBot NLP Engine — Flask API
Mengintegrasikan pipeline NLP dari project_nlp.py dengan data Tapak Lampung.

Fitur NLP:
  - Preprocessing: case folding, cleaning, tokenisasi, stopword removal, stemming (Sastrawi)
  - Spell Correction (pyspellchecker)
  - Synonym Expansion (kosakata wisata Lampung)
  - Intent Detection (Wisata, Kuliner, Open Trip, Sapaan, Tiket)
  - TF-IDF Cosine Similarity untuk rekomendasi semantik
"""

from flask import Flask, request, jsonify                        # type: ignore  # noqa: E402
from flask_cors import CORS                                      # type: ignore  # noqa: E402
import mysql.connector                                           # type: ignore  # noqa: E402
import re
import string
import nltk                                                      # type: ignore  # noqa: E402
from nltk.corpus import stopwords                                # type: ignore  # noqa: E402
from nltk.tokenize import RegexpTokenizer                        # type: ignore  # noqa: E402
from sklearn.feature_extraction.text import TfidfVectorizer      # type: ignore  # noqa: E402
from sklearn.metrics.pairwise import cosine_similarity           # type: ignore  # noqa: E402
from spellchecker import SpellChecker                            # type: ignore  # noqa: E402
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory       # type: ignore  # noqa: E402

app = Flask(__name__)
CORS(app)

# ─────────────────────────────────────────────
# INISIALISASI NLP TOOLS
# ─────────────────────────────────────────────
nltk.download('stopwords', quiet=True)

factory = StemmerFactory()
stemmer = factory.create_stemmer()

list_stopwords = set(stopwords.words('indonesian'))
tokenizer = RegexpTokenizer(r'\w+')

# ─────────────────────────────────────────────
# KAMUS SINONIM (disesuaikan konteks Lampung)
# ─────────────────────────────────────────────
SYNONYM_MAP = {
    'healing':     ['santai', 'relaksasi', 'tenang', 'alam', 'refreshing'],
    'refreshing':  ['santai', 'relaksasi', 'alam', 'healing'],
    'jalan':       ['wisata', 'libur', 'berlibur'],
    'view':        ['pemandang', 'pemandangan', 'panorama'],
    'foto':        ['instagramable', 'spot'],
    'murah':       ['hemat', 'terjangkau'],
    'nongkrong':   ['cafe', 'santai', 'kopi'],
    'kuliner':     ['makan', 'gastronomi', 'santap', 'masakan'],
    'hidden_gem':  ['unik', 'alam', 'tersembunyi'],
    'pantai':      ['pesisir', 'laut', 'tepi'],
    'pulau':       ['island', 'kepulauan'],
    'snorkeling':  ['selam', 'menyelam', 'terumbu', 'karang'],
    'surfing':     ['selancar', 'ombak', 'gelombang'],
    'danau':       ['telaga', 'waduk', 'situ'],
    'air_terjun':  ['curug', 'waterfall', 'terjun'],
    'sejuk':       ['dingin', 'adem', 'segar'],
    'petualangan': ['adventure', 'tantangan', 'hiking', 'trekking'],
    'lumba':       ['lumba-lumba', 'dolphin', 'mamalia'],
    'trip':        ['paket', 'tour', 'wisata', 'perjalanan'],
    'open_trip':   ['paket', 'tour', 'ikut', 'rombongan'],
    'camping':     ['berkemah', 'tenda', 'kemah'],
    'lampung':     ['sumatera', 'bandar'],
    'viral':       ['populer', 'trend', 'terkenal'],
    'keluarga':    ['anak', 'family', 'bersama'],
    'seruit':      ['ikan', 'bakar', 'tempoyak', 'khas'],
    'kopi':        ['robusta', 'arabika', 'minuman', 'cafe'],
}

# ─────────────────────────────────────────────
# KAMUS KATA TIDAK BAKU (SLANG / GAUL)
# ─────────────────────────────────────────────
SLANG_MAP = {
    # Kata Ganti & Tanya
    'yg': 'yang',
    'gmn': 'bagaimana',
    'gimana': 'bagaimana',
    'dmn': 'dimana',
    'kpn': 'kapan',
    'knp': 'kenapa',
    'napa': 'kenapa',
    'brp': 'berapa',
    'brapa': 'berapa',
    'syp': 'siapa',
    'apaan': 'apa',
    
    # Keterangan Tempat & Waktu
    'dkt': 'dekat',
    'tmpt': 'tempat',
    'tpt': 'tempat',
    'jauh': 'jauh',
    'bsk': 'besok',
    'kmrn': 'kemarin',
    'nnt': 'nanti',
    'ntar': 'nanti',
    'skrg': 'sekarang',
    'hr': 'hari',
    
    # Kata Sifat & Keterangan
    'bgs': 'bagus',
    'keren': 'bagus',
    'mntp': 'bagus',
    'mantap': 'bagus',
    'bnyk': 'banyak',
    'dikit': 'sedikit',
    'bgt': 'sekali',
    'banget': 'sekali',
    'aja': 'saja',
    'jg': 'juga',
    'kyk': 'seperti',
    'kek': 'seperti',
    'sm': 'sama',
    'trs': 'terus',
    'trus': 'terus',
    'lg': 'lagi',
    
    # Konjungsi & Preposisi
    'klo': 'kalau',
    'kalo': 'kalau',
    'kalok': 'kalau',
    'krn': 'karena',
    'karna': 'karena',
    'tp': 'tapi',
    'tpi': 'tapi',
    'dr': 'dari',
    'dg': 'dengan',
    'dgn': 'dengan',
    'utk': 'untuk',
    'buat': 'untuk',
    'ttg': 'tentang',
    'sblm': 'sebelum',
    
    # Kata Kerja & Status
    'udh': 'sudah',
    'udah': 'sudah',
    'dah': 'sudah',
    'sdh': 'sudah',
    'blm': 'belum',
    'blom': 'belum',
    'ga': 'tidak',
    'gk': 'tidak',
    'gak': 'tidak',
    'ngga': 'tidak',
    'nggak': 'tidak',
    'gtw': 'tidak tahu',
    'gatau': 'tidak tahu',
    'y': 'ya',
    'iy': 'ya',
    'pgn': 'ingin',
    'pengen': 'ingin',
    'mo': 'ingin',
    
    # Kosakata Wisata & Makanan
    'rekomen': 'rekomendasi',
    'inpo': 'informasi',
    'info': 'informasi',
    'mkn': 'makan',
    'minum': 'minuman',
    'kuy': 'ayo',
    'otw': 'berangkat',
    'pantey': 'pantai',
    'pante': 'pantai',
    'nginep': 'menginap',
    'penginapan': 'menginap',
    'uenak': 'enak',
    'wenak': 'enak'
}

# ─────────────────────────────────────────────
# KOORDINAT REFERENSI LOKASI LAMPUNG
# ─────────────────────────────────────────────
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

# ─────────────────────────────────────────────
# DATA DESTINASI (di-load saat startup untuk TF-IDF)
# ─────────────────────────────────────────────
# Koordinat berdasarkan data riset tiap destinasi
DESTINATION_COORDS = {
    1: (-5.6728, 105.2183),  # Pulau Pahawang Kecil
    2: (-5.7493, 105.1927),  # Teluk Kiluan
    3: (-5.2500, 103.9200),  # Pantai Mandiri Krui
    4: (-5.4852, 104.6903),  # Air Terjun Way Lalaan
    5: (-4.8625, 103.9306),  # Danau Ranau
    6: (-5.7500, 105.1900),  # Pulau Kelapa
}


# ─────────────────────────────────────────────
# FUNGSI DATABASE
# ─────────────────────────────────────────────
# ─────────────────────────────────────────────
# 1. FUNGSI DATABASE
# ─────────────────────────────────────────────
def get_db_connection():
    """
    Fungsi ini digunakan untuk membuka koneksi ke database MySQL.
    Menggunakan user 'root', tanpa password, di port 8000, 
    dan memilih database 'tapak_lampung'.
    """
    return mysql.connector.connect(
        host='127.0.0.1',
        port=3306,
        user='root',
        password='',
        database='tapak_lampung'
    )


def load_all_data():
    """
    Fungsi ini mengambil seluruh data dari tabel 'destinations', 'culinaries', 
    dan 'trips' di database. Data ini nantinya akan digunakan untuk:
    1. Membangun model TF-IDF (pencarian semantik).
    2. Menjawab pertanyaan pengguna secara langsung.
    """
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    # Ambil data destinasi wisata
    cursor.execute("SELECT id, name, location, description, category, entrance_fee, rating FROM destinations")
    destinations = cursor.fetchall()

    # Ambil data kuliner khas
    cursor.execute("SELECT id, name, description, category FROM culinaries")
    culinaries = cursor.fetchall()

    # Ambil data paket open trip
    cursor.execute("SELECT id, name, description, duration, price FROM trips")
    trips = cursor.fetchall()

    cursor.close()
    conn.close()
    return destinations, culinaries, trips


# ─────────────────────────────────────────────
# 2. PIPELINE PREPROCESSING NLP
# ─────────────────────────────────────────────
def preprocess_text(text):
    """
    Fungsi ini membersihkan teks agar lebih mudah dipahami oleh mesin (komputer):
    1. Case folding : Mengubah semua huruf menjadi huruf kecil.
    2. Cleaning     : Menghapus angka dan tanda baca.
    3. Tokenisasi   : Memecah kalimat menjadi kata-kata (token).
    4. Stopword     : Membuang kata hubung (yang, di, ke, dari) yang tidak penting.
    5. Stemming     : Mengubah kata berimbuhan menjadi kata dasar (misal: 'memakan' -> 'makan').
    """
    if not text:
        return ''
    text = str(text).lower()
    text = re.sub(r'\d+', '', text)
    text = text.translate(str.maketrans('', '', string.punctuation))
    text = re.sub(r'[^a-zA-Z\s]', '', text)
    text = re.sub(r'\s+', ' ', text).strip()

    # Normalisasi kata tidak baku (Slang)
    words = text.split()
    words = [SLANG_MAP.get(w, w) for w in words]
    text = ' '.join(words)

    tokens = tokenizer.tokenize(text)
    tokens = [w for w in tokens if w not in list_stopwords]
    tokens = [stemmer.stem(w) for w in tokens]
    return ' '.join(tokens)


def expand_synonyms(tokens, synonym_map=SYNONYM_MAP):
    """
    Memperluas kata-kata yang diketik user dengan sinonimnya.
    Contoh: Jika user mengetik 'healing', sistem juga akan mencari kata 'santai' dan 'alam'.
    Ini meningkatkan peluang ditemukannya wisata yang tepat.
    """
    expanded = []
    for token in tokens:
        expanded.append(token)
        if token in synonym_map:
            expanded.extend(synonym_map[token])
    return list(set(expanded))


spell_checker_global = None


def correct_spelling(tokens):
    """
    Mengoreksi typo (salah ketik) dari user.
    Contoh: 'pahawng' akan otomatis dikoreksi menjadi 'pahawang'.
    """
    global spell_checker_global
    if spell_checker_global is None:
        return tokens
    corrected = []
    for word in tokens:
        correction = spell_checker_global.correction(word)
        corrected.append(correction if correction else word)
    return corrected


def process_user_query(query):
    """
    Fungsi utama NLP yang menggabungkan 3 tahap:
    1. Preprocessing (dibersihkan)
    2. Koreksi ejaan (typo dibenarkan)
    3. Ekspansi sinonim (kata ditambahkan sinonimnya)
    """
    preprocessed = preprocess_text(query)
    tokens = tokenizer.tokenize(preprocessed)
    corrected = correct_spelling(tokens)
    expanded = expand_synonyms(corrected)
    return ' '.join(expanded)


# ─────────────────────────────────────────────
# 3. DETEKSI INTENT (Tujuan User)
# ─────────────────────────────────────────────
def identify_intent(user_message_raw):
    """
    Mendeteksi apa sebenarnya yang ingin dicari oleh pengguna (Intent)
    berdasarkan kata kunci spesifik di dalam pesannya.
    Misal: Jika ada kata 'makan' atau 'seruit', berarti intent-nya adalah 'Kuliner'.
    """
    msg = user_message_raw.lower()
    # Ubah kata tidak baku di pesan mentah untuk deteksi intent yang lebih akurat
    words_raw = msg.translate(str.maketrans('', '', string.punctuation)).split()
    words_raw = [SLANG_MAP.get(w, w) for w in words_raw]
    msg = ' '.join(words_raw)

    sapaan_kw = ['halo', 'hai', 'hi', 'pagi', 'siang', 'malam', 'bot', 'apa kabar', 'selamat', 'woi', 'bro', 'min']
    tiket_kw  = ['tiket', 'harga', 'htm', 'biaya', 'bayar', 'berapa']
    kuliner_kw = ['kuliner', 'makan', 'makanan', 'minuman', 'kopi', 'seruit', 'pindang',
                  'gulai', 'restoran', 'warung', 'lapar', 'haus', 'camilan', 'jajan', 'enak']
    trip_kw   = ['trip', 'open trip', 'paket', 'tour', 'ikut', 'rombongan', 'booking', 'travel']
    wisata_kw = ['wisata', 'pantai', 'pulau', 'danau', 'air terjun', 'teluk', 'gunung',
                 'healing', 'liburan', 'rekreasi', 'tempat', 'destinasi', 'explore',
                 'snorkeling', 'surfing', 'camping', 'jalan', 'main', 'bagus', 'indah']

    if any(k in msg for k in tiket_kw):
        return 'Tiket'
    if any(k in msg for k in sapaan_kw):
        return 'Sapaan'
    if any(k in msg for k in kuliner_kw):
        return 'Kuliner'
    if any(k in msg for k in trip_kw):
        return 'Trip'
    if any(k in msg for k in wisata_kw):
        return 'Wisata'
    return 'Umum'


# ─────────────────────────────────────────────
# 4. MEMBANGUN MODEL KECERDASAN BUATAN (TF-IDF)
# ─────────────────────────────────────────────
def build_tfidf_model(texts):
    """
    Fungsi ini membangun model matematis bernama TF-IDF.
    Tujuannya mengubah teks deskripsi tempat wisata menjadi "angka-angka" (vektor),
    sehingga komputer bisa menghitung tingkat kecocokan antara pertanyaan user
    dengan deskripsi tempat wisata di database.
    """
    # Jika data terlalu sedikit, turunkan min_df agar tidak error
    n_docs = len(texts)
    min_df = max(1, min(2, n_docs - 1))

    vectorizer = TfidfVectorizer(
        min_df=min_df,
        max_df=0.95,
        ngram_range=(1, 2) # Tangkap kata tunggal dan pasangan kata (bigram)
    )
    matrix = vectorizer.fit_transform(texts)
    vocab = vectorizer.get_feature_names_out()
    return vectorizer, matrix, vocab


# Variabel global untuk menyimpan data agar tidak di-load berulang kali
destinations_data = []
culinaries_data   = []
trips_data        = []

dest_vectorizer = None
dest_matrix     = None
cul_vectorizer  = None
cul_matrix      = None


def initialize_nlp():
    """
    Fungsi ini dijalankan SATU KALI saat server chatbot pertama kali dinyalakan.
    Tugasnya: Load database -> Bersihkan teks -> Bangun model TF-IDF -> Siapkan Spell Checker.
    Dengan begini, saat user chatting, chatbot bisa membalas dengan sangat cepat.
    """
    global destinations_data, culinaries_data, trips_data
    global dest_vectorizer, dest_matrix, cul_vectorizer, cul_matrix
    global spell_checker_global

    destinations_data, culinaries_data, trips_data = load_all_data()

    # Siapkan deskripsi wisata
    dest_texts = [
        preprocess_text(f"{d['name']} {d['location']} {d['description']} {d['category']}")
        for d in destinations_data
    ]

    # Siapkan deskripsi kuliner
    cul_texts = [
        preprocess_text(f"{c['name']} {c['description']} {c['category']}")
        for c in culinaries_data
    ]

    # Latih model AI (TF-IDF)
    if dest_texts:
        dest_vectorizer, dest_matrix, dest_vocab = build_tfidf_model(dest_texts)
    if cul_texts:
        cul_vectorizer, cul_matrix, cul_vocab = build_tfidf_model(cul_texts)

    # Masukkan semua kata ke dalam "kamus" Spell Checker agar AI mengenali kata-kata lokal
    all_vocab = []
    if dest_texts and dest_vectorizer:
        all_vocab.extend(dest_vectorizer.get_feature_names_out().tolist())
    if cul_texts and cul_vectorizer:
        all_vocab.extend(cul_vectorizer.get_feature_names_out().tolist())
    
    # Tambahkan nama-nama tempat secara eksplisit agar AI tahu itu bukan typo
    for d in destinations_data:
        all_vocab.extend(d['name'].lower().split())
    for c in culinaries_data:
        all_vocab.extend(c['name'].lower().split())

    spell_checker_global = SpellChecker()
    spell_checker_global.word_frequency.load_words(list(set(all_vocab)))

    print(f"[TapakBot NLP] Initialized: {len(destinations_data)} destinations, "
          f"{len(culinaries_data)} culinaries, {len(trips_data)} trips")

# ─────────────────────────────────────────────
# 5. FUNGSI PENCARIAN & REKOMENDASI AI
# ─────────────────────────────────────────────
def recommend_by_tfidf(processed_query, vectorizer, matrix, data_list, top_n=3, threshold=0.05):
    """
    Ini adalah otak rekomendasi (Semantic Search).
    Fungsi ini menghitung Cosine Similarity (kemiripan sudut) antara kalimat pertanyaan user
    dengan semua deskripsi data. Mengembalikan top N data yang paling cocok.
    """
    if vectorizer is None or matrix is None:
        return []

    # Ubah pertanyaan user jadi vektor angka
    query_vec = vectorizer.transform([processed_query])
    # Hitung kemiripan dengan semua data
    similarities = cosine_similarity(query_vec, matrix).flatten()

    # Gabungkan skor dengan data aslinya
    scored = [(data_list[i], float(similarities[i])) for i in range(len(data_list))]
    # Buang data yang tingkat kemiripannya terlalu rendah (di bawah threshold)
    scored = [(d, s) for d, s in scored if s >= threshold]
    # Urutkan dari skor tertinggi
    scored.sort(key=lambda x: x[1], reverse=True)
    return scored[:top_n]


# ─────────────────────────────────────────────
# 6. FUNGSI PEMBENTUK TAMPILAN BALASAN (FORMATTER)
# ─────────────────────────────────────────────
def format_destination_response(results_with_score):
    """Membentuk teks balasan untuk rekomendasi tempat wisata."""
    if not results_with_score:
        return None
    response = "Berikut rekomendasi destinasi wisata yang cocok untuk Anda di Lampung:\n\n"
    for dest, score in results_with_score:
        fee = dest['entrance_fee'] if dest.get('entrance_fee') else 'Gratis'
        rating = dest.get('rating', '-')
        response += (f"🏖️ **{dest['name']}**\n"
                     f"  📍 {dest['location']} | Kategori: {dest['category']}\n"
                     f"  {dest['description']}\n"
                     f"  🎫 Tiket: {fee} | ⭐ Rating: {rating}\n\n")
    return response.strip()


def format_culinary_response(results_with_score):
    """Membentuk teks balasan untuk rekomendasi kuliner."""
    if not results_with_score:
        return None
    response = "Inilah kuliner khas Lampung yang wajib Anda coba:\n\n"
    for cul, score in results_with_score:
        response += (f"🍽️ **{cul['name']}** ({cul['category']})\n"
                     f"  {cul['description']}\n\n")
    return response.strip()


def format_trip_response(trips):
    """Membentuk teks balasan untuk daftar paket open trip."""
    if not trips:
        return None
    response = "Open Trip tersedia di Tapak Lampung:\n\n"
    for t in trips:
        price = f"Rp {float(t['price']):,.0f}".replace(',', '.')
        response += (f"🧭 **{t['name']}**\n"
                     f"  ⏱️ Durasi: {t['duration']} | 💰 Mulai {price}/orang\n"
                     f"  {t['description']}\n\n")
    return response.strip()


# ─────────────────────────────────────────────
# 7. ENDPOINT API UTAMA (Yang dipanggil oleh website)
# ─────────────────────────────────────────────
@app.route('/chat', methods=['POST'])
def chat():
    """
    Ini adalah fungsi utama yang dipanggil oleh frontend website Anda.
    Menerima data JSON berisi pesan user, lalu mengembalikan balasan JSON.
    """
    data = request.json
    user_message = data.get('message', '').strip()

    # Jika user mengirim pesan kosong
    if not user_message:
        return jsonify({"reply": "Tolong ketik pesan Anda. 😊"})

    # ── Deteksi Intent ──
    intent = identify_intent(user_message)

    # ── Proses Query dengan NLP ──
    processed_query = process_user_query(user_message)


    # ─────────────────────────────────────────────
    # 8. LOGIKA BALASAN BERDASARKAN INTENT
    # ─────────────────────────────────────────────

    # ---------------------------------------------
    # INTENT: SAPAAN
    # Fungsi: Memberikan pesan selamat datang jika user menyapa
    # ---------------------------------------------
    if intent == 'Sapaan':
        reply = ("Halo! 👋 Saya **TapakBot**, asisten virtual wisata Lampung.\n\n"
                 "Saya bisa membantu Anda menemukan:\n"
                 "- 🏖️ **Destinasi wisata** (pantai, pulau, air terjun, danau)\n"
                 "- 🍽️ **Kuliner khas** Lampung\n"
                 "- 🧭 **Paket Open Trip** tersedia\n"
                 "- 🎫 **Harga tiket** destinasi\n\n"
                 "Mau liburan ke mana hari ini? 😊")
        return jsonify({"reply": reply})

    # ---------------------------------------------
    # INTENT: TIKET
    # Fungsi: Menampilkan harga tiket dari tempat wisata yang diketik user,
    # atau menampilkan seluruh daftar tiket jika tidak menyebut nama spesifik.
    # ---------------------------------------------
    if intent == 'Tiket':
        msg_lower = user_message.lower()
        # Sistem akan mengecek apakah user menyebut nama tempat spesifik
        for dest in destinations_data:
            if dest['name'].lower() in msg_lower or any(
                word in msg_lower for word in dest['name'].lower().split() if len(word) > 4
            ):
                fee = dest['entrance_fee'] if dest.get('entrance_fee') else 'Gratis (tidak ada data)'
                reply = (f"🎫 Harga tiket masuk **{dest['name']}**:\n"
                         f"   {fee}\n\n"
                         f"📍 Lokasi: {dest['location']}")
                return jsonify({"reply": reply})

        # Jika tidak menyebut nama spesifik, tampilkan semua daftar harga tiket
        reply = "Berikut daftar tiket masuk destinasi di Lampung:\n\n"
        for dest in destinations_data:
            fee = dest['entrance_fee'] if dest.get('entrance_fee') else 'Gratis'
            reply += f"- **{dest['name']}**: {fee}\n"
        return jsonify({"reply": reply})

    # ---------------------------------------------
    # INTENT: KULINER
    # Fungsi: Menggunakan AI (TF-IDF) untuk mencari makanan khas 
    # yang paling cocok dengan pencarian user.
    # ---------------------------------------------
    if intent == 'Kuliner':
        # Minta AI mencari kuliner paling relevan berdasarkan input user
        scored = recommend_by_tfidf(processed_query, cul_vectorizer, cul_matrix, culinaries_data, top_n=3)

        if scored:
            reply = format_culinary_response(scored)
        else:
            # Jika AI tidak menemukan yang spesifik, tampilkan daftar umum
            reply = "Kuliner khas Lampung yang wajib dicoba:\n\n"
            for c in culinaries_data:
                reply += f"🍽️ **{c['name']}** ({c['category']})\n  {c['description']}\n\n"

        reply += "\n\n💬 Ingin tahu rekomendasi wisata atau open trip? Tanyakan saja!"
        return jsonify({"reply": reply.strip()})

    # ---------------------------------------------
    # INTENT: TRIP
    # Fungsi: Mencari paket open trip berdasarkan nama yang disebut user.
    # ---------------------------------------------
    if intent == 'Trip':
        msg_lower = user_message.lower()
        matched_trips = trips_data

        # Filter mencari paket trip berdasarkan nama paket yang diketik user
        for t in trips_data:
            if any(kw in msg_lower for kw in t['name'].lower().split() if len(kw) > 3):
                matched_trips = [t]
                break

        reply = format_trip_response(matched_trips)
        if not reply:
            reply = "Maaf, belum ada paket trip yang tersedia saat ini. Silakan cek kembali nanti."

        reply += "\n\n📞 Hubungi organizer untuk informasi booking lebih lanjut!"
        return jsonify({"reply": reply})

    # ---------------------------------------------
    # INTENT: WISATA / UMUM (Pencarian Utama)
    # Fungsi: Menggunakan skor kecerdasan buatan (TF-IDF Semantic Search) 
    # untuk mencarikan 3 rekomendasi tempat wisata terbaik sesuai kata-kata user.
    # ---------------------------------------------
    scored = recommend_by_tfidf(processed_query, dest_vectorizer, dest_matrix, destinations_data, top_n=3)

    if scored:
        reply = format_destination_response(scored)
    else:
        # Jika AI gagal menemukan dengan metode canggih, coba cari manual (kata per kata)
        msg_lower = user_message.lower()
        fallback = []
        for dest in destinations_data:
            if any(kw in msg_lower for kw in [dest['name'].lower(), dest['category'].lower(), dest['location'].lower().split(',')[0]]):
                fallback.append((dest, 0.0))
        
        if fallback:
            reply = format_destination_response(fallback[:3])
        else:
            # Jika user nanya asal-asalan, bot akan memberikan 3 rekomendasi wisata acak
            import random
            sample = random.sample(destinations_data, min(3, len(destinations_data)))
            reply = "Ada banyak destinasi indah di Lampung! Ini rekomendasi untuk Anda:\n\n"
            for dest in sample:
                fee = dest['entrance_fee'] if dest.get('entrance_fee') else 'Gratis'
                reply += (f"🏖️ **{dest['name']}** — {dest['location']}\n"
                          f"  {dest['description'][:100]}...\n"
                          f"  🎫 Tiket: {fee}\n\n")

    # Tambahkan kalimat penutup / saran pertanyaan lain
    reply += "\n\n💡 *Tips: Tanya juga tentang kuliner khas atau paket open trip yang tersedia!*"
    return jsonify({"reply": reply})


# ─────────────────────────────────────────────
# 9. ENTRY POINT (Titik Awal Eksekusi Server)
# ─────────────────────────────────────────────
# Fungsi: Bagian ini adalah 'saklar utama'. Kode di bawah ini HANYA akan 
# dijalankan jika file app.py dieksekusi secara langsung.
if __name__ == '__main__':
    # Sebelum server menyala, muat data & latih model AI terlebih dahulu
    initialize_nlp()
    
    # Jalankan server API web Flask di alamat localhost port 5005
    print("[TapakBot NLP] Server berjalan di http://127.0.0.1:5005")
    app.run(host='127.0.0.1', port=5005, debug=False)
