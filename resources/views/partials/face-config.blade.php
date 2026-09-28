@php
    $modelBaseUrl = rtrim(asset('models/human'), '/') . '/';
    $wasmBaseUrl  = $modelBaseUrl;
@endphp
<script>
    window.FACE_CONFIG = {
        modelBase: "{{ $modelBaseUrl }}",
        wasmBase: "{{ $wasmBaseUrl }}",
        csrf: "{{ csrf_token() }}",
        endpoints: {
            challenge: "{{ route('face-verification.challenge-data') }}",
            verify: "{{ route('face-verification.verify') }}",
            recovery: "{{ route('face-verification.recovery') }}",
            cancel: "{{ route('face-verification.cancel') }}",
            enrollAdmin: "{{ route('admin.profile.face-verification.enroll') }}",
            enrollOwner: "{{ route('owner.profile.face-verification.enroll') }}",
            disableAdmin: "{{ route('admin.profile.face-verification.disable') }}",
            disableOwner: "{{ route('owner.profile.face-verification.disable') }}"
        },
        debug: {{ config('face.debug') ? 'true' : 'false' }}
    };
</script>
