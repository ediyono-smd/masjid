import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.lucide = { 
    createIcons: () => createIcons({ icons }), 
    icons 
};
window.Chart = Chart;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
