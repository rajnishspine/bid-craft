<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BidCraft') }} - Template Management Platform</title>

        <!-- Matrix Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Source+Code+Pro:wght@400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- Matrix Login Styles -->
        <link rel="stylesheet" href="{{ asset('css/matrix-login.css') }}">
    </head>
    <body>
        <!-- Matrix Background Canvas -->
        <canvas id="matrix-canvas" class="matrix-background"></canvas>
        
        <div class="auth-wrapper">
            <div class="login-card">
                <!-- Brand Header -->
                <div class="brand-header">
                    <div class="brand-logo">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h1 class="brand-title">BIDCRAFT</h1>
                    <p class="brand-subtitle">
                        NEURAL NETWORK TENDER INTELLIGENCE MATRIX
                    </p>
                </div>
                
                <!-- Login Form -->
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Matrix Background Animation -->
        <script>
            // Matrix Digital Rain Effect
            const canvas = document.getElementById('matrix-canvas');
            const ctx = canvas.getContext('2d');

            // Set canvas size
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Matrix characters
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*()_+-=[]{}|;:,.<>?';
            const charArray = chars.split('');

            // Columns configuration
            const fontSize = 14;
            const columns = canvas.width / fontSize;
            const drops = [];

            // Initialize drops
            for (let x = 0; x < columns; x++) {
                drops[x] = 1;
            }

            // Draw function
            function draw() {
                // Black background with transparency for trail effect
                ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Green text
                ctx.fillStyle = '#00ff00';
                ctx.font = fontSize + 'px "Source Code Pro", monospace';

                // Draw characters
                for (let i = 0; i < drops.length; i++) {
                    const text = charArray[Math.floor(Math.random() * charArray.length)];
                    ctx.fillText(text, i * fontSize, drops[i] * fontSize);

                    // Reset drop to top randomly
                    if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                        drops[i] = 0;
                    }
                    drops[i]++;
                }
            }

            // Animation loop
            setInterval(draw, 35);

            // Typing effect for brand elements
            document.addEventListener('DOMContentLoaded', function() {
                const brandTitle = document.querySelector('.brand-title');
                const brandSubtitle = document.querySelector('.brand-subtitle');
                
                if (brandTitle) {
                    const titleText = brandTitle.textContent;
                    brandTitle.textContent = '';
                    let i = 0;
                    
                    function typeTitle() {
                        if (i < titleText.length) {
                            brandTitle.textContent += titleText.charAt(i);
                            i++;
                            setTimeout(typeTitle, 100);
                        }
                    }
                    
                    setTimeout(typeTitle, 500);
                }
                
                if (brandSubtitle) {
                    const subtitleText = brandSubtitle.textContent;
                    brandSubtitle.textContent = '';
                    let j = 0;
                    
                    function typeSubtitle() {
                        if (j < subtitleText.length) {
                            brandSubtitle.textContent += subtitleText.charAt(j);
                            j++;
                            setTimeout(typeSubtitle, 50);
                        }
                    }
                    
                    setTimeout(typeSubtitle, 2000);
                }
            });
        </script>
    </body>
</html>