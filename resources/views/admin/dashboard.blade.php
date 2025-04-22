@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6e8efb 0%, #4a6cf7 100%);
        --success-gradient: linear-gradient(135deg, #28c76f 0%, #19a05e 100%);
        --warning-gradient: linear-gradient(135deg, #ffc107 0%, #f7b500 100%);
        --info-gradient: linear-gradient(135deg, #00cfe8 0%, #0097a7 100%);
        --danger-gradient: linear-gradient(135deg, #ea5455 0%, #d92550 100%);
        --purple-gradient: linear-gradient(135deg, #8a62e3 0%, #6a38ca 100%);
    }

    body {
        background-color: #f9fafc;
    }

    .page-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item {
        font-size: 0.875rem;
    }

    .stat-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        backdrop-filter: blur(5px);
        background: rgba(255, 255, 255, 0.95);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
    }

    .stats-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .stats-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.8;
        z-index: -1;
    }

    .stats-icon.primary::before {
        background: var(--primary-gradient);
    }

    .stats-icon.success::before {
        background: var(--success-gradient);
    }

    .stats-icon.warning::before {
        background: var(--warning-gradient);
    }

    .stats-icon.info::before {
        background: var(--info-gradient);
    }

    .stats-icon.purple::before {
        background: var(--purple-gradient);
    }

    .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.75rem 1.75rem 1.25rem;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2c3e50;
        margin-bottom: 0;
    }

    .table {
        border-spacing: 0 12px;
        border-collapse: separate;
        margin-top: -8px;
    }

    .table thead th {
        border-bottom: none;
        background-color: transparent;
        padding: 14px 20px;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        border-radius: 12px;
        transition: all 0.3s;
    }

    .table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
    }

    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-top: none;
        background-color: #fff;
        font-weight: 500;
    }

    .table tbody tr td:first-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .table tbody tr td:last-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .badge {
        padding: 8px 16px;
        font-weight: 500;
        border-radius: 8px;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }

    .badge.bg-success {
        background: rgba(40, 199, 111, 0.12) !important;
        color: #28c76f;
    }

    .badge.bg-warning {
        background: rgba(255, 193, 7, 0.12) !important;
        color: #ffc107;
    }

    .stat-value {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
        line-height: 1.2;
        background: linear-gradient(45deg, #2c3e50, #4a6cf7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .chart-container {
        position: relative;
        padding: 0.5rem;
    }

    .avatar-sm {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        position: relative;
        overflow: hidden;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        z-index: 1;
    }

    .avatar-sm.primary {
        background: var(--primary-gradient);
    }

    .avatar-sm.success {
        background: var(--success-gradient);
    }

    .avatar-sm.warning {
        background: var(--warning-gradient);
    }

    .bg-soft-overlay {
        position: relative;
    }

    .bg-soft-overlay:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.65);
        backdrop-filter: blur(5px);
        z-index: -1;
        border-radius: 16px;
    }
</style>
@endsection

@section('content')
<div class="page-content py-2">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row mb-2">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="page-title mb-2">لوحة التحكم</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">مرحباً بك في لوحة التحكم</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-lg-3">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary">
                                <i class="fas fa-users fs-4 text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stat-value">{{ number_format($usersCount) }}</h3>
                                <p class="stat-label mb-0">المستخدمين</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon success">
                                <i class="fas fa-user-check fs-4 text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">الموظفين</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warning">
                                <i class="fas fa-calendar-day fs-4 text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">زيارات اليوم</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon info">
                                <i class="fas fa-calendar-alt fs-4 text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">إجمالي الزيارات</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- More Stats Cards -->
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">زيارات هذا الأسبوع</p>
                            </div>
                            <div class="avatar-sm primary">
                                <span class="avatar-title">
                                    <i class="fas fa-calendar-week fs-4 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">زيارات هذا الشهر</p>
                            </div>
                            <div class="avatar-sm success">
                                <span class="avatar-title">
                                    <i class="fas fa-calendar-check fs-4 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="stat-value"></h3>
                                <p class="stat-label mb-0">المستخدمين</p>
                            </div>
                            <div class="avatar-sm warning">
                                <span class="avatar-title">
                                    <i class="fas fa-user-plus fs-4 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">إحصائيات الزيارات الشهرية</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="bookingsChart" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">توزيع الإحصائيات</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="distributionChart" height="260"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">أحدث الزيارات</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم العميل</th>
                                        <th>عامل النظافة</th>
                                        <th>النوع</th>
                                        <th>التاريخ</th>
                                        <th>الوقت</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Bookings Chart
        var ctx = document.getElementById('bookingsChart').getContext('2d');
        var months = @json(array_keys($monthlyStats));
        var bookingsData = @json(array_values($monthlyStats));

        var gradientFill = ctx.createLinearGradient(0, 0, 0, 350);
        gradientFill.addColorStop(0, 'rgba(110, 142, 251, 0.3)');
        gradientFill.addColorStop(1, 'rgba(110, 142, 251, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'عدد الزيارات',
                    data: bookingsData,
                    backgroundColor: gradientFill,
                    borderColor: '#6e8efb',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6e8efb',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 3,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#6e8efb',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                            },
                            color: '#6c757d'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                            },
                            color: '#6c757d'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' زيارة';
                            }
                        }
                    }
                }
            }
        });

        // Distribution Chart
        var distCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: ['العملاء', 'عمال النظافة', 'المستخدمين', 'الزيارات'],
                datasets: [{
                    data: [
                        {{ $customersCount }},
                        {{ $cleanersCount }},
                        {{ $usersCount }},
                        {{ $totalBookings }}
                    ],
                    backgroundColor: [
                        '#6e8efb',
                        '#28c76f',
                        '#ffc107',
                        '#8a62e3'
                    ],
                    borderColor: [
                        '#6e8efb',
                        '#28c76f',
                        '#ffc107',
                        '#8a62e3'
                    ],
                    borderWidth: 2,
                    borderRadius: 10,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13,
                                family: "'Tajawal', sans-serif"
                            },
                            color: '#6c757d'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false
                    }
                }
            }
        });
    });
</script> --}}
@endsection
