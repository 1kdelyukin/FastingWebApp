// Import specific Chart.js components
import { 
    Chart, 
    BarController, 
    BarElement, 
    CategoryScale, 
    LinearScale, 
    Title, 
    Tooltip, 
    Legend 
} from 'chart.js';

// Import the annotation plugin
import annotationPlugin from 'chartjs-plugin-annotation';

// Register the necessary components with Chart.js
Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Title,
    Tooltip,
    Legend,
    annotationPlugin
);
document.addEventListener('DOMContentLoaded', () => {

    const canvas = document.getElementById('weeklyFastingChart');
    if (!canvas) return; // Exit if the element doesn't exist

    // Parse the JSON data from the data attribute
    let barData;

    try {
        barData = JSON.parse(canvas.dataset.chartData);
    } catch (error) {
        console.error("Failed to parse chart data:", error);
        return;
    }

    // console.log("Identifier:", barData[0]);  // Debugging, check the identifier
    // Check if the component is in Dashboard or in a different page


    if (!barData || !Array.isArray(barData)) {
        console.error("Invalid chart data:", barData);
        return;
    }

    // Function to set canvas size based on device pixel ratio
    function setCanvasSize(canvas) {
        const parent = canvas.parentElement;
        const computedStyle = getComputedStyle(parent);

        // Get the width and height from Tailwind classes (e.g., max-w-xl and h-96)
        const width = parseInt(computedStyle.width);
        const height = parseInt(computedStyle.height);

        // Get device pixel ratio


        // Set canvas width and height attributes (in actual pixels)
        canvas.width = width ;
        
        canvas.style.width = `${width}px`;

        canvas.height = height *1.5;
        canvas.style.height = `${height*2}px`;

        // Scale the context to match the DPR
        const ctx = canvas.getContext('2d');
        ctx.scale(1, 1);
    }

    setCanvasSize(canvas);

    // Initialize Chart.js without manual sizing
    initChart(barData);

    function initChart(data) {
        // Validate data structure
        if (!data.every(item => 'date' in item && 'total_fasting_minutes' in item)) {
            console.error("Chart data has invalid structure:", data);
            return;
        }

        // a) X-axis labels for days: e.g., "Mon", "Tue", ...
        const labels = data.map(item => {
            const dateObj = new Date(item.date);
            return dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }); // e.g., "Mar 3"
        });

        // b) Convert minutes to hours
        const fastingHours = data.map(item => {
            return (item.total_fasting_minutes / 60).toFixed(2);
        });

        // c) Determine bar color for each day based on fasting hours
        const barColors = fastingHours.map(hours => hours >= 16 ? '#3F8855' : '#ED6D52');

        // d) Define scaling factors (optional if using responsive)
        const canvasWidth = canvas.clientWidth;
        const canvasHeight = canvas.clientHeight;

        const titleFontSize = Math.max(12, canvasWidth * 0.03);
        const tickFontSize = Math.max(8, canvasWidth * 0.03);
        const tooltipFontSize = Math.max(8, canvasWidth * 0.045);

        // e) Create the chart
        const ctx = canvas.getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            axisY:{
                lineThickness: 0
        
            },
            data: {
                labels: labels,
                datasets: [{
                    data: fastingHours,
                    backgroundColor: barColors,
                    borderRadius: 20,
                    borderSkipped: false
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: true,
                layout: {

                },
                plugins: {
                    // Chart Title
                    title: {
                        display: false,
                        text: 'Weekly Fasting Duration',
                        font: {
                            family: 'Inria Sans',
                            size: titleFontSize,
                            weight: '800'
                        },
                        color: '#000000'
                    },
                    // Hide the legend
                    legend: {
                        display: false
                    },
                    annotation: {
                            annotations: [
                                {
                                    type: 'line',
                                    mode: 'horizontal',
                                    // dashed line
                                    borderDash: [2, 2],
                                    scaleID: 'y',
                                    value: 16,
                                    borderColor: '#000000',
                                    borderWidth: 2,
                                    }
                                
                            ]
                        },
                    // Tooltip Configuration
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#F7F1DE',
                        titleColor: '#000000',
                        bodyColor: '#000000',
                        borderColor: '#FFFFFF',
                        borderWidth: 1,
                        titleFont: {
                            family: 'Inria Sans',
                            size: tooltipFontSize,
                            weight: '600'
                        },
                        bodyFont: {
                            family: 'Inria Sans',
                            size: tooltipFontSize,
                            weight: '600'
                        },
                        footerFont: {
                            family: 'Inria Sans',
                            size: tooltipFontSize,
                            weight: '600'
                        },
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} hours`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'Inria Sans',
                                size: tickFontSize,
                                weight: '0'
                            },
                            color: '#000000'
                        },
                        border: {
                            display: false // Hides the Y-axis line
                        },
                        
                    },
                    y: {
                        beginAtZero: true,
                        max: 24,
                        grid: {
                            display: false, // Hides grid lines
                            drawBorder: false // Removes border on Y-axis
                        },
                        ticks: {
                            display: true,
                            stepSize: 16,
                            font: {
                                family: 'Inria Sans',
                                size: tickFontSize + 5,
                                weight: '0'
                            },
                            color: '#000000',
                            callback: function(value, index, values) {
                                if (value === 16) {
                                    return '16h';
                                }
                                if (value === 0) {
                                    return '0h';
                                }
                                return '';
                            }
                        },
                        border: {
                            display: false // Hides the Y-axis line
                        }
                    }
                    
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }

});
