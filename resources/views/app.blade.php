<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título da página para SEO -->
    <title>MaxCine - Filmes e Séries Online Grátis | Baixe Agora!</title>
    <!-- Descrição para SEO aprimorada -->
    <meta name="description"
        content="MaxCine: Seu aplicativo grátis para streaming ilimitado de filmes e séries online em HD. Assista aos seus conteúdos favoritos a qualquer hora, em qualquer lugar. Baixe já o app MaxCine e desfrute de entretenimento grátis!">
    <!-- Palavras-chave para SEO aprimoradas -->
    <meta name="keywords"
        content="MaxCine, filmes grátis, séries grátis, streaming online, assistir filmes, assistir séries, app de filmes, app de séries, entretenimento grátis, baixar aplicativo, cinema online, download MaxCine, melhor app de streaming">
    <!-- Open Graph para redes sociais -->
    <meta property="og:title" content="MaxCine - Filmes e Séries Online Grátis">
    <meta property="og:description"
        content="MaxCine: Seu aplicativo grátis para streaming ilimitado de filmes e séries online em HD. Assista aos seus conteúdos favoritos a qualquer hora, em qualquer lugar. Baixe já!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.seusite.com.br/maxcine-download">
    <!-- Substitua pelo URL real da sua landing page -->
    <meta property="og:image" content="https://placehold.co/1200x630/4a0082/ffffff?text=MaxCine+App">
    <!-- Imagem do seu app, agora com tema roxo -->
    <meta property="og:site_name" content="MaxCine App">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Configuração da fonte Inter */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a202c;
            /* Fundo escuro para um visual moderno */
            color: #e2e8f0;
            /* Texto claro */
        }
    </style>
</head>

