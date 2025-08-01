// Matrix Digital Rain Animation using GSAP
class MatrixRain {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext('2d');
        this.columns = [];
        this.fontSize = 14;
        this.matrixChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*()_+-=[]{}|;:,.<>?`~アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
        
        this.init();
        this.createColumns();
        this.animate();
        
        // Mouse interaction
        this.setupMouseInteraction();
        
        // Resize handler
        window.addEventListener('resize', () => this.handleResize());
    }
    
    init() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
        
        this.ctx.fillStyle = '#000000';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        
        this.ctx.font = `${this.fontSize}px 'Source Code Pro', monospace`;
        this.ctx.textAlign = 'center';
    }
    
    createColumns() {
        const columnCount = Math.floor(this.canvas.width / this.fontSize);
        this.columns = [];
        
        for (let i = 0; i < columnCount; i++) {
            this.columns.push({
                x: i * this.fontSize,
                y: Math.random() * this.canvas.height,
                speed: Math.random() * 3 + 1,
                chars: [],
                length: Math.random() * 20 + 10,
                brightness: Math.random() * 0.5 + 0.5
            });
            
            // Initialize character trail for each column
            for (let j = 0; j < this.columns[i].length; j++) {
                this.columns[i].chars.push({
                    char: this.getRandomChar(),
                    opacity: 1 - (j / this.columns[i].length)
                });
            }
        }
    }
    
    getRandomChar() {
        return this.matrixChars[Math.floor(Math.random() * this.matrixChars.length)];
    }
    
    animate() {
        // Clear canvas with fade effect
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        
        this.columns.forEach((column, index) => {
            // Draw each character in the column trail
            column.chars.forEach((charData, charIndex) => {
                const y = column.y - (charIndex * this.fontSize);
                
                if (y > 0 && y < this.canvas.height + this.fontSize) {
                    // Create glow effect for leading character
                    if (charIndex === 0) {
                        this.ctx.shadowColor = '#00ff00';
                        this.ctx.shadowBlur = 10;
                        this.ctx.fillStyle = '#ffffff';
                    } else {
                        this.ctx.shadowBlur = 0;
                        const opacity = charData.opacity * column.brightness;
                        this.ctx.fillStyle = `rgba(0, 255, 0, ${opacity})`;
                    }
                    
                    this.ctx.fillText(charData.char, column.x + this.fontSize/2, y);
                }
            });
            
            // Reset shadow
            this.ctx.shadowBlur = 0;
            
            // Move column down
            column.y += column.speed;
            
            // Reset column when it goes off screen
            if (column.y > this.canvas.height + (column.length * this.fontSize)) {
                column.y = -column.length * this.fontSize;
                column.speed = Math.random() * 3 + 1;
                column.brightness = Math.random() * 0.5 + 0.5;
                
                // Randomly change characters
                column.chars.forEach(charData => {
                    if (Math.random() < 0.1) {
                        charData.char = this.getRandomChar();
                    }
                });
            }
        });
        
        requestAnimationFrame(() => this.animate());
    }
    
    setupMouseInteraction() {
        let mouseX = 0;
        let mouseY = 0;
        
        this.canvas.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            
            // Create ripple effect at mouse position
            this.createRipple(mouseX, mouseY);
        });
        
        this.canvas.addEventListener('click', (e) => {
            this.createExplosion(e.clientX, e.clientY);
        });
    }
    
    createRipple(x, y) {
        const nearbyColumns = this.columns.filter(col => 
            Math.abs(col.x - x) < 100
        );
        
        nearbyColumns.forEach(column => {
            column.speed += 0.5;
            column.brightness = Math.min(column.brightness + 0.3, 1);
            
            // Restore normal speed after a delay
            setTimeout(() => {
                column.speed = Math.max(column.speed - 0.5, 1);
                column.brightness = Math.max(column.brightness - 0.3, 0.5);
            }, 1000);
        });
    }
    
    createExplosion(x, y) {
        // Create burst of new characters at click position
        for (let i = 0; i < 10; i++) {
            const angle = (Math.PI * 2 * i) / 10;
            const distance = Math.random() * 100 + 50;
            const burstX = x + Math.cos(angle) * distance;
            const burstY = y + Math.sin(angle) * distance;
            
            // Create temporary characters that fade out
            this.createTempChar(burstX, burstY);
        }
    }
    
    createTempChar(x, y) {
        const char = this.getRandomChar();
        const element = document.createElement('div');
        element.textContent = char;
        element.style.cssText = `
            position: fixed;
            left: ${x}px;
            top: ${y}px;
            color: #00ff00;
            font-family: 'Source Code Pro', monospace;
            font-size: ${this.fontSize}px;
            pointer-events: none;
            z-index: 1000;
            text-shadow: 0 0 10px #00ff00;
        `;
        
        document.body.appendChild(element);
        
        // Animate with GSAP
        gsap.to(element, {
            duration: 2,
            y: y + 100,
            opacity: 0,
            scale: 0,
            rotation: Math.random() * 360,
            ease: "power2.out",
            onComplete: () => {
                document.body.removeChild(element);
            }
        });
    }
    
    handleResize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
        this.createColumns();
    }
}

// Matrix Loading Animation
class MatrixLoader {
    constructor() {
        this.loader = document.getElementById('matrix-loader');
        this.init();
    }
    
    init() {
        // Animate typing text
        const typingTexts = document.querySelectorAll('.typing-text');
        
        typingTexts.forEach((text, index) => {
            gsap.to(text, {
                opacity: 1,
                y: 0,
                duration: 1,
                delay: index * 0.8,
                ease: "power2.out"
            });
        });
        
        // Hide loader after all text animations
        setTimeout(() => {
            gsap.to(this.loader, {
                opacity: 0,
                duration: 1,
                onComplete: () => {
                    this.loader.style.display = 'none';
                }
            });
        }, 5000);
    }
}

// Interactive Elements Enhancement
class MatrixInterface {
    constructor() {
        this.setupInteractiveElements();
        this.setupGlitchEffects();
    }
    
    setupInteractiveElements() {
        // Card hover effects
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    scale: 1.02,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
            
            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
        
        // Button pulse effects
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                gsap.to(button, {
                    boxShadow: "0 0 20px rgba(0, 255, 0, 0.5)",
                    duration: 0.3
                });
            });
            
            button.addEventListener('mouseleave', () => {
                gsap.to(button, {
                    boxShadow: "0 0 0px rgba(0, 255, 0, 0)",
                    duration: 0.3
                });
            });
        });
        
        // Form field glitch on focus
        const formFields = document.querySelectorAll('.form-control, .table-input');
        formFields.forEach(field => {
            field.addEventListener('focus', () => {
                this.glitchElement(field);
            });
        });
    }
    
    setupGlitchEffects() {
        // Random glitch effect on headers
        const headers = document.querySelectorAll('h1, h2, h3');
        headers.forEach(header => {
            setInterval(() => {
                if (Math.random() < 0.05) { // 5% chance every interval
                    this.glitchText(header);
                }
            }, 2000);
        });
    }
    
    glitchElement(element) {
        const timeline = gsap.timeline();
        timeline
            .to(element, {
                duration: 0.1,
                x: Math.random() * 4 - 2,
                y: Math.random() * 4 - 2,
                repeat: 3,
                yoyo: true
            })
            .to(element, {
                duration: 0.1,
                x: 0,
                y: 0
            });
    }
    
    glitchText(element) {
        const originalText = element.textContent;
        const glitchChars = '!@#$%^&*()_+-=[]{}|;:,.<>?`~';
        
        // Replace some characters with glitch characters
        let glitchedText = originalText.split('').map(char => {
            return Math.random() < 0.3 ? glitchChars[Math.floor(Math.random() * glitchChars.length)] : char;
        }).join('');
        
        element.textContent = glitchedText;
        
        // Restore original text after short delay
        setTimeout(() => {
            element.textContent = originalText;
        }, 150);
    }
}

// Initialize Matrix System
document.addEventListener('DOMContentLoaded', () => {
    // Start Matrix rain animation
    const matrixRain = new MatrixRain('matrix-canvas');
    
    // Start loading sequence
    const matrixLoader = new MatrixLoader();
    
    // Initialize interactive interface
    setTimeout(() => {
        const matrixInterface = new MatrixInterface();
    }, 5000);
});