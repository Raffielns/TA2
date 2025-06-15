<div class="card shadow mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Log Aktivitas Terbaru</h6>
        <a href="#" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="card-body">
        @if ($logs->isEmpty())
            <p class="text-muted">Belum ada aktivitas tercatat.</p>
        @else
            <ul class="timeline">
                @foreach ($logs as $log)
                    <li class="timeline-item mb-3">
                        <span class="timeline-date">{{ $log->created_at->format('d M Y H:i') }}</span>
                        <div class="timeline-content">
                            <strong>{{ $log->user->name }}</strong> - {{ $log->activity }}
                            @if ($log->target)
                                <span class="text-muted">({{ $log->target }})</span>
                            @endif
                            <div class="small text-muted">{{ $log->created_at->format('d M Y') }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<style>
    .timeline {
        list-style: none;
        padding-left: 0;
        border-left: 2px solid #ddd;
        margin-left: 10px;
    }

    .timeline-item {
        position: relative;
        padding-left: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 5px;
        width: 12px;
        height: 12px;
        background-color: #3F72AF;
        border-radius: 50%;
        border: 2px solid white;
    }

    .timeline-content {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 6px;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    }
</style>