<body class="antialiased">
    <!-- Header Section -->
    <header class="bg-gray-900 shadow-lg py-4">
        <nav class="container mx-auto px-6 flex justify-between items-center">
            <!-- Logo MaxCine com cor roxa -->
            <a href="#"
                class="text-3xl font-bold text-purple-500 rounded-lg p-2 hover:bg-gray-800 transition duration-300">MaxCine</a>
            <div class="hidden md:flex space-x-6">
                <a href="#features" class="text-gray-300 hover:text-white transition duration-300">Recursos</a>
                <a href="#download" class="text-gray-300 hover:text-white transition duration-300">Download</a>
                <a href="#faq" class="text-gray-300 hover:text-white transition duration-300">FAQ</a>
            </div>
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </nav>
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu"
            class="hidden md:hidden fixed inset-0 bg-gray-900 bg-opacity-95 z-50 flex flex-col items-center justify-center space-y-8">
            <button id="close-mobile-menu" class="absolute top-6 right-6 text-white text-3xl focus:outline-none">
                &times;
            </button>
            <a href="#features" class="text-white text-2xl hover:text-purple-500 transition duration-300"
                onclick="document.getElementById('mobile-menu').classList.add('hidden')">Recursos</a>
            <a href="#download" class="text-white text-2xl hover:text-purple-500 transition duration-300"
                onclick="document.getElementById('mobile-menu').classList.add('hidden')">Download</a>
            <a href="#faq" class="text-white text-2xl hover:text-purple-500 transition duration-300"
                onclick="document.getElementById('mobile-menu').classList.add('hidden')">FAQ</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-gray-800 to-gray-900 py-20 md:py-32 overflow-hidden">
        <!-- Background imagem, ajustar para refletir filmes e series -->
        <div
            class="absolute inset-0 bg-[url('https://placehold.co/1920x1080/0d0f1a/3f475b?text=Filmes+e+Series')] bg-cover bg-center opacity-20">
        </div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h1
                class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6 rounded-lg bg-gray-900 bg-opacity-70 p-4 inline-block">
                MaxCine: Seu Cinema Pessoal, Grátis e Ilimitado!
            </h1>
            <p class="text-lg md:text-2xl text-gray-300 mb-10 max-w-3xl mx-auto">
                Assista aos melhores filmes e séries online, sem custo e sem interrupções.
                A diversão da telona na palma da sua mão!
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- Botão de Download Principal com tema roxo -->
                <a href="#download"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-lg">
                    Baixe Agora Grátis!
                </a>
                <a href="#features"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-lg">
                    Saiba Mais
                </a>
            </div>
            <div class="mt-12 flex justify-center">
                <!-- Imagem do app em smartphone, pode ser substituída por uma captura de tela real -->
                <img src="https://placehold.co/400x250/4a0082/ffffff?text=MaxCine+App" alt="MaxCine App em Smartphone"
                    class="rounded-xl shadow-2xl border-4 border-gray-700 max-w-full h-auto" />
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-24 bg-gray-900">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white text-center mb-12">
                Por Que Escolher MaxCine?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Feature 1: Totalmente Grátis -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.132l-3.218 3.218m0 0a2.5 2.5 0 11-3.536-3.536A2.5 2.5 0 0111.534 11.132zM19 12a7 7 0 11-14 0 7 7 0 0114 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Totalmente Grátis</h3>
                    <p class="text-gray-300 text-center">
                        Assista a milhares de títulos sem pagar nada. Sem assinaturas, sem taxas ocultas.
                    </p>
                </div>
                <!-- Feature 2: Ampla Biblioteca -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Ampla Biblioteca</h3>
                    <p class="text-gray-300 text-center">
                        Um catálogo gigantesco de filmes e séries de todos os gêneros, atualizado constantemente.
                    </p>
                </div>
                <!-- Feature 3: Qualidade HD -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L12 19.25L14.25 17M12 4.75V19.25M19.25 12H4.75"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Qualidade HD</h3>
                    <p class="text-gray-300 text-center">
                        Desfrute de seus conteúdos favoritos em alta definição, com a melhor experiência visual.
                    </p>
                </div>
                <!-- Feature 4: Disponível Onde Quiser -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c1.657 0 3 1.343 3 3v2a3 3 0 01-3 3m0-12V5m0 16v-2m-6-3H4m16 0h-2M6.343 7.657l-1.414-1.414M19.071 19.071l-1.414-1.414m-3.536 0L9.071 9.071m-1.414 1.414L6.343 7.657">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Disponível Onde Quiser</h3>
                    <p class="text-gray-300 text-center">
                        Assista no seu smartphone, tablet ou espelhe na sua Smart TV. Compatibilidade total!
                    </p>
                </div>
                <!-- Feature 5: Interface Intuitiva -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Interface Intuitiva</h3>
                    <p class="text-gray-300 text-center">
                        Fácil de usar, navegação simples e busca rápida para encontrar o que você quer assistir.
                    </p>
                </div>
                <!-- Feature 6: Sempre Atualizado -->
                <div class="bg-gray-800 p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="text-purple-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-5a2 2 0 00-2-2h-3v5zM4 11V4a2 2 0 012-2h5M20 4v5a2 2 0 002 2h-5"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white text-center mb-4">Sempre Atualizado</h3>
                    <p class="text-gray-300 text-center">
                        Novos filmes e séries adicionados frequentemente para você nunca ficar sem opções.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action / Download Section -->
    <section id="download" class="py-16 md:py-24 bg-gray-800 text-center">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8">
                Pronto para Começar a Maratona?
            </h2>
            <p class="text-lg md:text-2xl text-gray-300 mb-12 max-w-2xl mx-auto">
                Baixe o MaxCine agora e mergulhe em um universo de entretenimento sem limites e sem custo!
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-6 sm:space-y-0 sm:space-x-8">
                <!-- Google Play Button -->
                <a href="https://maxcine.fun/download/maxcine.apk" target="_blank"
                    rel="noopener noreferrer"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-xl flex items-center justify-center">
                    <!-- Icone Google Play -->
                    <svg class="w-7 h-7 mr-3" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 24l12-12L7 0v24zM16.5 6.5l-9.5 5.5v-11zM7 17.5l9.5-5.5v11z"></path>
                    </svg>
                    Download para Android
                </a>
                <!-- Apple App Store Button (Placeholder) -->
                {{-- <a href="#"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-xl flex items-center justify-center cursor-not-allowed opacity-70">
                    <!-- Icone Apple -->
                    <svg class="w-7 h-7 mr-3" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 0C8.686 0 6.002 2.686 6.002 6.002c0 2.82 1.83 5.232 4.316 6.104l-1.042 3.738C9.278 16.52 9.002 17.5 9.002 18.5c0 1.932 1.568 3.5 3.5 3.5s3.5-1.568 3.5-3.5c0-1.002-.276-1.98-.772-2.884l-1.042-3.738C16.168 11.234 18.002 8.822 18.002 6.002c0-3.316-2.686-6.002-6.002-6.002z">
                        </path>
                    </svg>
                    Download para iOS (Em Breve)
                </a> --}}
            </div>
            <!-- Novas Opções: Telegram e Website -->
            <div class="flex flex-col sm:flex-row justify-center space-y-6 sm:space-y-0 sm:space-x-8 mt-8">
                <!-- Botão Telegram com tema roxo -->
                {{-- <a href="https://t.me/seucanalmaxcine" target="_blank" rel="noopener noreferrer"
                    class="bg-purple-700 hover:bg-purple-800 text-white font-bold py-4 px-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-xl flex items-center justify-center">
                    <!-- Icone Telegram (exemplo SVG simples) -->
                    <svg class="w-7 h-7 mr-3" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm6.05 8.16l-2.772 13.064c-.292 1.378-1.026 1.704-2.115 1.096l-4.232-3.136-2.057 1.984c-.332.32-.598.572-1.22.572-.45 0-.85-.164-1.084-.508-.344-.508-.328-1.26.04-1.872l3.072-2.964L4.858 8.12c-.22-.646-.076-1.122.518-1.312l15.11-5.714c.484-.184.97-.034 1.34.336.37.37.52 1.05.28 1.58z">
                        </path>
                    </svg>
                    Junte-se no Telegram
                </a> --}}
                <!-- Botão Website com tema roxo -->
                <a href="https://maxcine.fun" target="_blank" rel="noopener noreferrer"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-4 px-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300 ease-in-out text-xl flex items-center justify-center">
                    <!-- Icone Website (exemplo SVG simples de globo) -->
                    <svg class="w-7 h-7 mr-3" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.41-7-7.07 0-1.74.67-3.32 1.76-4.51L12 17.53l-1 2.4zM10.15 4.09c.47-.28 1-.48 1.59-.61V20.1C11.53 17.47 13.56 15.42 16 13.56c-.95-2.02-2.02-3.69-3.05-4.88-.6-.64-1.26-1.17-1.95-1.57zm.05-1.97c-.12.06-.24.13-.36.2-.2-.25-.4-.5-.6-.73L10.2 2.12zm7.74 3.75c-1.2-.82-2.5-1.42-3.95-1.8L12 4.07V2.1C14.7 2.45 17.07 3.82 17.94 5.82zM6.07 6.13c-.32.48-.61.98-.87 1.5l-1.91-1.04C3.86 5.82 4.96 4.95 6.07 6.13zM12 20.9V22c-2.73-.39-5.11-1.78-6.94-3.79 1.13-.53 2.21-.9 3.23-1.04l3.71 3.73z">
                        </path>
                    </svg>
                    Acessar Website
                </a>
            </div>
            <p class="text-gray-400 text-sm mt-8">
                *O download para iOS está em desenvolvimento e será lançado em breve.
            </p>
        </div>
    </section>

    <!-- FAQ Section (Optional but good for user queries and SEO) -->
    <section id="faq" class="py-16 md:py-24 bg-gray-900">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white text-center mb-12">
                Perguntas Frequentes (FAQ)
            </h2>
            <div class="max-w-3xl mx-auto space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold text-white mb-2">O MaxCine é realmente grátis?</h3>
                    <p class="text-gray-300">
                        Sim, o MaxCine é 100% gratuito. Você não é obrigado a pagar nada para assistir filmes e séries.
                    </p>
                </div>
                <!-- FAQ Item 2 -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold text-white mb-2">Preciso fazer cadastro para usar?</h3>
                    <p class="text-gray-300">
                        Não, você pode começar a assistir imediatamente após baixar e instalar o aplicativo, sem a
                        necessidade de cadastro.
                    </p>
                </div>
                <!-- FAQ Item 3 -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold text-white mb-2">O aplicativo está disponível para iOS?</h3>
                    <p class="text-gray-300">
                        Atualmente, o MaxCine está disponível para Android. A versão para iOS está em desenvolvimento e
                        será lançada em breve. Fique atento!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-gray-950 py-8">
        <div class="container mx-auto px-6 text-center text-gray-400">
            <p>&copy; 2025 MaxCine. Todos os direitos reservados.</p>
            <div class="mt-4 space-x-4">
                <a href="#" class="hover:text-white transition duration-300">Termos de Uso</a>
                <a href="#" class="hover:text-white transition duration-300">Política de Privacidade</a>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Mobile menu toggle logic
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    </script>
</body>

</html>