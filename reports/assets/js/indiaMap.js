const indiaMap = {
    init: function(stateData) {
        this.stateData = stateData;
        this.bindEvents();
        this.updateColors();
    },

    bindEvents: function() {
        const paths = document.querySelectorAll('#indiaMap path');
        const tooltip = document.getElementById('tooltipContainer');
        
        paths.forEach(path => {
            path.addEventListener('mouseover', (e) => this.showTooltip(e, path, tooltip));
            path.addEventListener('mouseout', () => this.hideTooltip(tooltip));
        });
    },

    showTooltip: function(event, path, tooltip) {
        const state = this.stateData.find(s => s.state === path.id);
        if (!state) return;

        tooltip.innerHTML = `
            <strong>${path.getAttribute('title')}</strong><br>
            Beneficiaries: ${state.beneficiary_count}<br>
            Appeals: ${state.appeal_count}
        `;
        
        tooltip.classList.remove('d-none');
        tooltip.style.left = event.pageX + 10 + 'px';
        tooltip.style.top = event.pageY + 10 + 'px';
    },

    hideTooltip: function(tooltip) {
        tooltip.classList.add('d-none');
    },

    updateColors: function() {
        const maxCount = Math.max(...this.stateData.map(s => s.beneficiary_count));
        
        this.stateData.forEach(state => {
            const path = document.getElementById(state.state);
            if (path) {
                const intensity = (state.beneficiary_count / maxCount) * 0.8 + 0.2;
                path.style.fill = `rgba(54, 162, 235, ${intensity})`;
            }
        });
    }
};

