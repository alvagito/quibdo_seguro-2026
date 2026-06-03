// Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Filtros del feed
    const filterButtons = document.querySelectorAll('.filter-btn');
    const feedCards = document.querySelectorAll('.feed-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            // Filtrar tarjetas
            feedCards.forEach(card => {
                // Obtener la fecha del atributo data-date (timestamp)
                const cardTimestamp = parseInt(card.getAttribute('data-date'));
                const cardDate = new Date(cardTimestamp * 1000);
                const now = new Date();
                
                // Calcular diferencia en milisegundos
                const diffMs = now - cardDate;
                const diffHours = diffMs / (1000 * 60 * 60);
                const diffDays = diffMs / (1000 * 60 * 60 * 24);
                
                let showCard = false;
                
                if (filter === 'all') {
                    showCard = true;
                } else if (filter === 'hoy') {
                    // Mostrar si es de las últimas 24 horas
                    showCard = diffHours <= 24;
                } else if (filter === 'semana') {
                    // Mostrar si es de los últimos 7 días
                    showCard = diffDays <= 7;
                }
                
                if (showCard) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Animación de entrada para las tarjetas
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    feedCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        observer.observe(card);
    });
    
    // Hacer los items de tipo clickeables para filtrar
    const tipoItems = document.querySelectorAll('.tipo-item');
    
    tipoItems.forEach(item => {
        item.style.cursor = 'pointer';
        
        item.addEventListener('click', function() {
            // Obtener el tipo de incidente del texto
            const tipoText = this.querySelector('span').textContent.toLowerCase();
            let tipoId = 0;
            
            if (tipoText.includes('robo')) tipoId = 1;
            else if (tipoText.includes('accidente')) tipoId = 2;
            else if (tipoText.includes('violencia')) tipoId = 3;
            else if (tipoText.includes('otro')) tipoId = 4;
            
            // Remover clase active de todos los items
            tipoItems.forEach(t => t.classList.remove('tipo-active'));
            
            // Si se hace click en el mismo tipo, mostrar todos
            if (this.classList.contains('tipo-clicked')) {
                this.classList.remove('tipo-clicked');
                feedCards.forEach(card => {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            } else {
                // Marcar como clickeado
                tipoItems.forEach(t => t.classList.remove('tipo-clicked'));
                this.classList.add('tipo-clicked');
                this.classList.add('tipo-active');
                
                // Filtrar por tipo
                feedCards.forEach(card => {
                    const cardTipo = parseInt(card.getAttribute('data-tipo'));
                    
                    if (cardTipo === tipoId) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            }
        });
    });
});
