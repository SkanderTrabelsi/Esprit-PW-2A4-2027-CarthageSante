(() => {
  'use strict';
  const { Chart } = globalThis;
  if (!Chart) {
    return;
  }

  const root = document.documentElement;
  const cssVar = (name, fallback = '') =>
    getComputedStyle(root).getPropertyValue(name).trim() || fallback;

  const alpha = (color, opacity) => {
    let hex = String(color).trim().replace(/^#/, '');
    if (!/^[\da-f]{3}([\da-f]{3})?$/i.test(hex)) {
      return color;
    }
    if (hex.length === 3) {
      hex = [...hex].map((c) => c + c).join('');
    }
    const [r, g, b] = [0, 2, 4].map((i) => Number.parseInt(hex.slice(i, i + 2), 16));
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
  };

  const areaFill =
    (color, from = 0.35, to = 0) =>
    (context) => {
      const { ctx, chartArea } = context.chart;
      if (!chartArea) {
        return alpha(color, from);
      }
      const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
      gradient.addColorStop(0, alpha(color, from));
      gradient.addColorStop(1, alpha(color, to));
      return gradient;
    };

  let applied = [];

  const apply = () => {
    const text = cssVar('--bs-secondary-color', '#6c757d');
    const body = cssVar('--bs-body-color', '#212529');
    const surface = cssVar('--bs-body-bg', '#fff');
    const grid = `rgba(${cssVar('--bs-emphasis-color-rgb', '0, 0, 0')}, 0.08)`;
    const rtl = root.getAttribute('dir') === 'rtl';
    const d = Chart.defaults;
    applied = [text, grid];

    d.font.family = cssVar('--bs-body-font-family', d.font.family);
    d.font.size = 12;
    d.color = text;
    d.borderColor = grid;
    d.maintainAspectRatio = false;

    d.scale.grid.color = grid;
    d.scale.border.color = grid;
    d.scale.ticks.padding = 8;
    d.set('scales.linear', { border: { display: false } });
    d.set('scales.category', { grid: { display: false } });

    d.elements.line.borderWidth = 2;
    d.elements.point.radius = 0;
    d.elements.point.hoverRadius = 5;
    d.elements.point.hitRadius = 8;
    d.elements.point.hoverBorderWidth = 2;
    d.elements.point.borderColor = surface;
    d.elements.bar.borderRadius = 4;
    d.elements.arc.borderColor = surface;
    d.elements.arc.borderWidth = 2;

    const { legend, tooltip } = d.plugins;
    legend.position = 'bottom';
    legend.rtl = rtl;
    Object.assign(legend.labels, {
      color: body,
      usePointStyle: true,
      pointStyle: 'circle',
      boxWidth: 8,
      boxHeight: 8,
      padding: 16,
    });

    Object.assign(tooltip, {
      rtl,
      backgroundColor: surface,
      borderColor: cssVar('--bs-border-color', '#dee2e6'),
      borderWidth: 1,
      titleColor: cssVar('--bs-emphasis-color', '#000'),
      bodyColor: body,
      titleFont: { weight: '600' },
      padding: 10,
      cornerRadius: 6,
      caretSize: 5,
      boxPadding: 4,
      usePointStyle: true,
    });

    for (const type of ['line', 'bar']) {
      Chart.overrides[type].interaction = { mode: 'index', intersect: false };
    }
  };

  const forgetThemeColors = (options, stale) => {
    for (const [key, value] of Object.entries(options)) {
      if (value && typeof value === 'object' && !Array.isArray(value)) {
        forgetThemeColors(value, stale);
      } else if (/color$/i.test(key) && stale.includes(value)) {
        delete options[key];
      }
    }
  };

  const refresh = () => {
    const stale = applied;
    apply();
    for (const chart of Object.values(Chart.instances)) {
      forgetThemeColors(chart.config.options.scales || {}, stale);
      chart.update('none');
    }
  };

  apply();
  new MutationObserver(refresh).observe(root, {
    attributes: true,
    attributeFilter: ['data-bs-theme', 'dir'],
  });
  document.fonts?.addEventListener('loadingdone', refresh);

  globalThis.lteChartTheme = { cssVar, alpha, areaFill, refresh };

  const canvas = document.querySelector('#sales-chart');
  if (!canvas) {
    return;
  }

  new Chart(canvas, {
    type: 'line',
    data: {
      labels: ["Jan '23", "Feb '23", "Mar '23", "Apr '23", "May '23", "Jun '23", "Jul '23"],
      datasets: [
        {
          label: 'Digital Goods',
          data: [28, 48, 40, 19, 86, 27, 90],
          borderColor: '#0d6efd',
          backgroundColor: areaFill('#0d6efd'),
          pointBackgroundColor: '#0d6efd',
          fill: true,
          tension: 0.4,
        },
        {
          label: 'Electronics',
          data: [65, 59, 80, 81, 56, 55, 40],
          borderColor: '#20c997',
          backgroundColor: areaFill('#20c997'),
          pointBackgroundColor: '#20c997',
          fill: true,
          tension: 0.4, 
        },
      ],
    },
    options: {
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            title: ([item]) =>
              `${new Date(2023, item.dataIndex).toLocaleString('en', { month: 'long' })} 2023`,
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
})();