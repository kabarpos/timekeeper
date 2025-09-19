@extends('layouts.admin-layout')

@section('title', 'System Monitoring')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">System Monitoring</h1>
        <p class="text-gray-600">Monitor aplikasi TimeKeeper secara real-time</p>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">System Status</p>
                    <p class="text-2xl font-bold text-green-600" id="system-status">Healthy</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Requests</p>
                    <p class="text-2xl font-bold text-blue-600" id="total-requests">0</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Error Rate</p>
                    <p class="text-2xl font-bold text-red-600" id="error-rate">0%</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Response Time</p>
                    <p class="text-2xl font-bold text-yellow-600" id="avg-response-time">0ms</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Checks -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Health Checks</h2>
            <div id="health-checks" class="space-y-3">
                <!-- Health checks will be loaded here -->
            </div>
            <button onclick="refreshHealthChecks()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Refresh Health Checks
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">System Metrics</h2>
            <div id="system-metrics" class="space-y-3">
                <!-- System metrics will be loaded here -->
            </div>
            <button onclick="refreshMetrics()" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Refresh Metrics
            </button>
        </div>
    </div>

    <!-- Recent Errors -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Errors</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response Time</th>
                    </tr>
                </thead>
                <tbody id="recent-errors" class="bg-white divide-y divide-gray-200">
                    <!-- Recent errors will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Performance Trends</h2>
        <div class="h-64 flex items-center justify-center text-gray-500">
            <p>Performance chart akan ditampilkan di sini</p>
        </div>
    </div>
</div>

<script>
// Auto-refresh data every 30 seconds
setInterval(() => {
    refreshHealthChecks();
    refreshMetrics();
    refreshRecentErrors();
}, 30000);

// Load initial data
document.addEventListener('DOMContentLoaded', () => {
    refreshHealthChecks();
    refreshMetrics();
    refreshRecentErrors();
});

async function refreshHealthChecks() {
    try {
        const response = await fetch('/health');
        const data = await response.json();
        
        // Update system status
        const statusElement = document.getElementById('system-status');
        statusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
        statusElement.className = `text-2xl font-bold ${getStatusColor(data.status)}`;
        
        // Update health checks
        const checksContainer = document.getElementById('health-checks');
        checksContainer.innerHTML = '';
        
        Object.entries(data.checks).forEach(([name, check]) => {
            const checkElement = document.createElement('div');
            checkElement.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
            checkElement.innerHTML = `
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-3 ${getStatusDotColor(check.status)}"></div>
                    <span class="font-medium text-gray-900">${name.charAt(0).toUpperCase() + name.slice(1)}</span>
                </div>
                <span class="text-sm text-gray-600">${check.response_time_ms || 0}ms</span>
            `;
            checksContainer.appendChild(checkElement);
        });
    } catch (error) {
        console.error('Error refreshing health checks:', error);
    }
}

async function refreshMetrics() {
    try {
        const response = await fetch('/health/metrics');
        const data = await response.json();
        
        // Update metrics display
        const metricsContainer = document.getElementById('system-metrics');
        metricsContainer.innerHTML = '';
        
        // Memory usage
        const memoryElement = document.createElement('div');
        memoryElement.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
        memoryElement.innerHTML = `
            <span class="font-medium text-gray-900">Memory Usage</span>
            <span class="text-sm text-gray-600">${(data.metrics.memory.usage / 1024 / 1024).toFixed(2)} MB</span>
        `;
        metricsContainer.appendChild(memoryElement);
        
        // Database connections
        const dbElement = document.createElement('div');
        dbElement.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
        dbElement.innerHTML = `
            <span class="font-medium text-gray-900">DB Connections</span>
            <span class="text-sm text-gray-600">${data.metrics.database.connections}</span>
        `;
        metricsContainer.appendChild(dbElement);
        
        // Cache stats
        const cacheElement = document.createElement('div');
        cacheElement.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
        cacheElement.innerHTML = `
            <span class="font-medium text-gray-900">Cache Hit Rate</span>
            <span class="text-sm text-gray-600">${calculateHitRate(data.metrics.cache)}%</span>
        `;
        metricsContainer.appendChild(cacheElement);
        
    } catch (error) {
        console.error('Error refreshing metrics:', error);
    }
}

async function refreshRecentErrors() {
    // This would fetch recent error logs
    // For now, we'll show a placeholder
    const errorsContainer = document.getElementById('recent-errors');
    errorsContainer.innerHTML = `
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                No recent errors found
            </td>
        </tr>
    `;
}

function getStatusColor(status) {
    switch (status) {
        case 'healthy': return 'text-green-600';
        case 'degraded': return 'text-yellow-600';
        case 'unhealthy': return 'text-red-600';
        default: return 'text-gray-600';
    }
}

function getStatusDotColor(status) {
    switch (status) {
        case 'healthy': return 'bg-green-500';
        case 'warning': return 'bg-yellow-500';
        case 'unhealthy': 
        case 'critical': return 'bg-red-500';
        default: return 'bg-gray-500';
    }
}

function calculateHitRate(cache) {
    const total = cache.hits + cache.misses;
    return total > 0 ? Math.round((cache.hits / total) * 100) : 0;
}
</script>
@endsection