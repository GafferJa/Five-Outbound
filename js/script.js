// Slideshow functionality with animation
let slideIndex = 0;
const slides = document.getElementsByClassName('slide');
function showSlides(n) {
    if (n >= slides.length) slideIndex = 0;
    if (n < 0) slideIndex = slides.length - 1;
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove('active');
    }
    slides[slideIndex].classList.add('active');
}
function plusSlides(n) {
    slideIndex += n;
    showSlides(slideIndex);
}
// Auto slideshow
setInterval(() => {
    plusSlides(1);
}, 5000);
// Initial display
showSlides(slideIndex);

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