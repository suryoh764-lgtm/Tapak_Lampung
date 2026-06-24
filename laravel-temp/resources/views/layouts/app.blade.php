<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tapak Lampung — Platform pariwisata terintegrasi untuk Provinsi Lampung. Temukan hidden gems, open trip, dan kuliner khas Lampung.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tapak Lampung')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>
    @include('partials.svg-defs')
    @include('partials.mobile-nav')
    @include('partials.nav')

    @yield('content')

    @include('partials.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <!-- Chatbot Widget -->
    <div id="chatbot-container" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end;">
        <!-- Jendela Obrolan (Hidden by default) -->
        <div id="chat-window" style="display: none; width: 350px; height: 450px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); flex-direction: column; overflow: hidden; margin-bottom: 20px; transition: all 0.3s ease;">
            <div style="background: var(--accent); color: white; padding: 15px 20px; font-family: 'Outfit'; font-weight: 600; font-size: 16px; display: flex; justify-content: space-between; align-items: center;">
                <span style="display: flex; align-items: center; gap: 8px;">
                    🤖 TapakBot
                </span>
                <button onclick="toggleChat()" style="background: none; border: none; color: white; cursor: pointer; font-size: 18px; line-height: 1;">&times;</button>
            </div>
            
            <div id="chat-messages" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; font-size: 14px; background: var(--bg);">
                <div style="background: var(--surface); color: var(--text); padding: 12px 16px; border-radius: 16px 16px 16px 4px; border: 1px solid var(--border); align-self: flex-start; max-width: 85%; line-height: 1.5;">
                    Halo! 👋 Saya TapakBot. Saya bisa membantu Anda mencari rekomendasi destinasi wisata, open trip, maupun kuliner khas di Lampung. Ada yang bisa saya bantu?
                </div>
            </div>
            
            <div style="padding: 15px; border-top: 1px solid var(--border); background: var(--surface); display: flex; gap: 10px; align-items: center;">
                <input type="text" id="chat-input" placeholder="Tanya tentang wisata..." onkeypress="if(event.key === 'Enter') sendMessage()" style="flex: 1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 20px; outline: none; font-size: 14px; background: var(--bg); color: var(--text);">
                <button onclick="sendMessage()" style="background: var(--accent); color: white; border: none; border-radius: 50%; width: 42px; height: 42px; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>
        
        <!-- Tombol Pemicu -->
        <button onclick="toggleChat()" style="background: var(--accent); color: white; border: none; width: 65px; height: 65px; border-radius: 50%; cursor: pointer; box-shadow: 0 5px 20px rgba(0,0,0,0.25); display: flex; justify-content: center; align-items: center; transition: transform 0.3s ease, background 0.3s;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M5 15v4a2 2 0 0 0 2 2h4"></path></svg>
        </button>
    </div>

    <script>
        function toggleChat() {
            const win = document.getElementById('chat-window');
            if (win.style.display === 'none') {
                win.style.display = 'flex';
                document.getElementById('chat-input').focus();
            } else {
                win.style.display = 'none';
            }
        }

        async function sendMessage() {
            const input = document.getElementById('chat-input');
            const msg = input.value.trim();
            if(!msg) return;
            
            // Tambahkan pesan user ke UI
            addMessage(msg, 'user');
            input.value = '';
            
            // Tambahkan efek "mengetik..."
            const typingId = addMessage('Mengetik...', 'bot', true);
            
            try {
                const response = await fetch('http://127.0.0.1:5005/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: msg })
                });
                const data = await response.json();
                
                // Hapus efek "mengetik..."
                document.getElementById(typingId).remove();
                
                // Ubah format jawaban (Bold dan Baris Baru)
                let formattedReply = data.reply.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>').replace(/\n/g, '<br>');
                addMessage(formattedReply, 'bot');
            } catch (error) {
                document.getElementById(typingId).remove();
                addMessage('Maaf, server TapakBot sedang tidak bisa dihubungi saat ini.', 'bot');
            }
        }
        
        function addMessage(text, sender, isTyping = false) {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            const id = 'msg-' + Date.now();
            div.id = id;
            
            div.style.padding = '12px 16px';
            div.style.maxWidth = '85%';
            div.style.lineHeight = '1.5';
            
            if (sender === 'user') {
                div.style.background = 'var(--accent)';
                div.style.color = 'white';
                div.style.borderRadius = '16px 16px 4px 16px';
                div.style.alignSelf = 'flex-end';
            } else {
                div.style.background = 'var(--surface)';
                div.style.color = 'var(--text)';
                div.style.border = '1px solid var(--border)';
                div.style.borderRadius = '16px 16px 16px 4px';
                div.style.alignSelf = 'flex-start';
                if (isTyping) {
                    div.style.opacity = '0.7';
                    div.style.fontStyle = 'italic';
                }
            }
            
            div.innerHTML = text;
            container.appendChild(div);
            
            // Auto scroll ke bawah
            container.scrollTop = container.scrollHeight;
            
            return id;
        }
    </script>
</body>

</html>
