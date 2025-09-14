// Create floating elements for background animation
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.modern-bg');
    if (!container) return;
    
    // Create floating elements
    for (let i = 0; i < 20; i++) {
        createFloatingElement(container);
    }
});

function createFloatingElement(container) {
    const element = document.createElement('div');
    element.classList.add('floating-element');
    
    // Random size between 10px and 60px
    const size = Math.floor(Math.random() * 50) + 10;
    element.style.width = `${size}px`;
    element.style.height = `${size}px`;
    
    // Random position
    const posX = Math.floor(Math.random() * 100);
    const posY = Math.floor(Math.random() * 100);
    element.style.left = `${posX}%`;
    element.style.top = `${posY}%`;
    
    // Random opacity
    element.style.opacity = (Math.random() * 0.6 + 0.1).toString();
    
    // Animation
    const duration = Math.floor(Math.random() * 20) + 10;
    element.style.animation = `float ${duration}s linear infinite`;
    
    // Random animation delay
    element.style.animationDelay = `${Math.random() * 5}s`;
    
    // Add to container
    container.appendChild(element);
    
    // Add keyframes for this element
    addKeyframes(element, posX, posY);
}

function addKeyframes(element, startX, startY) {
    // Create a movement path
    const style = document.createElement('style');
    const animationName = `float-${Math.floor(Math.random() * 1000)}`;
    
    const endX = startX + (Math.random() * 20 - 10);
    const endY = startY + (Math.random() * 20 - 10);
    
    style.textContent = `
        @keyframes ${animationName} {
            0% { 
                transform: translate(0, 0) rotate(0deg); 
                opacity: ${element.style.opacity};
            }
            50% { 
                transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg); 
                opacity: ${Math.min(parseFloat(element.style.opacity) + 0.1, 0.6)};
            }
            100% { 
                transform: translate(0, 0) rotate(0deg); 
                opacity: ${element.style.opacity};
            }
        }
    `;
    
    document.head.appendChild(style);
    element.style.animation = `${animationName} ${element.style.animation.split(' ')[1]} ${element.style.animation.split(' ')[2]}`;
}