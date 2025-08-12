    // Hero background slideshow logic
    let heroSection = document.querySelector('.hero');
    let slides = document.querySelectorAll('.hero .slide');
    let currentSlide = 0;

    function showSlide(n) {
        slides[currentSlide].classList.remove('active');
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');

        // Change hero background with gradient overlay
        let bg = slides[currentSlide].getAttribute('data-bg');
        heroSection.style.backgroundImage = `linear-gradient(rgba(40,53,147,0.5), rgba(40,53,147,0.5)), url('${bg}')`;
    }

    function plusSlides(n) {
        showSlide(currentSlide + n);
    }

    // Initialize
    showSlide(0);
    // --- Service Tabs Dynamic Content ---
document.addEventListener('DOMContentLoaded', function() {
    const serviceData = {
        'info-eng': [
            'Data Science',
            'Data Engineering',
            'Cloud Engineering',
            'Visualizations',
            'Talent Acquisition'
        ],
        'it-consult': [
            'Business Intelligence Service',
            'Process Automation',
            'Software Development',
            'Machine Learning',
            'Cloud Computing',
            'Automated Testing Solutions',
            'Staffing and Placement',
            'Online Training'
        ],
        'biz-consult': [
            'Marketing Services',
            'Sales Consulting',
            'IT Operations Support',
            'Lead Generation'
        ]
    };
    function showService(tab) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.service-grid').forEach(grid => {
            grid.classList.add('hidden');
            grid.classList.remove('animated');
        });
        const grid = document.getElementById(tab);
        grid.innerHTML = serviceData[tab].map(item => `<div class="service-card">${item}</div>`).join('');
        document.querySelector(`.tab-btn[onclick*="${tab}"]`).classList.add('active');
        grid.classList.remove('hidden');
        // Animate in
        setTimeout(() => grid.classList.add('animated'), 10);
    }
    window.showService = showService;
    // Initial fill
    showService('info-eng');
});