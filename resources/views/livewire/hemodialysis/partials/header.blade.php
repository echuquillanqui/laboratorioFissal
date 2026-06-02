<div class="container-fluid px-3 px-lg-5 clinical-page">
    <section class="clinical-hero mb-4">
        <div class="clinical-hero-content">
            <span class="eyebrow text-primary">Historias Clínicas</span>
            <h1 class="fw-bold mb-2">{{ $title }}</h1>
            <p class="text-muted mb-0">Módulo de Hemodiálisis integrado con pacientes, permisos, PDFs y formularios Livewire.</p>
        </div>
        <div class="clinical-hero-badge"><div><i class="fa-solid fa-heart-pulse"></i><span>HD</span></div></div>
    </section>
    @if (session('status'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('status') }}</div>
    @endif
