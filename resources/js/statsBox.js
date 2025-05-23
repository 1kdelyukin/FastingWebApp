document.addEventListener("DOMContentLoaded", function () {
    const statsContainer = document.getElementById("stats-box");
    if (!statsContainer) return;

    // Get stats from the page (assuming they're passed via a data attribute)
    const fastingStats = JSON.parse(document.getElementById('fasting-stats').getAttribute('data-stats'));

    statsContainer.innerHTML = `
        <div class="flex flex-col items-center">
          <h2 class="text-2xl font-bold">${fastingStats.fastingDays} Days</h2>
          <p class="text-gray-500 text-sm">Fasting this month</p>
        </div>
        <div class="flex flex-col items-center">
          <h2 class="text-2xl font-bold">${fastingStats.streakDays} Day</h2>
          <p class="text-gray-500 text-sm">In streak</p>
        </div>
        <div class="flex flex-col items-center">
          <h2 class="text-2xl font-bold">${fastingStats.averageTime}</h2>
          <p class="text-gray-500 text-sm">Ave. fasting time</p>
        </div>
        <div class="flex flex-col items-center">
          <h2 class="text-2xl font-bold">${fastingStats.successRate}%</h2>
          <p class="text-gray-500 text-sm">Success rate</p>
        </div>
    `;
});