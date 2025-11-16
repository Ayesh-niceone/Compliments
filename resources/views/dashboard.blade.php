@extends('layouts.app')

@section('content')
<style>
.no-data-box {
    border: 1px dashed #d6d6d6;
    background: #fafafa;
    padding: 35px 20px;
    border-radius: 12px;
    text-align: center;
}

.no-data-icon {
    font-size: 48px;
    color: #bfbfbf;
    margin-bottom: 10px;
}

.no-data-title {
    font-size: 18px;
    font-weight: 600;
    color: #777;
}

.no-data-desc {
    font-size: 14px;
    color: #999;
    margin-top: 4px;
}
</style>


<div class="row mt-5">
    <!-- Department -->
    <div class="col-md-6 mb-5">
        <h5 class="fw-bold mb-3">Compliments By Department</h5>
        <div id="departmentDonut" style="min-height:280px;"></div>
    </div>

    <!-- Completion Type -->
    <div class="col-md-6 mb-5">
        <h5 class="fw-bold mb-3">Compliments By Completion Type</h5>
        <div id="completionDonut" style="min-height:280px;"></div>
    </div>

    <!-- Target Type -->
    <div class="col-md-6 mb-5">
        <h5 class="fw-bold mb-3">Compliments By Target Type</h5>
        <div id="targetDonut" style="min-height:280px;"></div>
    </div>

    <!-- Care User -->
    <div class="col-md-6 mb-5">
        <h5 class="fw-bold mb-3">Compliments By Care User</h5>
        <div id="careUserDonut" style="min-height:280px;"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 <script>
    fetch('/dashboard-donuts')
    .then(res => res.json())
    .then(data => {
        renderDonut("departmentDonut", data.department_chart);
        renderDonut("completionDonut", data.completion_chart);
        renderDonut("targetDonut", data.target_type_chart);
        renderDonut("careUserDonut", data.care_user_chart);
    });

function renderDonut(elementId, chartData) {
    const container = document.querySelector(`#${elementId}`);

    // check if dataset is empty OR all values = 0
    if (!chartData.length || chartData.every(i => i.value == 0)) {

        container.innerHTML = `
            <div class="no-data-box">
                <div class="no-data-icon">📊</div>
                <div class="no-data-title">No Data Available</div>
                <div class="no-data-desc">There are no records to display for this chart.</div>
            </div>
        `;

        return;
    }

    const labels = chartData.map(i => i.label);
    const values = chartData.map(i => i.value);

    let options = {
        series: values,
        chart: {
            type: 'donut',
            height: 280
        },
        labels: labels,
        legend: { position: 'bottom' },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: "Total",
                            formatter: () =>
                                values.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        }
    };

    new ApexCharts(container, options).render();
}

 </script>
@endpush
